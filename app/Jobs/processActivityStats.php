<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class processActivityStats implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    protected array $allPoints;
    protected int $activity_id;
    public function __construct(array $allPoints, int $activity_id)
    {
        $this->allPoints = $allPoints;
        $this->activity_id = $activity_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $activity = Activity::find($this->activity_id);

        // $duration = Carbon::parse($activity->start_time)->diffInSeconds(Carbon::parse($activity->end_time));
        
    }
}
