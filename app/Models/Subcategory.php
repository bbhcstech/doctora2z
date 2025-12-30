<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    // Define the table associated with the model
    protected $table = 'subcategory';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'category_id',
        'name',
    ];

    // Define the relationship with the Category model
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
