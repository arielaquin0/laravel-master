<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Repositories\ApiAuth\AuthRepository;
use Illuminate\Http\Request;
use App\Traits\JsonTrait;

class LoginController extends ApiBaseController
{
    use JsonTrait;

    use JsonTrait;

    protected $authRepository;
    public function __construct(AuthRepository $authRepository) {
        parent::__construct();
        $this->authRepository = $authRepository;
    }

    function do(Request $request)
    {
        $username = trim($request->input("username",""));
        $password = trim($request->input("password",""));

        if(empty($username)){
            return $this->json(-100,"Username cannot be empty");
        }
        if(empty($password)){
            return $this->json(-101,"Password cannot be blank");
        }

        $loginData = array(
            "username" => strtolower($username),
            "password" => $password,
            "clientIp" => $this->clientIp,
        );

        $res = $this->authRepository->doLogin($loginData);
        if ($res['code'] != 0)
        {
            return $this->json($res['code'], $res['msg'], $res['data']);
        }
        else
        {
            $userInfo = $res['data'];
            $re = array(
                "token" => $userInfo['token'],
                "userId" => $userInfo['id']
            );
            return $this->json(0,"ok", $re);
        }
    }
}
