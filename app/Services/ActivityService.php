<?php

namespace App\Services;

use App\Exceptions\Api\CheckIfActivityIsInComplete;
use App\Models\Activity;

class ActivityService
{

    public function store(array $data)
    {
        $this->checkIfActivityIsIncomplete();
        auth()->user()->activities()->create([
            "type" => "walk",
            "start_time" => now()
        ]);
    }


    public function get()
    {
        $activities = auth()->user()->activities()->get();
        return [
            "activity" => $activities
        ];
    }

    public function getById(int $activity_id)
    {
        $activity = auth()->user()->activities()->findOrFail($activity_id);
        return [
            "activity" => $activity
        ];
    }

    public function markAsComplete(int $activity_id)
    {
        $activity = auth()->user()->activities()->findOrFail($activity_id);
        if ($activity->end_time) {
            return;
        }
        $activity->update([
            "end_time" => now()
        ]);
    }


    private function checkIfActivityIsIncomplete()
    {
        $hasIncomplete  = auth()->user()->activities()->whereNull("end_time")->exists();
        if ($hasIncomplete) {
            throw new CheckIfActivityIsInComplete();
        }
    }

    public function delete(int $activity_id)
    {
        auth()->user()->activities()->findOrFail($activity_id)->delete();
    }
}
