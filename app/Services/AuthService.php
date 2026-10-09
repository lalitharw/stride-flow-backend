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
            throw new \Exception("Email Or Password Incorrect.");
        }
    }

    public function getuser(string $email)
    {
        return User::where("email", $email)->first();
    }

    public function setPassword(string $password){
        auth()->user()->update([
            "password" => Hash::make($password)
        ]);
    }


    public function createUserAccount(string $email, string $password)
    {
        // $this->userExistsByEmail($email);
        // $user = new User();
        // $user->email = $email;
        // $user->password = Hash::make($password);
        // $user->name = $name;
        // $user->save();
        // $token = $this->createUserToken($user);
        // return [
        //     "user" => $user,
        //     "token" => $token
        // ];
        $type = "old";
        $user = User::where("email",$email)->first();
        if(!$user){
            $user = new User();
            $user->email = $email;
            
            $user->save();
            $type = "new";
        }

        else{

        }
        
        $token = $this->createUserToken($user);
        return [
            "user" => $user,
            "token" => $token,
            "type" => $type
        ];
    }

    public function checkEmail($email){
        $user = User::where("email",$email)->first();
        return [
           "exists" => isset($user) ? true : false,
           "has_password" => isset($user->password) ? true: false,
           "has_google" => isset($user->firebase_uid) ? true: false 
        ];
    }

    private function createUserToken(User $user)
    {
        $token = $user->createToken(time())->plainTextToken;
        return $token;
    }

    // public function login(string $email, string $password)
    // {
    //     $user = User::where('email', $email)->first();
    //     if($user){
    //         if($user->provider !== "email"){
    //             throw new \Exception("Account not found. Please sign in with Google.");
    //         }
    //     }
    //     $this->checkUserEmailAndPassword($email, $password);
    //     $user = $this->getuser($email);
    //     $token = $this->createUserToken($user);

    //     return [
    //         "user" => $user,
    //         "token" => $token
    //     ];
    // }

    

    public function login(string $email, string $password){
        $user = User::where("email",$email)->first();
        if(!$user){
            $user = new User();
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->save();

            $token = $this->createUserToken($user);
            return [
                "token" => $token,
                "user" => $user     
            ];
        }

        if(!Hash::check($password,$user->password)){
            throw new \Exception("Invalid Credentials.");
        }
        
        $token = $this->createUserToken($user);
        return [
            "token" => $token,
            "user" => $user
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
