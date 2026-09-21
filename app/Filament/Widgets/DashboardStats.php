<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Activity;

class DashboardStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make("Total Users",User::count())->description("Registered Users"),
            Stat::make("Unique Users Today",User::today()->count())->description("Total Count of Users Registered Today"),
            Stat::make("Total Activities Completed",Activity::activityCount())->description("Total Count of all completed activities"),
            Stat::make("Completed Activities Today",Activity::activityCompletedToday()->count())->description("Total Count of all completed activities today")
        ];
    }
    
    protected function getColumns(): int{
        return 3;   
    }
}
