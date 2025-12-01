<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'suspended_until',
        'phone',
        'date_of_birth',
        'gender',
        'occupation',
        'bio',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'verification_status',
        'id_document_path',
        'selfie_path',
        'verification_notes',
        'verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'suspended_until' => 'datetime',
            'date_of_birth' => 'date',
            'verified_at' => 'datetime',
        ];
    }

    public function isSuspended(): bool
    {
        return $this->suspended_until && $this->suspended_until->isFuture();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }

    public function isPendingVerification(): bool
    {
        return $this->verification_status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->verification_status === 'rejected';
    }
}
