<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    protected $fillable = [
        'country_id',
        'name',
        'post_type',
        'order',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
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
        return $this->country->post_type;
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
