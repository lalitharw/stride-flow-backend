<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public function getProfile()
    {
        $user = Auth::user();
        return [
            "user" => $user
        ];
    }
}
