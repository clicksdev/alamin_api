<?php

// App\Models\Event.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'sub_title',
        'title_ar',
        'sub_title_ar',
        'url',
        'thumbnail',
        'cover',
        'portrait',
        'landscape',
        'categories',
        'date_from',
        'date_to',
        'location_id',
    ];

    protected $dates = ['date_from', 'date_to'];

    public function event_categories()
    {
        return $this->belongsToMany(Category::class, 'event_category', 'event_id', 'category_id');
    }
    // Define the relationship to location
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // Define the related events by location and active scope
    public function relatedEvents()
    {
        return $this->hasMany(Event::class, 'location_id', 'location_id')
                    ->where('id', '!=', $this->id)
                    ->active();
    }
    // Define a scope to filter out expired events
    public function scopeActive($query)
    {
        return $query->where('date_to', '>=', Carbon::now());
    }
    // Mutators for dates to handle Carbon instances
    public function setDateFromAttribute($value)
    {
        $this->attributes['date_from'] = Carbon::parse($value);
    }

    public function setDateToAttribute($value)
    {
        $this->attributes['date_to'] = Carbon::parse($value);
    }
}
