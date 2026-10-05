<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRegisterRequest;
use App\Http\Requests\Api\UserLoginRequest;
use App\Http\Requests\Api\UserGoogleLoginRequest;
use App\Services\AuthService;
use App\Trait\Api\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;

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
        $result = $this->authService->createUserAccount($data["email"], $data["password"],$data['name']);
        return $this->successResponse(
            data: $result,
            message: "User Registered Successfully"
        );
    }

    public function login(UserLoginRequest $request)
    {
        $data = $request->validated();
        $result = $this->authService->login($data['email'], $data['password']);
        return $this->successResponse(
            data: $result,
            message: "User Logged In Successfully"
        );
    }

    public function googleLogin(UserGoogleLoginRequest $request){
        $data = $request->validated();
        $result = $this->authService->googleLogin($data);
        return $this->successResponse(
            data: $result,
            message: "User Google Logged In Successfully"
        );
    }

    public function checkEmail(Request $request){
        $request->validate([
            "email" => "required|email|string"
        ]);

        $result = $this->authService->checkEmail($request->email);
        return $this->successResponse(
            data: $result,
            message:"User Email Checked"
        );
    }

      public function forgotPassword(Request $request){
        $request->validate([
            "email" => "required|email"
        ]);

        $user = User::where("email",$request->email)->first();

       

        $status = Password::sendResetLink(
            $request->only("email")
        );

        \Log::info($status);

        if($status === Password::RESET_LINK_SENT){
            $this->successResponse(
                message: "Password reset link sent to your email"
            );
        }

         throw new \Exception("Unable to send password reset link.");
    
    }

    public function resetPassword(Request $request){
        $request->validate([
            "token" => "required",
            "email" => "required|email|string",
            "password" => "required|min:8,confirmed"
        ]);

        $status = Password::reset(
            $request->only("email","password","password_confirmation","token"),
            function($user,$password){
                $user->forceFill([
                    "password" => Hash::make($password),
                    "remember_token" => Str::random(60)
                ])->save();
            }
        );

        if($status === Password::PASSWORD_RESET){
            return $this->successResponse(
                message: "Password has been reset successfully"
            );
        }

        throw new \Exception("Invalid or Expired reset token.");
    }
}
