<?php

namespace App\Traits;

use App\Helpers\TokenHelper;
use Illuminate\Support\Facades\Redis;

trait TokenTrait
{

    public function getNewToken($uid)
    {
        return TokenHelper::newToken($uid);
    }

    public function unPackToken($token)
    {
        return TokenHelper::unPackToken($token);
    }

}
