<?php

namespace App\Services;

use App\Models\ActivityPoint;
use Illuminate\Support\Facades\Redis;

class ActivityPointService
{

    public function store(array $data)
    {
        $key = "activity:{$data['activity_id']}:points";

        Redis::rPush($key, json_encode([
            "co_ordinates" => $data['co_ordinates'],
            "sequence" => $data['sequence']
        ]));

        return [
            "success" => true
        ];
    }


    public function blukStore(object $allPoints, int $activity_id)
    {
        ActivityPoint::insert(
            $allPoints->map(fn($point, $index) => [
                "activity_id" => $activity_id,
                "order" => $index,
                "latitude" => $point[0],
                "longitude" => $point[1],
                "created_at" => now(),
                "updated_at" => now()
            ])->toArray()
        );
    }
}
