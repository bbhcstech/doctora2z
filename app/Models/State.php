<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;
    
    // ✅ Database-এ যা আছে তাই use করছি
    protected $table = 'states';
    protected $primaryKey = 'id'; // Database-এ 'id' নামে আছে
    public $incrementing = true;
    
    // ✅ Actual database columns
    protected $fillable = [
        'name',        // Database-এ 'name' নামে আছে
        'country_id', 
        // নোট: 'is_active', 'sort_order', 'lang' নেই database-এ
    ];
    
    // ✅ Timestamps আছে database-এ
    public $timestamps = true;
    
    // ✅ Accessor for compatibility
    public function getStateAttribute()
    {
        return $this->name; // 'name' কে 'state' হিসেবে access করতে
    }
    
    public function getIdStateAttribute()
    {
        return $this->id; // 'id' কে 'id_state' হিসেবে access করতে
    }
    
    // ✅ Relation
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function cities()
    {
        return $this->hasMany(City::class, 'state_id');
    }
}