<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;
    protected $table = 'states';

    protected $primaryKey = 'id'; // Specify custom primary key
    public $incrementing = true; // Auto-increment
    protected $fillable = ['name', 'country_id', 'is_active', 'sort_order', 'lang'];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function cities()
    {
        return $this->hasMany(City::class, 'state_id');
    }
}
