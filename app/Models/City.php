<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $fillable = [
        'state_id',
        'name',
        'post_type',
        'order',
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function getEffectivePostType(): string
    {
        if ($this->post_type !== 'inherit') {
            return $this->post_type;
        }
        return $this->state->getEffectivePostType();
    }

    public function isPaid(): bool
    {
        return $this->getEffectivePostType() === 'paid';
    }

    public function isFree(): bool
    {
        return $this->getEffectivePostType() === 'free';
    }
}
