<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    use HasFactory;
    protected $table = 'page_section';

    protected $fillable = [
        'page_id', 'header', 'details', 'image', 
        'image_position', 'button', 'button_active', 'button_position'
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
