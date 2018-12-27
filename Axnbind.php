<?php

class Sms
{
    /**
     * AXN绑定接口
     * 
     * @param        $phoneNoA
     * @param        $time
     * @param string $phoneNoB
     *
     * @return mixed
     */
    protected function bindAxn($phoneNoA, $time, $phoneNoB = '')
    {
        // 获取配置
        $config = $this->config['axn'];
        $accessKeyId = $config['appId'];
        $accessKeySecret = $config['appKey'];
        $poolKey = $config['poolKey'];
        // 拼接对应数据
        $params = array (
            'PoolKey' => $poolKey, // 号池key
            'PhoneNoA' => $phoneNoA, // AXN关系中的A号码
            'AccessKeyId' => $accessKeyId,
            'NoType' => 'NO_95', // 95中间号, 目前支持2种号码类型, NO_95代表95接入号, NO_170代表170中间号
            'Expiration' => $time, // 绑定关系对应的失效时间
            'SignatureNonce' => uniqid(),
            'Timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
            "SignatureMethod" => "HMAC-SHA1",
            'Action' => 'BindAxn',
            'Version' => '2017-05-25',
            "SignatureVersion" => "1.0",
            "AccessKeyId" => $accessKeyId,
            "Format" => "JSON",
        );

        // AXN中A拨打X的时候转接到的默认的B号码,如果不需要则不设置
        if (!empty($phoneNoB)) {
            $params ['PhoneNoB'] = $phoneNoB;
        }

        // 计算签名并把签名结果加入请求参数
        $params ['Signature'] = $this->computeSignature($params, $accessKeySecret);

        ksort($params);

        // 发送请求
        $url = 'http://dyplsapi.aliyuncs.com/?' . http_build_query($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        curl_close($ch);

        //返回请求结果
        return $result;
    }

        // 计算签名并把签名结果加入请求参数
        $params ['Signature'] = $this->computeSignature($params, $accessKeySecret);

        ksort($params);

        // 发送请求
        $url = 'http://dyplsapi.aliyuncs.com/?' . http_build_query($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        curl_close($ch);

        //返回请求结果
        return $result;
    }

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
}
