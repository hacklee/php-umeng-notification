<?php

namespace Hacklee\Umeng\Android;

use Hacklee\Umeng\AndroidNotification;

class AndroidGroupcast extends AndroidNotification
{

    public function __construct()
    {
        parent::__construct();
        $this->data["type"] = "groupcast";
        $this->data["filter"] = NULL;
    }
}