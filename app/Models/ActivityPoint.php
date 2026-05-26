<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityPoint extends Model
{
    protected $guarded = [];
    protected $casts = [
        "co_ordinate_data" => "array"
    ];


    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
