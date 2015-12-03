<?php

namespace Hacklee\Umeng\Ios;

use Hacklee\Umeng\IosNotification;

class IOSGroupcast extends IOSNotification
{

    public function __construct()
    {
        parent::__construct();
        $this->data["type"] = "groupcast";
        $this->data["filter"] = NULL;
    }
}