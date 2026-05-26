<?php

namespace App\Services;

use App\Models\ActivityPoint;

class ActivityPointService
{
    public function store(array $data)
    {
        $activity_point = new ActivityPoint();
        $activity_point->activity_id = $data["activity_id"];
        $activity_point->co_ordinate_data = $data["co_ordinates"];
        $activity_point->save();
    }
}
