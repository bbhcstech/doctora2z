<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;
    
    protected $table = 'about_us';
    protected $fillable = [
        'title',
        'description',
        'banner_image',
        'page_image',
        'button_text',
        'button_url',
    ];
}
