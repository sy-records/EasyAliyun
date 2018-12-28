<?php
/**
 * author: ShenYan.
 * Email：52o@qq52o.cn
 * CreatedTime: 2018/12/28 11:22
 */

namespace SyRecords;


class Aliyun extends Common
{
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * 发送短信
     * @param        $mobile [手机号]
     * @param string $code [验证码]
     * @param int    $isMarketing [是否营销短信]
     *
     * @return mixed
     */
    public function sendSms($mobile, $code = '', $isMarketing = 0)
    {
        // 获取配置信息
        $config = $this->config;
        $accessKeyId = $config['appId']; // 阿里云 AccessKeyID
        $accessKeySecret = $config['appKey']; // 阿里云 AccessKeySecret
        $templateCode = $config['tplId']; // 短信模板ID
        $signName = $config['signName']; // 短信签名
        $url = $config['url']; // 请求域名

        $params = array (
            'SignName' => $signName,
            'Format' => 'JSON',
            'Version' => '2017-05-25',
            'AccessKeyId' => $accessKeyId,
            'SignatureVersion' => '1.0',
            'SignatureMethod' => 'HMAC-SHA1',
            'SignatureNonce' => uniqid(),
            'Timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
            'Action' => 'SendSms',
            'TemplateCode' => $templateCode,
            'PhoneNumbers' => $mobile,
            'TemplateParam' => '{"code":"' . $code . '"}'
        );

        if ($isMarketing) {
            // 营销短信无需 TemplateParam 参数
            unset($params['TemplateParam']);
        }

        // 计算签名并把签名结果加入请求参数
        $params ['Signature'] = $this->computeSignature($params, $accessKeySecret);

        return $this->AliCurl($url, $params);
    }

    /**
     * AXN绑定接口
     * @param        $phoneNoA AXN中的A号码
     * @param        $time 绑定关系的过期时间
     * @param string $phoneNoB AXN中的默认的B号码
     * @param string $noType 默认95接入号, 目前支持2种号码类型, NO_95代表95接入号, NO_170代表170中间号
     *
     * @return mixed
     */
    protected function putAxnBind($phoneNoA, $time, $phoneNoB = '', $noType = 'NO_95')
    {
        // 获取配置
        $config = $this->config;
        $accessKeyId = $config['appId']; // 阿里云 AccessKeyID
        $accessKeySecret = $config['appKey']; // 阿里云 AccessKeySecret
        $poolKey = $config['poolKey']; // 号池Key
        $url = $config['url']; // 请求域名

        // 拼接对应数据
        $params = array (
            'PoolKey' => $poolKey, // 号池key
            'PhoneNoA' => $phoneNoA, // AXN关系中的A号码
            'AccessKeyId' => $accessKeyId,
            'NoType' => $noType,
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
            $params['PhoneNoB'] = $phoneNoB;
        }

        // 计算签名并把签名结果加入请求参数
        $params ['Signature'] = $this->computeSignature($params, $accessKeySecret);

        return $this->AliCurl($url, $params);
    }
}