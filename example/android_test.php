<?php
require '../autoload.php';

use Hacklee\Umeng\UmengNotifyApi;

// 直接读config配置参数new 对象
$api = new UmengNotifyApi();

// 自定义别名发送消息（练手操作）
$api->setAndroidAfterOpen()
    ->setAndroidText('安卓自定义别名测试-text')
    ->setAndroidTitle('安卓自定义别名测试-title')
    ->setAndroidTicker('安卓自定义别名测试-ticker')
    ->setAlias('alias')
    ->setAliasType('aliasType')
    ->sendAndroidCustomizedcast();

// 广播，直接传数组方式调用
$api->sendAndroidBroadcast([
    'ticker' => '安卓广播测试-ticker',
    'title' => '安卓广播测试-title',
    'text' => '安卓广播测试-text',
    'after_open' => 'go_app'
], [
    'ext' => '扩展参数'
]);

// 自定义传配置，new 对象
$customizeApi = new UmengNotifyApi('android', 'app_key', 'app_secret', true);

$customizeApi->setAndroidAfterOpen()
    ->setAndroidText('安卓单播测试-text')
    ->setAndroidTitle('安卓单播测试-title')
    ->setAndroidTicker('安卓单播测试-ticker')
    ->setDeviceTokens('tokens')
    ->sendAndroidUnicast();

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
$customizeApi->setOs('android')
    ->setAppKey('app_key')
    ->setAppMasterSecret('app_secret')
    ->sendAndroidGroupcast([
    'ticker' => '安卓组播测试-ticker',
    'title' => '安卓组播测试-title',
    'text' => '安卓组播测试-text',
    'after_open' => 'go_app',
    'filter' => $filter
]);

// 文件播
$api->setFileContent("test\ntest")
    ->setAndroidAfterOpen()
    ->setAndroidText('安卓文件播测试-text')
    ->setAndroidTitle('安卓文件播测试-title')
    ->setAndroidTicker('安卓文件播测试-ticker')
    ->sendAndroidFilecast();