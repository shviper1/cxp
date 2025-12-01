<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $fillable = [
        'name',
        'post_type',
        'currency_symbol',
        'order',
        'amount',
    ];

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function isPaid(): bool
    {
        return $this->post_type === 'paid';
    }

    public function isFree(): bool
    {
        return $this->post_type === 'free';
    }
}
