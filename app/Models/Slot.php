<?php

namespace App\Models;

use App\Traits\HasImageSlot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;

class Slot extends Model
{
    use HasFactory;
    use HasImageSlot;
    use Searchable;

    protected $fillable = [
        'total_quests',
        'points',
        'name',
        'image_path',
    ];

    protected $appends = [
        'image_slot_url',
    ];

    protected $with = [
        'quests',
    ];

    public function quests(): BelongsToMany
    {
        return $this->belongsToMany(Quest::class);
    }

    public function enrollments(): BelongsToMany
    {
        return $this->belongsToMany(Enrollment::class);
    }

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'points' => $this->points,
            'total_quests' => $this->total_quests,
        ];
    }
}
