<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Trait\Api\ResponseTrait;
use Illuminate\Http\Request;
use App\Services\ActivityService;
use App\Models\Activity;

class ActivityController extends Controller
{
    use ResponseTrait;
    protected ActivityService $activityService;
    public function __construct(ActivityService $activityService)
    {
        $this->activityService = $activityService;
    }

    public function store(Request $request)
    {
        $this->activityService->store($request->all());
        return $this->successResponse(message: "Activity Created Successfully!");
    }

    public function get()
    {
        $result = $this->activityService->get();
        return $this->successResponse(data: $result);
    }

    public function getById(int $activity_id)
    {
        $result = $this->activityService->getById($activity_id);
        return $this->successResponse(data: $result);
    }

    public function markAsComplete(int $activity_id)
    {
        $this->activityService->markAsComplete($activity_id);
        return $this->successResponse(message: "Activity Marked As Completed Successfully!");
    }

    public function delete(int $activity_id)
    {
        $this->activityService->delete($activity_id);
        return $this->successResponse(message: "Activity Deleted Successfully!");
    }
}
