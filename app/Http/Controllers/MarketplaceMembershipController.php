<?php

namespace App\Http\Controllers;

use App\Models\Marketplace;
use App\Models\MarketplaceMembership;
use App\Models\User;
use Illuminate\Http\Request;

class MarketplaceMembershipController extends Controller
{
    public function index(Marketplace $marketplace)
    {
        $this->authorize('manage', $marketplace);

        $memberships = $marketplace->memberships()->with('user')->get();

        return inertia('MarketplaceMemberships/Index', [
            'marketplace' => $marketplace,
            'memberships' => $memberships,
        ]);
    }

    public function store(Request $request, Marketplace $marketplace)
    {
        $this->authorize('manage', $marketplace);

        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'role' => ['required', 'string', 'in:'.MarketplaceMembership::ROLE_SELLER.','.MarketplaceMembership::ROLE_DELIVERY_BEARER],
        ]);

        $existing = MarketplaceMembership::where('marketplace_id', $marketplace->id)
            ->where('user_id', $validated['user_id'])
            ->first();

        if ($existing) {
            return back()->withErrors(['user_id' => 'User already has a membership in this marketplace.']);
        }

        MarketplaceMembership::create([
            'marketplace_id' => $marketplace->id,
            'user_id' => $validated['user_id'],
            'role' => $validated['role'],
            'status' => MarketplaceMembership::STATUS_ACTIVE,
        ]);

        return back();
    }

    public function update(Request $request, Marketplace $marketplace, MarketplaceMembership $membership)
    {
        $this->authorize('manage', $marketplace);

        if ($membership->marketplace_id !== $marketplace->id) {
            abort(404);
        }

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:'.MarketplaceMembership::STATUS_ACTIVE.','.MarketplaceMembership::STATUS_INACTIVE],
        ]);

        $membership->update(['status' => $validated['status']]);

        return back();
    }
}