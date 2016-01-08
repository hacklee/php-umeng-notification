<?php

namespace Hacklee\Umeng;

use Exception;
use Hacklee\Umeng\Android\AndroidBroadcast;
use Hacklee\Umeng\Android\AndroidFilecast;
use Hacklee\Umeng\Android\AndroidGroupcast;
use Hacklee\Umeng\Android\AndroidUnicast;
use Hacklee\Umeng\Android\AndroidCustomizedcast;
use Hacklee\Umeng\Ios\IOSBroadcast;
use Hacklee\Umeng\Ios\IOSFilecast;
use Hacklee\Umeng\Ios\IOSGroupcast;
use Hacklee\Umeng\Ios\IOSUnicast;
use Hacklee\Umeng\Ios\IOSCustomizedcast;

/**
 * 友盟消息推送
 *
 * @author liguoyi
 */
class UmengNotifyApi
{
    private $nameSpace = "Hacklee\\Umeng\\";
    private $appkey;
    private $appMasterSecret;
    private $timestamp;
    private $validation_token;
    private $productionMode;
    private $os;
    private $params = [];
    private $ext = [];
    private $fileContent = null;

    public function __construct($os = 'android', $key = '', $secret = '', $productionMode = null)
    {
        $this->checkOs($os);
        $this->os = $os;
        $conf = $os === 'android' ? NotificationConfig::ANDROID : NotificationConfig::IOS;
        $this->appkey = $key ?  : $conf['appKey'];
        $this->appMasterSecret = $secret ?  : $conf['appMasterSecret'];
        $this->productionMode = $productionMode ?  : $conf['productionMode'];
        $this->timestamp = strval(time());
    }

    private function checkOs($os)
    {
        if (! in_array($os, [
            'android',
            'ios'
        ])) {
            throw new Exception('系统类型错误，os 必须为android或ios');
        }
    }

    /**
     * 设置设备系统类型
     *
     * @param unknown $os
     */
    public function setOs($os)
    {
        $this->checkOs($os);
        $this->os = $os;
        return $this;
    }

    public function setAppKey($appKey)
    {
        $this->appkey = $appKey;
        return $this;
    }

    public function setAppMasterSecret($secret)
    {
        $this->appMasterSecret = $secret;
        return $this;
    }

    /**
     *
     * @param string $ticker 通知栏提示文字
     * @return \Hacklee\Umeng\UmengNotifyApi
     */
    public function setAndroidTicker($ticker)
    {
        $this->params['ticker'] = $ticker;
        return $this;
    }

    /**
     *
     * @param string $title 通知标题
     * @return \Hacklee\Umeng\UmengNotifyApi
     */
    public function setAndroidTitle($title)
    {
        $this->params['title'] = $title;
        return $this;
    }

    /**
     *
     * @param string $text 通知文字描述
     * @return \Hacklee\Umeng\UmengNotifyApi
     */
    public function setAndroidText($text)
    {
        $this->params['text'] = $text;
        return $this;
    }

    /**
     *
     * @param string $afterOpen "go_app": 打开应用 "go_url": 跳转到URL "go_activity": 打开特定的activity "go_custom": 用户自定义内容。
     * @return \Hacklee\Umeng\UmengNotifyApi
     */
    public function setAndroidAfterOpen($afterOpen = 'go_app')
    {
        $this->params['after_open'] = $afterOpen;
        return $this;
    }

    /**
     *
     * @param string $tokens 设备唯一标识 当type=unicast时,必填, 表示指定的单个设备 当type=listcast时,必填,要求不超过500个, 多个device_token以英文逗号间隔
     * @return \Hacklee\Umeng\UmengNotifyApi
     */
    public function setDeviceTokens($tokens)
    {
        $this->params['device_tokens'] = $tokens;
        return $this;
    }

    /**
     *
     * @param array $filter = array( "where" => array( "and" => array( array( "tag" => "iostest" ) ) ) );
     */
    public function setFilter(array $filter)
    {
        $this->params['filter'] = $filter;
        return $this;
    }

    /**
     *
     * @param string $alias 要求不超过50个alias,多个alias以英文逗号间隔。
     * @return \Hacklee\Umeng\UmengNotifyApi
     */
    public function setAlias($alias)
    {
        $this->params['alias'] = $alias;
        return $this;
    }

    /**
     *
     * @param string $aliasType alias_type可由开发者自定义,开发者在SDK中 调用setAlias(alias, alias_type)时所设置的alias_type
     * @return \Hacklee\Umeng\UmengNotifyApi
     */
    public function setAliasType($aliasType)
    {
        $this->params['alias_type'] = $aliasType;
        return $this;
    }

    /**
     *
     * @param string $alert 通知内容 ios必填字段
     * @return \Hacklee\Umeng\UmengNotifyApi
     */
    public function setIosAlert($alert)
    {
        $this->params['alert'] = $alert;
        return $this;
    }

    /**
     *
     * @param unknown $badge
     * @return \Hacklee\Umeng\UmengNotifyApi
     */
    public function setIosBadge($badge = 0)
    {
        $this->params['badge'] = $badge;
        return $this;
    }

