<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $table = 'advertisement';

    protected $fillable = [
        'category_id',
        'category_name',
        'country_id',
        'country_name',
        'state_id',
        'state_name',
        'district_id',
        'district_name',
        'city_id',
        'city_name',
        'status',
        'title',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    
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
