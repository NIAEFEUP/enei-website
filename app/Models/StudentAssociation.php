<?php

namespace App\Models;

use App\Traits\HasReferralLink;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentAssociation extends Model
{
    use HasFactory;
    use HasReferralLink;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'code',
    ];

    private function getPromoterCode(): string
    {
        return $this->user_id;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function editions(): BelongsToMany
    {
        return $this->belongsToMany(Edition::class)->using(StudentAssociationParticipation::class)->withPivot('points');
    }

    public function promoted(): HasMany
    {
        return $this->hasMany(Participant::class, 'promoter');
    }

    public function toSearchableArray(): array
    {
        return [];
    }
}
