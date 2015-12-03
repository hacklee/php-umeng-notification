<?php

namespace Hacklee\Umeng\Android;

use Hacklee\Umeng\AndroidNotification;

class AndroidUnicast extends AndroidNotification
{

    public function __construct()
    {
        parent::__construct();
        $this->data["type"] = "unicast";
        $this->data["device_tokens"] = NULL;
    }
}