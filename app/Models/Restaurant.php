<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;
    protected $fillable = [
        'photo_path',
        'title',
        'sub_title',
        'location_id',
        'phone',
        'working_from',
        'working_to',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    /**
     * Get the location that owns the restaurant.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
