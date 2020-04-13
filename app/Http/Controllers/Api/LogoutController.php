<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Repositories\ApiAuth\AuthRepository;
use App\Traits\JsonTrait;

class LogoutController extends ApiBaseController
{
    use JsonTrait;
    protected $authRepository;
    public function __construct(AuthRepository $authRepository)
    {
        parent::__construct();
        $this->authRepository = $authRepository;
    }

    function do()
    {
        $this->authRepository->logout($this->uid);
        return $this->json(0,"ok");
    }
}
