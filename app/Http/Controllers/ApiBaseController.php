<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Helpers\ClientIpHelper;
use App\Traits\JsonTrait;
use Request;
use App;

class ApiBaseController extends BaseController
{
    use JsonTrait;
    protected $clientIp = 0;
    protected $uid = 0;
    protected $userInfo = array();
    protected $path = "";
    protected $noAuthList = array(
        "api/login",
    );
    protected $authRepository;

    public function __construct()
    {
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400'); // cache for 1 day
        }

        $this->authRepository = App::make("App\Repositories\ApiAuth\AuthRepository");
        $path = Request::path();
        $this->clientIp = ip2long(ClientIpHelper::getClientIp());
        if ($this->checkIsNeedAuth($path) == true) {
            $token = Request::header("token", "");
            if (empty($token)) {
                return $this->json(-200, "Unauthorized access");
            }
            $this->checkAuth($token);
        }
    }

    protected function checkAuth($token)
    {
        $result = $this->authRepository->authByToken($token, $this->clientIp, $this->path);
        if ($result['code'] >= 6000 AND $result['code'] <= 7000)
        {
            return $this->json($result['code'], $result['msg']);
        }
        elseif ($result['code'] != 0)
        {
            if ($this->path == "logout")
            {
                return $this->json(6003, "Logout token verification does not pass, You will need to re-login.");
            }
            return $this->json($result['code'], $result['msg']);
        }
        $userInfo = $result['data']['userInfo'];
        $this->uid = $userInfo['id'];
        $this->userInfo = $userInfo;
        return true;
    }

    protected function checkIsNeedAuth($path)
    {
        if (in_array($path, $this->noAuthList))
        {
            return false;
        }
        return true;
    }

}
