<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Activity extends Model
{
    protected $guarded = [];

    protected $appends = [
        "formatted_start_time",
        "formatted_end_time"
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activityPoints()
    {
        return $this->hasMany(ActivityPoint::class, "activity_id");
    }

    protected function formattedStartTime(): Attribute {
        return Attribute::make(
            get: fn() =>  $this->start_time ? Carbon::parse($this->start_time)->format('d-m-Y H:i') : null
        );
    }

    protected function formattedEndTime():Attribute {
        return Attribute::make(
            get: fn() =>  $this->end_time ? Carbon::parse($this->end_time)->format('d-m-Y H:i') : null
        );
    }


    //local scope
    public function scopeActivityCount($query){
        return $query->where("status","completed")->count();
    }

    public function scopeActivityCompletedToday($query){
        return $query->where("status","completed")->where("created_at",">=",Carbon::today()->startOfDay());
    }

    public function scopeActivityActiveToday($query){
        return $query->where("status","active")->where("created_at",">=",Carbon::today()->startOfDay());
    }
    
}
