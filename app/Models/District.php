<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $table = 'districts';

    protected $fillable = ['name', 'state_id'];

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    // one district can have many pincodes
    public function pincodes()
    {
        return $this->hasMany(Pincode::class, 'district_id');
    }

    // if you want easy access to a primary pincode
    public function primaryPincode()
    {
        return $this->hasOne(Pincode::class, 'district_id')->latestOfMany();
    }
}
