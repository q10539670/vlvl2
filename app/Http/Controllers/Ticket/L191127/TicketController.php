<?php

namespace App\Http\Controllers\Ticket\L191127;

use Illuminate\Http\Request;
use App\Models\Ticket\L191127\User as User;
use App\Models\Ticket\L191127\Ticket as Ticket;
use App\Models\Ticket\L191127\Admin;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Tdr\X191127Export;

class TicketController extends Controller
{

    public function index(Request $request)
    {
        $where = function ($query) use ($request) {
            if ($request->filled('user_id')) {
                $users = User::select('id', 'nickname')
                    ->where('id', $request->user_id)
                    ->orWhere('nickname', 'like', '%' . $request->user_id . '%')
                    ->get()->toArray();
//                var_dump($users);exit;
                $query->whereIn('user_id', array_column($users, 'id'));
                $query->orWhereIn('id', [$request->user_id]);
            }
            if ($request->filled('check_status')) {
                if ($request->check_status == 11) {
                    $query->whereIn('check_status', [11, 21, 22]);
                } else {
                    $query->where('check_status', $request->check_status);
                }
            } else {
                $query->whereIn('check_status', [0, 10, 11, 12,13,14, 20, 21, 22, 23,24]);
            }
            if ($request->filled('money')) {
                $query->where('money', $request->money);
            }
            if ($request->filled('daterangepicker_checked_at')) {
                $checkedArr = explode(' 至 ', $request->daterangepicker_checked_at);
                $query->whereBetween('checked_at', [$checkedArr[0] . ' 00:00:00', $checkedArr[1] . ' 23:59:59']);
            }
            if ($request->filled('daterangepicker_created_at')) {
                $createdArr = explode(' 至 ', $request->daterangepicker_created_at);
                $query->whereBetween('created_at', [$createdArr[0] . ' 00:00:00', $createdArr[1] . ' 23:59:59']);
            }
        };
        $paginator = Ticket::where($where)
            ->whereHas('user', function ($query) {
                $query->whereIn('status', [1, 2]);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('ticket.' . Admin::$itemPrefix . '.ticket.index', compact('paginator'));
    }

    public function serviceRedpack(Request $request)
    {
        $status = (int)$request->status;
        if ($status == 0) {
            $newStatus = 1;
            $msg = '服务已开启';
        } else {
            $newStatus = 0;
            $msg = '服务已关闭';
        }
        $redis = app('redis');
        $redis->select(12);
        $redis->hset($key = Admin::$itemPrefix . '_login:' . 'service', 'redpack', $newStatus);
        return response()->json(['code' => 1, 'message' => $msg, 'data' => ['status' => $newStatus]]);
    }

    /*
     * 人工审核不通过
     * */
    public function checkNoPass(Request $request)
    {
        $ticketId = (int)$request->ticket_id;
        $success = Ticket::where('id', $ticketId)->whereIn('check_status', [0, 11])->update([
            'check_status' => 12,
            'result_check_msg' => '不通过【人工审核】',
            'result_check_desc' => '小票不符合活动规则',
            'checked_at' => now()->toDateTimeString(),
        ]);
        $ticket = Ticket::find($ticketId);
        if ($success) {
            return response()->json([
                'code' => 1,
                'message' => '状态更新成功',
                'data' => [
                    'ticket' => $ticket,
                ],
            ]);
        }
        return response()->json([
            'code' => -1,
            'message' => '更改失败， 请稍后重试',
            'data' => [
                'ticket' => $ticket,
            ],
        ]);

    }

    /*
     * 人工审核通过
     * */
    public function checkPass(Request $request)
    {
        $ticketId = (int)$request->ticket_id;
        $success = Ticket::where('id', $ticketId)->whereIn('check_status', [0, 12])->update([
            'check_status' => 11,
            'result_check_msg' => '通过【人工审核】',
            'result_check_desc' => '',
            'checked_at' => now()->toDateTimeString(),
        ]);
        $ticket = Ticket::find($ticketId);
        if ($success) {
            return response()->json([
                'code' => 1,
                'message' => '状态更新成功',
                'data' => [
                    'ticket' => $ticket,
                ],
            ]);
        }
        return response()->json([
            'code' => -1,
            'message' => '更改失败， 请稍后重试',
            'data' => [
                'ticket' => $ticket,
            ],
        ]);

    }

    /*
     * 重置红包状态
     * */
    public function resetRedpack(Request $request)
    {
        $ticketId = (int)$request->ticket_id;
        $success = Ticket::where('id', $ticketId)->whereIn('check_status', [22, 23])->update([
            'check_status' => 11,
            'result_redpack_msg' => '',
            'result_redpack_desc' => '',
            'result_redpack' => '',
            'prize_at' => null,
        ]);
        $ticket = Ticket::find($ticketId);
        if ($success) {
            return response()->json([
                'code' => 1,
                'message' => '重置成功',
                'data' => [
                    'ticket' => $ticket,
                ],
            ]);
        }
        return response()->json([
            'code' => -1,
            'message' => '重置失败， 请稍后重试',
            'data' => [
                'ticket' => $ticket,
            ],
        ]);
    }

    /*
     * 审核 发红包
     * */
    public function checkStatus(Request $request)
    {
        $ticket = Ticket::find($request->ticket_id);
        if ($ticket->check_status != 0) {
            return response()->json(['code' => 2, 'message' => '该小票已被审核', 'data' => ['ticket' => $ticket]]);
        }
        if ($request->tongguo == -1) {  //审核不通过
            $ticket->check_status = -1;
            $ticket->describle = $request->describle;
        } else {        // 审核通过
            $prizeRedpackResult = $ticket->setPrize();  // 发红包
            $ticket->money = $prizeRedpackResult['money'];
            $ticket->describle = $prizeRedpackResult['describle'];
            $ticket->send_listid = $prizeRedpackResult['send_listid'];
            $ticket->check_status = 1;
            if ($prizeRedpackResult['money'] > 0) $ticket->user->prize_num++;
            $ticket->user->total_money += $prizeRedpackResult['money'];
            $ticket->user->save();
        }
        $ticket->checked_at = now()->toDateTimeString();
        $ticket->save();

        return response()->json([
            'code' => 1,
            'message' => '更新状态成功',
            'data' => [
                'ticket' => $ticket,
                'conf' => isset($prizeRedpackResult) ? $prizeRedpackResult['conf'] : ''
            ],
        ]);
    }

    /*
     * 重置未中奖的 审核状态
     * */
    public function resetStatus(Request $request)
    {
        $ticket = Ticket::find($request->ticket_id);
        if (($ticket->money > 0) || ($ticket->send_listid != '')) {
            return response()->json(['code' => -1, 'message' => '已中奖的小票无法重置', 'data' => ['ticket' => $ticket]]);
        }
        $ticket->check_status = 0;
        $ticket->describle = '';
        $ticket->checked_at = null;
        $ticket->save();
        return response()->json(['code' => 1, 'message' => '重置成功', 'data' => ['ticket' => $ticket]]);
    }

    /*
     *
     * */
    public function exportTickets(Request $request)
    {
        $rangeStr = '截止至' . date('Y-m-d');
        $where = function ($query) use ($request, & $rangeStr) {
            if ($request->filled('daterangepicker_created_at')) {
                $createdArr = explode(' 至 ', $request->daterangepicker_created_at);
                $query->whereBetween('created_at', [$createdArr[0], $createdArr[1]]);
                $rangeStr = substr($createdArr[0], 0, 10) . '至' . substr($createdArr[1], 0, 10);
            }
            if ($request->filled('user_id')) {
                $users = User::select('id', 'nickname')
                    ->where('id', $request->user_id)
                    ->orWhere('nickname', 'like', '%' . $request->user_id . '%')
                    ->get()->toArray();
//                var_dump($users);exit;
                $query->whereIn('user_id', array_column($users, 'id'));
                $query->orWhereIn('id', [$request->user_id]);
            }
            if ($request->filled('check_status')) {
                if ($request->check_status == 11) {
                    $query->whereIn('check_status', [11, 21, 22]);
                } else {
                    $query->where('check_status', $request->check_status);
                }
            } else {
                $query->whereIn('check_status', [0, 10, 11, 12,13,14, 20, 21, 22, 23,24]);
            }
            if ($request->filled('money')) {
                $query->where('money', $request->money);
            }
        };
        return (new X191127Export($where))->download('汤达人上传小票信息.xlsx');
    }
}
