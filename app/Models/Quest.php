<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Quest extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'points',
        'category',
    ];

    public function edition(): BelongsTo {
        return $this->belongsTo(Edition::class);
    }

    public function enrollments(): BelongsToMany {
        return $this->belongsToMany(Enrollment::class);
    }
}