<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRegisterRequest;
use App\Services\AuthService;
use App\Trait\Api\ResponseTrait;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ResponseTrait;
    protected  AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }


    public function register(UserRegisterRequest $request)
    {
        $data  = $request->validated();
        $result = $this->authService->createUserAccount($data["email"], $data["password"]);
        return $this->successResponse(
            data: $result,
            message: "User Registered Successfully"
        );
    }

    public function login(UserRegisterRequest $request)
    {
        $result = $this->authService->login($request->email, $request->password);
        return $this->successResponse(
            data: $result,
            message: "User Logged In Successfully"
        );
    }
}
