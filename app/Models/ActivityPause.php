<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityPause extends Model
{
    protected $guarded = [];

    protected function activity(){
        return $this->belongsTo(Activity::class);
    }
}
