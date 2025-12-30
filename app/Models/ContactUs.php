<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    use HasFactory;

    protected $table = 'contact_us'; // Table name

    protected $fillable = [
        'title',
        'address',
        'mail',
        'phone',
        'map_url',
        'banner_image',
    ];
}
