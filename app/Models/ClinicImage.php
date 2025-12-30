<?php


namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ClinicImage extends Model
{
    use HasFactory;

    // Define the table name (optional if it matches the plural form of the model)
    protected $table = 'clinics_images';
    
    protected $casts = [
    'images' => 'array', // Automatically decode JSON to array when accessed
    ];

    // Specify the fillable fields for mass assignment
    protected $fillable = [
        'clinic_id',
        'path',
    ];

    /**
     * Define the relationship with the Clinic model.
     * Assuming each image belongs to a single clinic.
     */
   // Define the inverse of the relationship
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
