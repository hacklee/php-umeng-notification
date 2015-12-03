<?php

namespace Hacklee\Umeng;

final class NotificationConfig
{
    const ANDROID = [
        // 应用key
        'appKey' => '',
        // 应用密钥
        'appMasterSecret' => '',
        // 是否产品模式
        'productionMode' => true
    ];
    const IOS = [
        // 应用key
        'appKey' => 'app_key',
        // 应用密钥
        'appMasterSecret' => 'app_secret',
        // 是否产品模式
        'productionMode' => true
    ];
}