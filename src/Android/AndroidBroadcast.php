<?php

namespace Hacklee\Umeng\Android;

use Hacklee\Umeng\AndroidNotification;

class AndroidBroadcast extends AndroidNotification
{

    public function __construct()
    {
        parent::__construct();
        $this->data["type"] = "broadcast";
    }
}