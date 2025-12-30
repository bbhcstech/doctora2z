<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Define the table associated with the model
    protected $table = 'categories';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'type',
        'name',
        'image'
    ];
    
    // public function doctors()
    // {
    //     return $this->hasMany(DoctorList::class, 'category_id', 'id');
    // }
    public function doctors()
{
    return $this->hasMany(Doctor::class, 'category_id', 'id');
}
}