    /**
     * 如果该字段为空，采用SDK默认的声音, 即res/raw/下的 umeng_push_notification_default_sound声音文件 如果SDK默认声音文件不存在， 则使用系统默认的Notification提示音。
     *
     * @param string $sound
     * @return \Hacklee\Umeng\UmengNotifyApi
     */
    public function setIosSound($sound = 'chime')
    {
        $this->params['sound'] = $sound;
        return $this;
    }

    /**
     *
     * @param string $content 文件内容,多个device_token/alias请用回车符"\n"分隔。
     * @return \Hacklee\Umeng\UmengNotifyApi
     */
    public function setFileContent($content)
    {
        $this->fileContent = $content;
        return $this;
    }

    /**
     * 可选 用户自定义key-value。只对"通知" (display_type=notification)生效。 可以配合通知到达后,打开App,打开URL,打开Activity使用。
     *
     * @param array $ext
     */
    public function setExtraField(array $ext)
    {
        $this->ext = $ext;
        return $this;
    }

    /**
     * 安卓广播
     *
     * @param array $params ['ticker','title','text','after_open']
     * @param unknown $ext 扩展字段
     */
    public function sendAndroidBroadcast(array $params = [], array $ext = [])
    {
        $this->sendCast('AndroidBroadcast', $params, $ext);
    }

    /**
     * 安卓单播
     *
     * @param array $params ['device_tokens','ticker','title','text','after_open']
     * @param unknown $ext 扩展字段
     */
    public function sendAndroidUnicast(array $params = [], array $ext = [])
    {
        $this->sendCast('AndroidUnicast', $params, $ext);
    }

    /**
     * 安卓文件播
     *
     * @param array $params ['ticker','title','text','after_open']
     * @param string $content
     */
    public function sendAndroidFilecast(array $params = [], $content = null)
    {
        $this->sendCast('AndroidFilecast', $params, [], $content);
    }

    /**
     * 安卓组播
     *
     * @param array $params ['ticker','title','text','after_open','filter']
     */
    public function sendAndroidGroupcast(array $params = [])
    {
        $this->sendCast('AndroidGroupcast', $params);
    }

    /**
     * 安卓自定义alias进行推送
     *
     * @param array $params ['alias','alias_type','ticker','title','text','after_open','filter']
     */
    public function sendAndroidCustomizedcast(array $params = [])
    {
        $this->sendCast('AndroidCustomizedcast', $params);
    }

    /**
     * ios广播
     *
     * @param array $params ['alert','badge','sound']
     * @param unknown $ext 扩展字段
     */
    public function sendIOSBroadcast(array $params = [], array $ext = [])
    {
        $this->sendCast('IOSBroadcast', $params, $ext);
    }

    /**
     * ios单播
     *
     * @param array $params ['device_tokens','alert','badge','sound']
     * @param unknown $ext 扩展字段
     */
    public function sendIOSUnicast(array $params = [], array $ext = [])
    {
        $this->sendCast('IOSUnicast', $params, $ext);
    }

    /**
     * ios文件播
     *
     * @param array $params ['alert','badge','sound']
     * @param string $content
     */
    public function sendIOSFilecast(array $params = [], $content = null)
    {
        $this->sendCast('IOSFilecast', $params, [], $content);
    }

    /**
     * ios组播
     *
     * @param array $params ['filter','alert','badge','sound']
     */
    public function sendIOSGroupcast(array $params = [])
    {
        $this->sendCast('IOSGroupcast', $params);
    }

    /**
     * ios利用自定义alias进行推送
     *
     * @param array $params ['alias','alias_type','alert','badge','sound']
     */
    public function sendIOSCustomizedcast(array $params = [])
    {
        $this->sendCast('IOSCustomizedcast', $params);
    }

    /**
     * 创建cast对象，并初始化基础参数
     *
     * @param unknown $className
     */
    private function getCastObj($className)
    {
        $className = $this->nameSpace . ucfirst($this->os) . "\\{$className}";
        $obj = new $className();
        $obj->setAppMasterSecret($this->appMasterSecret);
        $obj->setPredefinedKeyValue("appkey", $this->appkey);
        $obj->setPredefinedKeyValue("timestamp", $this->timestamp);
        
        $obj->setPredefinedKeyValue("production_mode", $this->productionMode);
        return $obj;
    }

    /**
     * 向友盟服务器发送请求
     *
     * @param unknown $className 消息对象
     * @param array $params 消息参数
     * @param array $ext 扩展字段
     * @param string $file 文件内容
     */
    private function sendCast($className, array $params = [], array $ext = [], $file = null)
    {
        try {
            $brocast = $this->getCastObj($className);
            $params = array_merge($this->params, $params);
            foreach ($params as $key => $val) {
                $brocast->setPredefinedKeyValue($key, $val);
            }
            $ext = array_merge($this->ext, $ext);
            // [optional]Set extra fields
            if (count($ext)) {
                $func = $this->os == 'android' ? 'setExtraField' : 'setCustomizedField';
                foreach ($ext as $key => $val) {
                    $brocast->{$func}($key, $val);
                }
            }
            $file || ($file = $this->fileContent);
            $file && $brocast->uploadContents($file);
            $brocast->send();
        } catch (Exception $e) {
        }
    }
}
