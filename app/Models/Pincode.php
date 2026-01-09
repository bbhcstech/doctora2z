<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Pincode extends Model
{
    protected $table = 'pincodes';

    protected $fillable = [
        'pincode',
        'country_id',
        'state_id',
        'district_id',
        'city_id',
    ];

    protected $casts = [
        'country_id'  => 'integer',
        'state_id'    => 'integer',
        'district_id' => 'integer',
        'city_id'     => 'integer',
    ];

    // ---- relationships ----
    public function country()
    {return $this->belongsTo(Country::class, 'country_id');}
    public function state()
    {return $this->belongsTo(State::class, 'state_id');}
    public function district()
    {return $this->belongsTo(District::class, 'district_id');}
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // ---- scopes/helpers ----
    public function scopeFindByPincode(Builder $q, string $pincode): Builder
    {
        return $q->where('pincode', trim($pincode));
    }

    // normalize outgoing payload for the UI (no phantom fields)
    public function toPayloadArray(): array
    {
        return [
            'id'          => $this->id,
            'pincode'     => $this->pincode,
            'country_id'  => $this->country_id,
            'country'     => $this->country?->name,
            'state_id'    => $this->state_id,
            'state'       => $this->state?->name,
            'district_id' => $this->district_id,
            'district'    => $this->district?->name,
            'city_id'     => $this->city_id,
            'city'        => $this->city?->name,
            'created_at'  => optional($this->created_at)->toDateTimeString(),
            'updated_at'  => optional($this->updated_at)->toDateTimeString(),
        ];
    }

    // optional: force 6 numeric digits when setting pincode
    public function setPincodeAttribute($value): void
    {
        $digits                      = preg_replace('/[^0-9]/', '', (string) $value);
        $this->attributes['pincode'] = substr($digits, 0, 6);
    }
}
