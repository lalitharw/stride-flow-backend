<?php

namespace App\Services;

use App\Models\ActivityPoint;
use Illuminate\Support\Facades\Redis;

class ActivityPointService
{

    public function store(array $data)
    {
        $now = now();
        $points = [];
        $activity_id = $data['activity_id'];
        
        foreach($data["points"] as $point){
            if(ActivityPoint::where("client_id",$point['client_id'])->exists()){
                continue;
            }
            $points[] = [
                "activity_id" => $activity_id,
                "latitude" => $point['latitude'],
                "longitude" => $point['longitude'],
                "client_id" => $point['client_id'],
                "speed" => $point['speed'],
                "altitude" => $point['altitude'],
                "created_at" => $now,
                "updated_at" => $now
            ];
        }
        ActivityPoint::insert($points);
      
        return [
            "success" => true
        ];
    }


    // public function blukStore(object $allPoints, int $activity_id)
    // {
    //     ActivityPoint::insert(
    //         $allPoints->map(fn($point, $index) => [
    //             "activity_id" => $activity_id,
    //             "order" => $index,
    //             "latitude" => $point[0],
    //             "longitude" => $point[1],
    //             "created_at" => now(),
    //             "updated_at" => now()
    //         ])->toArray()
    //     );
    // }
}
