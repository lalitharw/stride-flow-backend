<?php

namespace App\Services;
use App\Models\ActivityPause;


class ActivityPauseService {
    public function store(array $data){
        $activityPause = ActivityPause::firstOrCreate([
            "client_id" => $data['client_id']
        ],
        [
            "activity_id" => $data['activity_id'],
            "client_id" => $data['client_id'],
            "pause_at" => $data['pause_at'],
            "resume_at" => $data['resume_at']
        ]);

        return [
            "activity_pause" => $activityPause
        ];
    }
}