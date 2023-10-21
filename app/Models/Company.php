<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;
use League\CommonMark\Util\HtmlFilter;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Company extends Model
{
    use HasFactory;
    use HasRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'description',
        'user_id',
        'social_media_id',
    ];

    protected $with = ['socialMedia'];

    protected $appends = ['description_html'];

    public function socialMedia(): BelongsTo
    {
        return $this->belongsTo(SocialMedia::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sponsors(): HasMany
    {
        return $this->hasMany(Sponsor::class);
    }

    public function descriptionHtml(): Attribute
    {
        return Attribute::get(fn () => Str::markdown($this->description ?? '', [
            'html_input' => HtmlFilter::STRIP,
            'allow_unsafe_links' => false,
        ]));
    }

    public function participants(): HasManyThrough
    {
        return $this->hasManyDeep(
            Participant::class,
            [
                Sponsor::class,
                Stand::class,
                Quest::class,
                'enrollment_quest',
                Enrollment::class,
            ], [
                null, // FK on "sponsor" table
                null, // FK on "stand" table
                ['requirement_type', 'requirement_id'], // FK on "quest" table
                null, // FK on "enrollment_quest" table
                null, // FK on "Enrollment" table
                'id', // Local Key on "Participant" table
            ], [
                null, // Local key on "company" table
                null, // Local key on "sponsor" table
                null, // Local key on "stand" table
                null, // Local key on "quest" table
                null, // Local key on "enrollment_quest" table
                'participant_id', // Foreign key on "Enrollment" table
            ]
        )->distinct();
    }

    public function toSearchableArray(): array
    {
        return [
            'description' => $this->description,
            'social_media' => $this->socialMedia?->toSearchableArray(),
        ];
    }
}
