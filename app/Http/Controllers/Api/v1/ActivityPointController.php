<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ActivityPointStoreRequest;
use App\Services\ActivityPointService;
use App\Trait\Api\ResponseTrait;
use Illuminate\Http\Request;

class ActivityPointController extends Controller
{
    use ResponseTrait;
    protected ActivityPointService $activityPointService;

    public function __construct(ActivityPointService $activityPointService)
    {
        $this->activityPointService = $activityPointService;
    }

    public function store(ActivityPointStoreRequest $request)
    {
        $this->activityPointService->store($request->validated());
        return $this->successResponse(message: "Activity Point Saved Successfully");
    }
}
