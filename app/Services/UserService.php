<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getProfile()
    {
        $user = Auth::user();
        return [
            "user" => $user
        ];
    }

    public function update(array $data){
        $user = Auth::user();

        if(isset($data['name'])){
            $user->name = $data["name"];
        }

        $user->save();

        return [
            "user" => $user
        ];
    }

    public function setName(string $name){
        Auth::user()->update([
            "name" => $name
        ]);

        return [
            "user" => Auth::user()
        ];
    }
}
