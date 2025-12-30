<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerImage extends Model
{
    use HasFactory;
     // Specify the table name explicitly
    protected $table = 'banner_images';

    protected $fillable = [
        'name',
        'image',
        'mobile_image'
    ];
}
