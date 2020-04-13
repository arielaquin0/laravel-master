<?php

namespace App\Helpers;

class PasswordHelper {

    public static  function getPasswordSalt()
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        for ($i=0; $i < 4; $i++)
        {
            $password .= $chars[ mt_rand(0, strlen($chars) -1 )];
        }
        return $password;
    }

    public static  function getHashPassword($pwd, $salt)
    {
        $hash = $pwd.$salt;
        return password_hash($hash, PASSWORD_DEFAULT);
    }

    public static function checkHashPassword($pwd, $salt, $hash)
    {
        $pwds = $pwd.$salt;
        return password_verify($pwds,$hash);
    }

    public static function checkPasswordRule($pwd){
        if(empty($pwd)){
            return false;
        }
        if (!preg_match("#(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?!.*\s).{8,}#", $pwd)) {
            return false;
        }
        return true;
    }
}

?>
