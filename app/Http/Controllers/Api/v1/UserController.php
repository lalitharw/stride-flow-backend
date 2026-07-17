<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Trait\Api\ResponseTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Api\UpdateProfileRequest;

class UserController extends Controller
{
    use ResponseTrait;
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function get()
    {
        $result = $this->userService->getProfile();
        return $this->successResponse($result);
    }

    public function update(UpdateProfileRequest $request){
        
        $data = $request->validated();
        \Log::info($data);
        $result = $this->userService->update($data);
        return $this->successResponse($result);
    }
}
