<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketplaceMembership extends Model
{
    public const ROLE_OWNER = 'owner';
    public const ROLE_SELLER = 'seller';
    public const ROLE_DELIVERY_BEARER = 'delivery_bearer';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    protected $fillable = ['marketplace_id', 'user_id', 'role', 'status'];

    public function marketplace(): BelongsTo
    {
        return $this->belongsTo(Marketplace::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
