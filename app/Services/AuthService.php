<?php

namespace App\Services;

use App\Exceptions\Api\ApiException;
use App\Exceptions\Api\UserAlreadyExistsException;
use App\Models\User;
use App\Trait\Api\ResponseTrait;
use Illuminate\Support\Facades\Hash;
use Kreait\Laravel\Firebase\Facades\Firebase;


class AuthService
{
    use ResponseTrait;
    private function userExistsByEmail(string $email)
    {
        $user_exists = User::where("email", $email)->exists();
        if ($user_exists) {
            throw new UserAlreadyExistsException();
        }
    }

    public function checkUserEmailAndPassword(string $email, string $password)
    {
        $user = User::where("email", $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw new ApiException("Password Incorrect");
        }
    }

    public function getuser(string $email)
    {
        return User::where("email", $email)->first();
    }


    public function createUserAccount(string $email, string $password,string $name)
    {
        $this->userExistsByEmail($email);
        $user = new User();
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->name = $name;
        $user->save();
        $token = $this->createUserToken($user);
        return [
            "user" => $user,
            "token" => $token
        ];
    }

    public function checkEmail($email){
        $user = User::where("email",$email)->first();

        
        return [
           "exists" => ($user && $user->provider === "email") ? true : false 
        ];
    }

    private function createUserToken(User $user)
    {
        $token = $user->createToken(time())->plainTextToken;
        return $token;
    }

    public function login(string $email, string $password)
    {
        $user = User::where('email', $email)->first();
        if($user){
            if($user->provider !== "email"){
                throw new \Exception("Account not found. Please sign in with Google.");
            }
        }
        $this->checkUserEmailAndPassword($email, $password);
        $user = $this->getuser($email);
        $token = $this->createUserToken($user);

        return [
            "user" => $user,
            "token" => $token
        ];
    }


    public function googleLogin(array $data){
        $auth = Firebase::auth();
        $verifiedIdToken = $auth->verifyIdToken($data['id_token']);
        $firebaseUid = $verifiedIdToken->claims()->get('sub');
        $email = $verifiedIdToken->claims()->get('email');
        $name = $verifiedIdToken->claims()->get('name');
        $user = User::where('email', $email)->first();


        if($user){
            if($user->provider !== "google"){
                throw new \Exception("Account not found. Please sign in with email and password.");
            }
            $user->update([
                "firebase_uid" => $firebaseUid
            ]);
        }

        else{
            $user = User::create([
                "email" => $email,
                "name" => $name,
                "firebase_uid" => $firebaseUid,
                "provider" => "google"
            ]);
        }


        $token = $this->createUserToken($user);

        return [
            "user" => $user,
            "token" => $token
        ];
    }
}
