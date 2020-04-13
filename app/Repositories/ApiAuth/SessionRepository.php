<?php

namespace App\Repositories\ApiAuth;

use App\Repositories\BaseRepository;
use App\Helpers\ClientHelper;
use App\Helpers\RkeyHelper;
use Illuminate\Support\Facades\Redis;

class SessionRepository extends BaseRepository
{
    protected $lifeTime = 7776000; // 90days
    protected $sysTimeNow=0;

    public function __construct()
    {
        $this->sysTimeNow = time();
    }

    public function getSessionLifeTime()
    {
        return $this->lifeTime;
    }

    public function write($uid, $token, $currSessionData)
    {
        $newExpiredTime = $this->sysTimeNow + $this->lifeTime;
        $clientTypeId = ClientHelper::getClientType();
        $clientId = ClientHelper::getClientId($uid,$currSessionData['last_login_ip']);
        $ip = $currSessionData['last_login_ip'];
        if($oldSessionData = $this->checkClientSession($uid,$clientId))
        {
            $last_login_time = $oldSessionData['last_login_time'];
        }
        else
        {
            $last_login_time = $this->sysTimeNow;
        }
        $newSessionData = array(
            "uid" => $uid,
            "client_id" => $clientId,
            "client_type_id" => $clientTypeId,
            "token" => $token,
            "last_login_time" => $last_login_time,
            "last_active_time" => $this->sysTimeNow,
            "expired_time" => $newExpiredTime,
            "last_active_ip" => $ip
        );
        $saveData[$clientId] = $newSessionData;
        $cacheKey = RkeyHelper::getFormatKey(RkeyHelper::RKEY_STRING_USER_SESSION,$uid);
        if(Redis::set($cacheKey,json_encode($saveData),'EX',$this->lifeTime) == false)
        {
            return false;
        }
        return true;
    }

    public function checkClientSession($clientId,$uid)
    {
        $sessionData = $this->getSessionInfoByUid($uid);
        if(empty($sessionData))
        {
            return false;
        }
        if(isset($sessionData[$clientId]))
        {
            return $sessionData;
        }
        return false;
    }

    public function getSessionInfoByUid($uid)
    {
        if (empty($uid))
        {
            return false;
        }
        $cacheKey = RkeyHelper::getFormatKey(RkeyHelper::RKEY_STRING_USER_SESSION,$uid);
        $sessionJson = Redis::get($cacheKey);
        if (empty($sessionJson))
        {
            return false;
        }
        $sessionData = json_decode($sessionJson,true);
        if(empty($sessionData))
        {
            return false;
        }
        return $sessionData;
    }

    public function checkTokenNormal($token,$uid,$clientIp)
    {
        $clientId = ClientHelper::getClientId($uid,$clientIp);
        $sessionData=$this->getSessionInfoByUid($uid);

        if(empty($sessionData))
        {
            return false;
        }
        else
        {
            if(isset($sessionData[$clientId]))
            {
                return $sessionData;
            }
            else
            {
                return false;
            }
        }

    }

    public function destroyByUid($uid)
    {
        $cacheKey = RkeyHelper::getFormatKey(RkeyHelper::RKEY_STRING_USER_SESSION, $uid);
        Redis::del($cacheKey);
        return true;
    }
}
