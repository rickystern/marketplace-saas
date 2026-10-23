<?php

namespace App\Policies;

use App\Models\Marketplace;
use App\Models\MarketplaceMembership;
use App\Models\User;

class MarketplacePolicy
{
    public function view(User $user, Marketplace $marketplace): bool
    {
        return $marketplace->memberships()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->exists();
    }

    public function manage(User $user, Marketplace $marketplace): bool
    {
        return $marketplace->memberships()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->where('role', MarketplaceMembership::ROLE_OWNER)
            ->exists();
    }
}
