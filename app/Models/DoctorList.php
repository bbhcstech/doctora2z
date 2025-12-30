<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorList extends Model
{
    use HasFactory;
      // Specify the table name explicitly
    protected $table = 'doctors';

    protected $fillable = [
        'clinic_ids',
        'hospital_ids',
        'residence_ids',
        'nursing_ids',
        'outdoor_ids',
        'medica_ids',
        'name',
        'fees',
        'user_id',
        'auth_id',
        'degree',
        'phone_number',
        'personal_phone_number',
        'email',
        'specialization',
        'reg_no',
        'profile_text',
        'rating',
        'active',
        'country_id',
        'country_name',
        'state_id',
        'state_name',
        'district_id',
        'district_name',
        'city_id',
        'city_name',
        'address',
        'visiting_time',
        'date_picker',
        'date_wise_checkbox',
        'day_wise_checkbox',
        'time_checkbox',
        'time_slot',
        'month',
        'day',
        'start_time',
        'end_time',
        'category_id',
        'sub_category',
        'image',
        'last_update',
        'status',
        'tags',
        'whatsapp',
        'facebook',
        'instagram',
        'website',
        'latitude',
        'logitude',
        'language',
        'experience',
        'mode_of_payment',
        'loc1',
        'loc2',
        'loc3',
        'loc4',
        'loc5',
        'membership',
        'created_by',
        'updated_by'
       
        
    ];
    
    

    // Optionally, you can specify if you want to automatically manage the `created_at` and `updated_at` timestamps
    public $timestamps = true;
    
    public function userType()
    {
        return $this->belongsTo(UserType::class, 'user_id', 'id');
    }
    
    // DoctorList Model
   public function clinics()
{
    return $this->belongsTo(Client::class,'clinic_ids');
}
 public function hospitals()
{
    return $this->belongsTo(Hospital::class,'hospital_ids');
}
public function medicas()
{
    return $this->belongsTo(Medicashop::class,'medica_ids');
}

// Doctor.php
public function category()
{
    return $this->belongsTo(Category::class, 'category_id');
}

   
}
