<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Trait\Api\ResponseTrait;
use Illuminate\Http\Request;
use App\Services\ActivityService;
use App\Models\Activity;
use App\Http\Requests\Api\ActivityStoreRequest;

class ActivityController extends Controller
{
    use ResponseTrait;
    protected ActivityService $activityService;
    public function __construct(ActivityService $activityService)
    {
        $this->activityService = $activityService;
    }

    public function store(ActivityStoreRequest $request)
    {   
        $data = $request->validated();
        $result = $this->activityService->store($data);
        return $this->successResponse(data: $result, message: "Activity Created Successfully!");
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

    public function getActiveActivityById(int $activity_id){
        $result = $this->activityService->checkIfActivityActive($activity_id);
        return $this->successResponse(data:$result);
    }
}
