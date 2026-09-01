<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ActivityPauseService;
use App\Trait\Api\ResponseTrait;

class ActivityPauseController extends Controller
{
    use ResponseTrait;
    protected ActivityPauseService $activityPauseService;
    public function __construct(ActivityPauseService $activityPauseService){
        $this->activityPauseService = $activityPauseService;
    }

    public function store(Request $request){
        \Log::info($request->all());
        $result = $this->activityPauseService->store($request->all());
        return $this->successResponse(data: $result, message: "Activity Pause Created");
    }
}
