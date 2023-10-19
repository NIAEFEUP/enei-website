<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'facebook',
        'github',
        'instagram',
        'linkedin',
        'twitter',
        'website',
    ];

    public function toSearchableArray(): array
    {
        return [
            'email' => $this->email,
            'facebook' => $this->facebook,
            'github' => $this->github,
            'instagram' => $this->instagram,
            'linkedin' => $this->linkedin,
            'twitter' => $this->twitter,
            'website' => $this->website,
        ];
    }
}
