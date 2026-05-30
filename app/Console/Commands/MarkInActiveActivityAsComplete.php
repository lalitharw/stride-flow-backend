<?php

namespace App\Console\Commands;

use App\Models\Activity;
use App\Models\ActivityPoint;
use Illuminate\Console\Command;

class MarkInActiveActivityAsComplete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mark-in-active-activity-as-complete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Marking Inactive Activity As Completed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $inactivity_activities = ActivityPoint::where("created_at", now())->get();
    }
}
