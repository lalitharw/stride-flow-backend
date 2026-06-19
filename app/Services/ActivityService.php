<?php

namespace App\Services;

use App\Exceptions\Api\CheckIfActivityIsInComplete;
use App\Models\Activity;
use App\Models\ActivityPoint;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

class ActivityService
{
    protected ActivityPointService $activityPointService;
    public function __construct(ActivityPointService $activityPointService)
    {
        $this->activityPointService = $activityPointService;
    }

    public function store(array $data)
    {
        $this->checkIfActivityIsIncomplete();
        $activity = auth()->user()->activities()->create([
            "type" => "walk",
            "start_time" => now()
        ]);

        return [
            "activity" => $activity,
        ];
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
        $activity = auth()->user()->activities()->with("activityPoints")->findOrFail($activity_id);
        $activity->route = $activity->activityPoints
            ->sortBy("order")
            ->map(fn($point) => [
                $point->latitude,
                $point->longitude
            ])->values();
        unset($activity->activityPoints);
        return [
            "activity" => $activity
        ];
    }

    public function markAsComplete(int $activity_id)
    {
        $key = "activity:{$activity_id}:points";
        $raw = Redis::lrange($key, 0, -1);
        DB::transaction(function () use ($activity_id, $raw) {
            if (!empty($raw)) {
                $allPoints = collect($raw)->map(fn($item) => json_decode($item, true))->sortBy("sequence")->flatMap(fn($b) => $b["co_ordinates"])->values();
                $this->activityPointService->blukStore($allPoints, $activity_id);
            }
            auth()->user()->activities()->find($activity_id)->update([
                "end_time" => now()
            ]);
        });
        Redis::del($key);
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
