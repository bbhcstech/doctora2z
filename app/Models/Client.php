<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clinics';

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    // Allow mass assignment
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'phone_number2',
        'address',
        'country_id',
        'country_name',
        'state_id',
        'state_name',
        'district_id',
        'district_name',
        'city_id',
        'city_name',
        'other_information',
        'pincode',
        'website',
        'images',
        'user_id',
        'status',
        'auth_id',
        'category_id',
        'tags',
        'latitude',
        'longitude',
        'created_by',
        'updated_by',
    ];

    // Correct casts
    protected $casts = [
        'images' => 'array',
        'tags' => 'array',

        // ❌ Removed wrong cast for category_id
        // 'category_id' => 'array', 

        // If you want to store single category, keep as string:
        'category_id' => 'string',

        'latitude' => 'float',
        'longitude' => 'float',
        'approved' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'last_update' => 'datetime',
    ];

    // Relationships
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class, 'clinic_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function clinicImages()
    {
        return $this->hasMany(ClinicImage::class, 'clinic_id', 'id');
    }

    public function userType()
    {
        return $this->belongsTo(UserType::class, 'user_id', 'id');
    }
}
