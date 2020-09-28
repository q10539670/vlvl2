<?php


namespace App\Helpers;


class SendSms
{
    protected $apiKey='905003710001';
    protected $apiSecret='9D31EB3A62A66E6E8604E05523C8D946';

    const SENDURL = 'http://api.qirui.com:7891/mt';

    /**
     * 短信发送
     * @param $phone
     * @param $content
     * @param  int  $isReport
     * @return string
     */
    public function send($phone, $content, $isReport = 0)
    {
        $requestData = array(
            'un' => $this->apiKey,
            'pw' => $this->apiSecret,
            'sm' => $content,
            'da' => $phone,
            'rd' => $isReport,
            'dc' => 15,
            'rf' => 2,
            'tf' => 3,
        );

        $url = self::SENDURL.'?'.http_build_query($requestData);
        return $this->request($url);
    }

    /**
     * 请求发送
     * @param $url
     * @return bool|string
     */
    private function request($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

}