<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrendingDoctor extends Model
{
    protected $fillable = ['name','doctor_id','doctor_visiting_count_id','total_visit_count'];
}
