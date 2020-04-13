<?php

namespace App\Helpers;

class Base64UrlHepler
{
    public static function  base64urlEncode($input)
    {
        return strtr(base64_encode($input), '+/=', '._-');
    }

    public static function base64urlDecode($input)
    {
        return base64_decode(strtr($input, '._-', '+/='));
    }

    public static function base64urlDecode2($data)
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) + (strlen($data) % 4), '=', STR_PAD_RIGHT));
    }

    public static function base64urlEncode2($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

}
