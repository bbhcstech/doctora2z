<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'image', 'address_link', 
        'country_id', 'country_name', 
        'state_id', 'state_name', 
        'district_id', 'district_name', 
        'city_id', 'city_name'
    ];
    
     // Relationships
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
    
   

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}