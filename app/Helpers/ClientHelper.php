<?php

namespace App\Helpers;

class ClientHelper {
    const  CLIENT_TYPE_WINDOWS_PC_WEB = 100;
    const  CLIENT_TYPE_MAC_PC_WEB = 101;
    const  CLIENT_TYPE_PHONE_ANDROID = 200;
    const CLIENT_TYPE_PHONE_IOS = 300;
    const CLIENT_TYPE_UNKNOWN = -1000;

    public static function getClientType()
    {
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        if(stripos($agent, 'windows nt') !==false){
            return self::CLIENT_TYPE_WINDOWS_PC_WEB;
        }elseif(stripos($agent, 'mac os') !==false){
            return self::CLIENT_TYPE_MAC_PC_WEB;
        }elseif(stripos($agent, 'iphone') !==false){
            return self::CLIENT_TYPE_PHONE_IOS;
        }elseif(stripos($agent, 'android') !==false) {
            return self::CLIENT_TYPE_PHONE_ANDROID;
        }else{
            return self::CLIENT_TYPE_UNKNOWN;
        }
    }
    public static function checkIsIos(){
        $clientType = self::getClientType();
        if($clientType==self::CLIENT_TYPE_PHONE_IOS){
            return true;
        }else{
            return false;
        }
    }

    public static function checkIsAandroid(){
        $clientType = self::getClientType();
        if($clientType==self::CLIENT_TYPE_PHONE_ANDROID){
            return true;
        }else{
            return false;
        }
    }

    public static function  checkIsPCWeb(){
        $clientType = self::getClientType();
        if($clientType==self::CLIENT_TYPE_MAC_PC_WEB){
            return true;
        }elseif($clientType==self::CLIENT_TYPE_WINDOWS_PC_WEB){
            return true;
        }else{
            return false;
        }
    }
    public static function checkIsApp(){
        if(self::checkIsPCWeb()==true){
            return false;
        }else{
            return true;
        }
    }

    public static function getClientId($uid,$clientIp)
    {
        $clientTypeId = self::getClientType();
        $clientId = sha1(strtolower($_SERVER['HTTP_USER_AGENT']).$uid.$clientTypeId.$clientIp);
        return $clientId;
    }
}
