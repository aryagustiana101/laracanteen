<?php

namespace App\Utilities;

use App\Models\Status;

class Constant
{

    public static $incomingStatus = "asd";

    public function __construct()
    {
        $this->incomingStatus = Status::where('position', 'first')->where('result', 'success-user')->first();
    }
}
