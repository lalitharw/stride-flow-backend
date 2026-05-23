<?php

namespace App\Services;

use App\Exceptions\Api\ApiException;
use App\Exceptions\Api\UserAlreadyExistsException;
use App\Models\User;
use App\Trait\Api\ResponseTrait;
use Illuminate\Support\Facades\Hash;


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
            throw new ApiException("Email Or Password Incorrect");
        }
    }

    public function getuser(string $email)
    {
        return User::where("email", $email)->first();
    }


    public function createUserAccount(string $email, string $password)
    {
        $this->userExistsByEmail($email);
        $user = new User();
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->save();
        $token = $this->createUserToken($user);
        return [
            "user" => $user,
            "token" => $token
        ];
    }

    private function createUserToken(User $user)
    {
        $token = $user->createToken(time())->plainTextToken;
        return $token;
    }

    public function login(string $email, string $password)
    {
        $this->checkUserEmailAndPassword($email, $password);
        $user = $this->getuser($email);
        $token = $this->createUserToken($user);

        return [
            "user" => $user,
            "token" => $token
        ];
    }
}
