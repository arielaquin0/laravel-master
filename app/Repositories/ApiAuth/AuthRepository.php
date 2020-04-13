<?php

namespace App\Repositories\ApiAuth;

use App\Helpers\ClientHelper;
use App\Helpers\TokenHelper;
use App\Traits\TokenTrait;
use App\Repositories\BaseRepository;
use App\Helpers\PasswordHelper;
use App\Models\UserModel;

class AuthRepository extends BaseRepository
{
    use TokenTrait;

    protected $sessionRepository;
    protected $cModel;

    const AUTH_SESSION_OK = 0;

    protected $_re = array();
    protected $cookieLifeTime = 2592000; //30days

    protected $sysTimeNow = 0;

    public function __construct(SessionRepository $sessionRepository, UserModel $mastUserModel)
    {
        $this->sessionRepository = $sessionRepository;
        $this->cModel = $mastUserModel;
        $this->sysTimeNow = time();
        $this->_re = array(
            "code" => self::AUTH_SESSION_OK,
            "msg" => "ok",
            "data" => array(),
        );
    }

    public function getAuthTokenName()
    {
        return "api_token";
    }

    public function authByToken($token, $clientIp, $path)
    {
        $re = array(
            "code" => 0,
            "msg" => "ok",
            "data" => array(),
        );
        if (empty($token))
        {
            $re['code'] = -10000;
            $re['msg'] = "No login token, illegal access";

            return $re;
        }
        $result = $this->unPackToken($token);
        if (empty($result))
        {
            $re['code'] = 6001;
            $re['msg'] = "Authentication failed, Please contact the administrator";
            return $re;
        }

        $uid = $result['uid'];
        $userInfo = $this->cModel->getRowById($uid);
        if (empty($userInfo))
        {
            $re['code'] = -70002;
            $re['msg'] = "User does not exist";
            return $re;
        }

        $checkUserRe = $this->checkUserStatus($userInfo);
        if ($checkUserRe['code'] != 0)
        {
            return $checkUserRe;
        }

        $userInfo['token'] = $token;
        $sessionInfo = $this->sessionRepository->getSessionInfoByUid($uid);

        if (empty($sessionInfo))
        {
            $re['code'] = 6001;
            $re['msg'] = "Login credential has expired, Please re-login.";
            return $re;
        }

        if ($this->sessionRepository->checkTokenNormal($token, $uid, $clientIp) == false) {
            //
        }

        $clientId = ClientHelper::getClientId($uid, $clientIp);
        if (isset($sessionInfo[$clientId]))
        {
            $mySessionData = $sessionInfo[$clientId];
            if ($mySessionData['token'] != $token)
            {
                $re['code'] = -80000;
                $re['msg'] = "Illegal Access, Invalid token.";
                return $re;
            }

            $currSessionData = array(
                "last_login_ip" => $clientIp
            );
            $expired_time = $mySessionData['expired_time'];
            if (($this->sysTimeNow - $expired_time) > 0)
            {
                $re['code'] = 6000;
                $re['msg'] = "You haven't logged in for a long time or login token has expired, Please re-login.";
                return $re;
            }

            //Update session
            if ($this->sessionRepository->write($uid, $token, $currSessionData) == false)
            {
                $re['code'] = -80001;
                $re['msg'] = "Login credentials failed to be saved, Please contact the administrator.";
                return $re;
            }
        }
        else
        {
            $currSessionData = array(
                "last_login_ip" => $clientIp,
            );
            if ($this->sessionRepository->write($uid, $token, $currSessionData) == false)
            {
                $re['code'] = -80002;
                $re['msg'] = "Login credentials failed to be saved, Please contact the administrator.";
                return $re;
            }
            $re['code'] = 6666;
            $re['msg'] = "ok";
        }
        $re['data']['userInfo'] = $userInfo;

        return $re;
    }

    public function doLogin($loginData)
    {
        $checkLoginRe = $this->loginCheck($loginData);
        if ($checkLoginRe['code'] < 0) {
            return $checkLoginRe;
        }

        $clientIp = $loginData['clientIp'];
        $userInfo = $checkLoginRe['data'];

        $loginRe = $this->newLogin($userInfo, $clientIp);
        if ($loginRe['code'] == self::AUTH_SESSION_OK)
        {
            // Normal login
        }
        else
        {
            return $loginRe;
        }
        $time = time();
        $upData = array(
            "last_login_time" => $time,
            "last_login_ip" => $clientIp,
        );
        $this->cModel->updateById($userInfo['id'], $upData);
        $this->_re['data'] = $loginRe['data'];

        return $this->_re;
    }

    public function newLogin($userInfo, $clientIp)
    {
        $uid = intval($userInfo['id']);
        $sessionData = array(
            "last_login_ip" => $clientIp,
        );
        $token = TokenHelper::newToken($uid);

        if ($this->sessionRepository->write($uid, $token, $sessionData) !== true)
        {
            $this->_re['code'] = -6000;
            $this->_re['msg'] = "Unable to save session, Please contact the administrator";
            return $this->_re;
        }
        else
        {
            $userInfo['token'] = $token;
            $this->_re['code'] = self::AUTH_SESSION_OK;
            $this->_re['msg'] = "ok";
            $this->_re['data'] = $userInfo;
            return $this->_re;
        }
    }

    public function loginCheck($loginData)
    {
        if (empty($loginData))
        {
            $this->_re['code'] = -100;
            $this->_re['msg'] = "Parameter error";
            return $this->_re;
        }
        $username = $loginData['username'];
        $password = $loginData['password'];
        if (empty($username))
        {
            $this->_re['code'] = -101;
            $this->_re['msg'] = "Username cannot be empty";
            return $this->_re;
        }
        if (empty($password))
        {
            $this->_re['code'] = -102;
            $this->_re['msg'] = "Password cannot be blank";
            return $this->_re;
        }

        $userInfo = $this->cModel->getRow(array("username" => $username));
        if (empty($userInfo))
        {
            $this->_re['code'] = -103;
            $this->_re['msg'] = "User does not exist";
            return $this->_re;
        }

        if (PasswordHelper::checkHashPassword($password, $userInfo['login_password_salt'], $userInfo['login_password']) == false)
        {
            $this->_re['code'] = -104;
            $this->_re['msg'] = "Password is incorrect";
            return $this->_re;
        }

        $checkStatusRe =$this->checkUserStatus($userInfo);
        if ($checkStatusRe['code'] < 0)
        {
            return $checkStatusRe;
        }
        $this->_re['data'] = $userInfo;
        return $this->_re;
    }

    public function checkUserStatus($userInfo)
    {
        if (empty($userInfo))
        {
            $this->_re['code'] = -103;
            $this->_re['msg'] = "User does not exist";
            return $this->_re;
        }
        if ($userInfo['status'] == UserModel::MEMBER_STATUS_INACTIVE)
        {
            $this->_re['code'] = -105;
            $this->_re['msg'] = "This account has been deleted, Please contact the administrator";
            return $this->_re;
        }
        elseif ($userInfo['status'] == UserModel::MEMBER_STATUS_ACTIVE)
        {
            //
        }

        $this->_re['data'] = $userInfo;
        return $this->_re;
    }

    public function logout($uid)
    {
        return $this->sessionRepository->destroyByUid($uid);
    }

}
