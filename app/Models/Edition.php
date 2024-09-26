<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Laravel\Scout\Searchable;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Edition extends Model
{
    use HasFactory;
    use HasRelationships;
    use Searchable;

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
        return $this->through('event_days')->has('events')
            ->orderBy('event_days.date')->orderBy('time_start');
    }

    public function workshops(): HasManyThrough
    {
        return $this->through('event_days')->has('workshops');
    }

    public function talks(): HasManyThrough
    {
        return $this->through('event_days')->has('talks');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class)->orderBy('name');
    }

    public function quests(): HasMany
    {
        return $this->hasMany(Quest::class);
    }

    public function slots(): HasManyThrough
    {
        return $this->hasManyDeep(Slot::class, [Quest::class, 'quest_slot'])
            ->orderBy('name')
            ->distinct();
    }

    public function speakers(): HasManyThrough
    {
        return $this->hasManyDeep(User::class, [EventDay::class, Event::class, 'event_user'])
            ->whereHasMorph('usertype', Speaker::class)
            ->distinct();
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
        return $this->hasMany(Department::class)
            ->orderByDesc('priority')
            ->orderBy('name');
    }

    public function sponsor_tiers(): HasMany
    {
        return $this->hasMany(SponsorTier::class)->orderByDesc('rank');
    }

    public function competitions(): HasMany
    {
        return $this->hasMany(Competition::class)->orderBy('name');
    }

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'year' => $this->year,
        ];
    }
}
