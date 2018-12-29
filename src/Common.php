<?php
/**
 * author: ShenYan.
 * Email：52o@qq52o.cn
 * CreatedTime: 2018/12/28 11:17
 */

namespace SyRecords;


class Common
{
    /**
     * 签名计算
     * @param $parameters
     * @param $accessKeySecret
     *
     * @return string
     */
    protected function computeSignature($parameters, $accessKeySecret) {
        ksort($parameters);
        $canonicalizedQueryString = '';
        foreach ($parameters as $key => $value) {
            $canonicalizedQueryString .= '&' . $this->percentEncode($key) . '=' . $this->percentEncode($value);
        }
        $stringToSign = 'GET&%2F&' . $this->percentencode ( substr ( $canonicalizedQueryString, 1 ) );
        $signature = base64_encode(hash_hmac('sha1', $stringToSign, $accessKeySecret . '&', true));
        return $signature;
    }

    protected function percentEncode($string) {
        $string = urlencode($string);
        $string = preg_replace('/\+/', '%20', $string);
        $string = preg_replace('/\*/', '%2A', $string);
        $string = preg_replace('/%7E/', '~', $string);
        return $string;
    }

    /**
     * 发起请求
     * @param $url [请求链接]
     * @param $params [请求参数]
     *
     * @return mixed
     */
    protected function AliCurl($url, $params)
    {
        ksort($params);
        $curlUrl = $url . '?' . http_build_query($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $curlUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result,true);

        //返回请求结果
        return $result;
    }
}
