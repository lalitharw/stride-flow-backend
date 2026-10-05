<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Trait\Api\ResponseTrait;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    use ResponseTrait;
    protected ActivityPauseService $activityPauseService;
    public function __construct(ActivityPauseService $activityPauseService){
        $this->activityPauseService = $activityPauseService;
    }

  
}
