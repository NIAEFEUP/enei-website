<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;

class Order extends Model
{
    use HasFactory;
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'participant_id',
        'product_id',
        'quantity',
        'total',
        'state',
    ];


    /**
     * Get the participant that owns the order.
     */
    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    /**
     * Get the product that is associated with the order.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function toSearchableArray(): array
    {
        return [
        'id' => $this->id,
        'participant_id' => $this->participant_id,
        'product_id' => $this->product_id,
        'quantity' => $this->quantity,
        'total' => $this->total,
        'state' => $this->state,
        ];
    }
}
