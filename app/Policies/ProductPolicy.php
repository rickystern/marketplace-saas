<?php

namespace App\Policies;

use App\Models\Marketplace;
use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(User $user, Marketplace $marketplace): bool
    {
        return $marketplace->memberships()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->exists();
    }

    public function view(User $user, Product $product): bool
    {
        return $product->marketplace->memberships()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->exists();
    }

    public function create(User $user, Marketplace $marketplace): bool
    {
        return $marketplace->memberships()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->whereIn('role', ['owner', 'seller'])
            ->exists();
    }

    public function update(User $user, Product $product): bool
    {
        return $product->seller_id === $user->id
            && $product->marketplace->memberships()
                ->where('user_id', $user->id)
                ->where('status', 'active')
                ->whereIn('role', ['owner', 'seller'])
                ->exists();
    }

    public function deactivate(User $user, Product $product): bool
    {
        $activeMembership = $product->marketplace->memberships()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (! $activeMembership) {
            return false;
        }

        if ($activeMembership->role === 'owner') {
            return true;
        }

        return $activeMembership->role === 'seller' && $product->seller_id === $user->id;
    }
}
