# EasyAliyun

:cloud: 阿里云一些常用的服务：短信发送、虚拟号绑定等

## 安装

```php
composer require sy-records/easy-aliyun -vvv
```

## 配置

可以写在同一个配置文件去获取

```php
# 短信
$config = [
    'appId'  => '', // 阿里云 AccessKeyID
    'appKey' => '', // 阿里云 AccessKeySecret
    'tplId' => 'SMS_888888888', // 短信模板ID
    'signName' => '', // 短信签名
];
# Axnbind配置
$config = [
    'appId'  => '', // 阿里云 AccessKeyID
    'appKey' => '', // 阿里云 AccessKeySecret
    'poolKey' => '', // 号池Key
];
```

## 使用

```php
<?php
use SyRecords\Aliyun;

# $config 配置信息是数组
$aliyun = new Aliyun($config);

# 手机号；验证码；是否是营销短信（营销短信无需验证码）
$aliyun->sendSms($mobile, $code = '', $isMarketing = 0);
# AXN中的A号码；绑定关系过期时间；AXN中的默认的B号码；默认95接入号，目前支持2种号码类型
$aliyun->putAxnBind($phoneNoA, $time, $phoneNoB = '', $noType = 'NO_95');
```

## 文档

* [短信发送API](https://help.aliyun.com/document_detail/55451.html?spm=a2c4g.11186623.6.573.72e13a7611EBf4)
* [AXN绑定接口](https://help.aliyun.com/document_detail/59655.html?spm=a2c4g.11186623.6.586.39b9504fZJGS5s)

## 协议

GPL-3.0


