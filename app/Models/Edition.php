<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Edition extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'year',
    ];

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function event_days(): HasMany
    {
        return $this->hasMany(EventDay::class);
    }

    public function events(): HasManyThrough
    {
        return $this->through('event_days')->has('events');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function quests(): HasMany
    {
        return $this->hasMany(Quest::class);
    }

    public function speakers(): HasManyThrough
    {
        return $this->through('events')->has('speakers');
    }

    public function sponsors(): HasMany
    {
        return $this->hasMany(Sponsor::class);
    }

    public function stands(): HasManyThrough
    {
        return $this->through('event_days')->has('stands');
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }
}
