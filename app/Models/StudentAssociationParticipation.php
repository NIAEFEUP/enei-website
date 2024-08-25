<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Laravel\Scout\Searchable;

class StudentAssociationParticipation extends Pivot
{
    use Searchable;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'points',
    ];

    public function student_association(): BelongsTo
    {
        return $this->belongsTo(StudentAssociation::class);
    }

    public function edition(): BelongsTo
    {
        return $this->belongsTo(Edition::class);
    }

    public function toSearchableArray()
    {
        return [
        ];
    }

    protected $table = 'edition_student_association';
}
