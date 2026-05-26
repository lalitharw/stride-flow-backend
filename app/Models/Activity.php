<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activityPoints()
    {
        return $this->hasMany(ActivityPoint::class, "activity_id");
    }
}
