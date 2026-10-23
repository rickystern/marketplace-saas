<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Marketplace extends Model
{
    protected $fillable = ['owner_id', 'name', 'slug', 'status', 'branding', 'settings'];

    protected function casts(): array
    {
        return [
            'branding' => 'array',
            'settings' => 'array',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(MarketplaceMembership::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'marketplace_memberships');
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class);
    }
}
