<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X210204ImagesExport;
use App\Exports\X210204LogsExport;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X210204\User;
use App\Models\Sswh\X210204\Images;
use App\Models\Sswh\X210204\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\BinaryFileResponse;
use Maatwebsite\Excel\Facades\Excel;

class X210204Controller extends Controller
{
    //中铁·龙盘湖·世纪山水投票
    protected $itemName = 'x210204';


    public function index(Request $request)
    {
        if ($request->input('order')) {
            $paginator = Images::orderBy('poll', 'desc')->orderBy('updated_at',
                'asc')->paginate(15);
        } else {
            $paginator = Images::orderBy('updated_at', 'desc')->paginate(15);
        }
        $exportImagesUrl = asset('/vlvl/x210204/export_images');
        $exportLogsUrl = asset('/vlvl/x210204/export_logs');
        $prefix = 'https://cdnn.sanshanwenhua.com/statics/';
        $total = User::count();
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:sjss210204');
        return view('sswh.sswhAdmin.x210204', [
            'title' => '中铁·龙盘湖·世纪山水·投票',
            'paginator' => $paginator,
            'prefix' => $prefix,
            'exportImagesUrl' => $exportImagesUrl,
            'exportLogsUrl' => $exportLogsUrl,
            'redisShareData' => $redisShareData,
            'total' => $total
        ]);
    }

    /**
     * 下载投票
     * @return BinaryFileResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportImages()
    {
        return Excel::download(new X210204ImagesExport(), '中铁·龙盘湖·世纪山水·投票.xlsx');
    }

    /**
     * 下载投票记录
     * @return BinaryFileResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportLogs()
    {
        return Excel::download(new X210204LogsExport(), '中铁·龙盘湖·世纪山水·投票记录.xlsx');
    }

    /**
     * 上传照片接口(postman)
     * @param Request $request
     * @return false|\Illuminate\Http\JsonResponse|string
     */
    public function uploadImages(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'info' => 'required',
        ], [
            'info.required' => '编号不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $file = $request->file("image");
        if (empty($file)) {
            return response()->json(['error' => '请先选择文件', 'code'=>500]);
        }
        if (!$file->isValid()) {
            return response()->json(['error' => '上传错误','code'=>500]);
        }
        $storeDriver = env('APP_ENV') == 'local' ? 'public' : 'prod';
        if (!$path = $file->store('/wx_items/' . $this->itemName, $storeDriver)) {
            return response()->json(['error' => '上传错误,请重新上传'], 422);

        }
        $data['image'] = $path;
        $images = Images::create($data);
        $images = Images::find($images->id);
        return $this->returnJson(1, "添加成功", [
            'images' => $images,
        ]);
    }

    function hide(Request $request)
    {
        $id = $request->input('id');
        $image = Images::find($id);
        if ($image->status ===1) {
            return 0;
        }
        $image->status = 1;
        $image->save();
        return 1;
    }

    function display(Request $request)
    {
        $id = $request->input('id');
        $image = Images::find($id);
        if ($image->status ===0) {
            return 0;
        }
        $image->status = 0;
        $image->save();
        return 1;
    }
}
