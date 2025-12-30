<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = 'cities';

    // table has: id, district_id, name, created_at, updated_at
    protected $fillable = ['name', 'district_id'];

    // relationships
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    // one city can have many pincodes
    public function pincodes()
    {
        return $this->hasMany(Pincode::class, 'city_id');
    }

    public function primaryPincode()
    {
        return $this->hasOne(Pincode::class, 'city_id')->latestOfMany();
    }

    // helpers (optional)
    public function scopeByDistrict($q, int $districtId)
    {
        return $q->where('district_id', $districtId);
    }
}
