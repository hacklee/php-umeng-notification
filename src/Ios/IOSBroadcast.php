<?php

namespace Hacklee\Umeng\Ios;

use Hacklee\Umeng\IosNotification;

class IOSBroadcast extends IOSNotification
{

    public function __construct()
    {
        parent::__construct();
        $this->data["type"] = "broadcast";
    }
}