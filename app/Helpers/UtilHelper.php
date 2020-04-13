<?php

namespace App\Helpers;

class  UtilHelper
{
    public static function getMillisecond()
    {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }

    public static function generateWord($length = 8)
    {
        //$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|';
        //$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^*()-_[]<>+=?';
        $word = '';
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $char_len = strlen($chars);
        for ($i = 0; $i < $length; $i++)
        {
            $loop = mt_rand(0, ($char_len - 1));
            $word .= $chars[$loop];
        }

        return $word;
    }

    public static function checkEmail($email)
    {
        if (empty($email))
        {
            return false;
        }
        $perl = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/";
        if (preg_match($perl, $email))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function checkQQ($qq)
    {
        if (empty($qq))
        {
            return false;
        }
        $perl = "#[1-9][0-9]{4,}#";
        if (preg_match_all($perl, $qq))
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    public static function checkCardNumber($cardNumber)
    {
        if (empty($cardNumber))
        {
            return false;
        }
        if (is_numeric($cardNumber) == false)
        {
            return false;
        }
        if (strlen($cardNumber) < 7)
        {
            return false;
        }

        return true;
    }

    public static function str2IntvalArray($str)
    {
        if(empty($str))
        {
            return array();
        }
        return json_decode('[' . $str . ']', true);
    }

}
