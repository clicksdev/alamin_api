<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
class Restaurant extends Model
{
    use HasFactory;
    protected $fillable = [
        'photo_path',
        'title',
        'sub_title',
        'title_ar',
        'sub_title_ar',
        "description",
        "description_ar",
        'location_id',
        'phone',
        'website',
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
