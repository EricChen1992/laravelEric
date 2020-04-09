<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PunchTime extends Model
{
    //
    protected $fillable = ['cua_id','name','date','time_clock_in','time_clock_out','type'];
}
