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
        $result = $this->userService->update($data);
        return $this->successResponse($result);
    }

    public function setName(Request $request){
        $request->validate([
            "name" => "required"
        ]);

        \Log::info($request->all());

        $result = $this->userService->setName($request->name);
        return $this->successResponse(data: $result, message:"Name Set Successfully!");
    }
}
