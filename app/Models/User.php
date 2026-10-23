<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

#[Fillable(['name', 'email', 'password', 'is_platform_admin'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public function ownedMarketplaces(): HasMany
    {
        return $this->hasMany(Marketplace::class, 'owner_id');
    }

    public function marketplaceMemberships(): HasMany
    {
        return $this->hasMany(MarketplaceMembership::class);
    }

    public function marketplaces(): BelongsToMany
    {
        return $this->belongsToMany(Marketplace::class, 'marketplace_memberships');
    }

    public function marketplaceApplications(): HasMany
    {
        return $this->hasMany(MarketplaceApplication::class, 'applicant_id');
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_platform_admin' => 'boolean',
        ];
    }
}
