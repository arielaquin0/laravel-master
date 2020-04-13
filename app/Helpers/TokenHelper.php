<?php

namespace App\Helpers;
use App\Helpers\Base64UrlHepler;
use App\Helpers\UtilHelper;
use App\Helpers\RunEnvHelper;

class TokenHelper
{
    static function str_swap(&$str,$str_a,$str_b)
    {
        $a = $str[$str_a];
        $b = $str[$str_b];
        $str[$str_a] = $b;
        $str[$str_b] = $a;
        return $str;
    }

    public static function newToken($uid)
    {
        $uid = intval($uid);
        $time = time();
        $microtime =  microtime();
        $rand = substr($microtime,2,6);
        $slat = "pigORz";
        if(RunEnvHelper::getRunEnv()==RunEnvHelper::__RUN_NODE2_PROD__)
        {
            $slat = "vipORz";
        }
        elseif(RunEnvHelper::getRunEnv()==RunEnvHelper::__RUN_NODE3_PROD__)
        {
            $slat = "linOrz";
        }
        $secretKey = $slat.UtilHelper::generateWord(6);


        $binStr = pack("Na12NN",$uid,$secretKey,$time,$rand);

        $binStr = self::str_swap($binStr, 0, 10);
        $binStr = self::str_swap($binStr, 1, 16);
        $binStr = self::str_swap($binStr, 2, 7);
        $binStr = self::str_swap($binStr, 5, 21);
        $binStr = self::str_swap($binStr, 3, 19);
        $str = Base64UrlHepler::base64urlEncode2($binStr);
        $str = UtilHelper::generateWord(18).$str.UtilHelper::generateWord(14);
        $str = self::str_swap($str, 57, 24);
        $str = self::str_swap($str, 8, 22);
        $str = self::str_swap($str, 2, 45);
        $str = self::str_swap($str, 13, 28);
        $str = self::str_swap($str, 6, 25);
        $str = self::str_swap($str, 15, 46);

        $res = self::unPackToken($str);

        return $str;
    }

    public static function unPackToken($token)
    {
        if(empty($token))
        {
            return false;
        }
        if(strlen($token)<64)
        {
            return false;
        }
        $token = self::str_swap($token, 46, 15);
        $token = self::str_swap($token, 25, 6);
        $token = self::str_swap($token, 28, 13);
        $token = self::str_swap($token, 45, 2);
        $token = self::str_swap($token, 22, 8);
        $token = self::str_swap($token, 24, 57);
        $token = substr($token,18, 32);
        $binStr = Base64UrlHepler::base64urlDecode2($token);
        $binStr = self::str_swap($binStr, 19, 3);
        $binStr = self::str_swap($binStr, 21, 5);
        $binStr = self::str_swap($binStr, 7, 2);
        $binStr = self::str_swap($binStr, 16, 1);
        $binStr = self::str_swap($binStr, 10, 0);

        $res=@unpack("Nuid/a12secret_key/Ntime/Nrand",$binStr);
        if(empty($res))
        {
            return false;
        }
        if(!isset($res['uid']) OR empty($res['uid']))
        {
            return false;
        }
        if(!isset($res['secret_key']) OR empty($res['secret_key']))
        {
            return false;
        }
        $slat = "pigORz";
        if(RunEnvHelper::getRunEnv()==RunEnvHelper::__RUN_NODE2_PROD__)
        {
            $slat = "vipORz";
        }elseif (RunEnvHelper::getRunEnv()==RunEnvHelper::__RUN_NODE3_PROD__)
        {
            $slat = "linOrz";
        }
        if(substr($res['secret_key'],0,6) !==$slat)
        {
            return false;
        }
        if(!isset($res['time']) OR empty($res['time']) OR strlen($res['time'])!=10)
        {
            return false;
        }
        return $res;
    }

}

