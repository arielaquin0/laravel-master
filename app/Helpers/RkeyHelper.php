<?php

namespace App\Helpers;

class RkeyHelper
{
    const RKEY_STRING_USER_SESSION = "users:session:{i}";

    static function  getFormatKey($key,...$args)
    {
        $arg_list = func_get_args();
        array_shift($arg_list);
        $replace_arr = array();
        for($i=0;$i<count($arg_list);$i++){
            $replace_arr[$i] = "{i}";
        }
        return str_replace($replace_arr,$arg_list,$key);
    }
}
