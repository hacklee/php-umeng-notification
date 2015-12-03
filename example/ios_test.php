<?php
require '../autoload.php';

use Hacklee\Umeng\UmengNotifyApi;

// 直接读config配置参数new 对象
$api = new UmengNotifyApi('ios');

// 自定义别名发送消息（练手操作）
$api->setIosAlert('ios自定义别名测试-text')
    ->setIosBadge(0)
    ->setIosSound('chime')
    ->setAlias('alias')
    ->setAliasType('aliasType')
    ->sendIOSCustomizedcast();

// 广播，直接传数组方式调用
$api->sendIOSBroadcast([
    'alert' => 'IOS广播测试-alert'
], [
    'ext' => '扩展参数'
]);

// 自定义传配置，new 对象
$customizeApi = new UmengNotifyApi('ios', 'app_key', 'app_secret', true);

$customizeApi->setDeviceTokens('tokens')
    ->setIosAlert('IOS单播测试-alert')
    ->sendIOSUnicast();

// 组播 是利用filter条件进行刷选
$filter = [
    "where" => [
        "and" => [
            [
                "tag" => "test"
            ]
        ]
    ]
];
$customizeApi->setOs('ios')
    ->setAppKey('app_key')
    ->setAppMasterSecret('app_secret')
    ->setFilter($filter)
    ->sendIOSGroupcast([
    'alert' => '安卓组播测试-ticker'
]);

// 文件播
$api->setFileContent("ios_file_content")->sendIOSFilecast([
    'alert' => 'IOS文件播测试-alert',
    'badge' => 0,
    'sound' => 'chime'
]);