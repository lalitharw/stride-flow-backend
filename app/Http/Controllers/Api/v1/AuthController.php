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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\GoogleLoginMail;
use Illuminate\Support\Facades\Mail;

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

    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|string|email"
        ]);

        $result = $this->authService->createUserAccount($request->email);

        
        return $this->successResponse(
            data: $result,
            message: "User Logged In Successfully"
        );
    }

    public function setPassword(Request $request){
        $request->validate([
            "password" => "required|string"
        ]);

        $result = $this->authService->setPassword($request->password);

        return $this->successResponse(message:"Password Set Successfully");
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

        if($user->firebase_uid && !$user->password){
                Mail::to($user->email)->send(new GoogleLoginMail($user->email));
                 return $this->successResponse(
                message: "If an account exists with this email, a password reset link has been sent3333."
            );
        }

        

       

        $status = Password::sendResetLink(
            $request->only("email")
        );

        \Log::info($status);

        if($status === Password::RESET_LINK_SENT || $status === 'passwords.user'){
            return $this->successResponse(
                message: "If an account exists with this email, a password reset link has been sent."
            );
        }

         throw new \Exception("Unable to process the password reset request.");
    
    }



    public function resetPassword(Request $request){
        $request->validate([
            "token" => "required",
            "email" => "required|email|string",
            "password" => "required|min:8"
        ]);


        $status = Password::reset(
            $request->only("email","password","token"),
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
