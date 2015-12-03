<?php

namespace Hacklee\Umeng\Ios;

use Hacklee\Umeng\IosNotification;

class IOSUnicast extends IOSNotification
{

    public function __construct()
    {
        parent::__construct();
        $this->data["type"] = "unicast";
        $this->data["device_tokens"] = NULL;
    }
}