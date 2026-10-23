<?php

use App\Models\Marketplace;
use App\Models\MarketplaceApplication;
use App\Models\MarketplaceMembership;
use App\Models\Subscription;
use App\Models\User;

it('creates marketplace with owner relationship', function () {
    $owner = User::factory()->create();
    $marketplace = Marketplace::create([
        'owner_id' => $owner->id,
        'name' => 'Test Market',
        'slug' => 'test-market',
        'status' => 'pending',
    ]);

    expect($marketplace->owner->id)->toBe($owner->id);
    expect($owner->ownedMarketplaces()->count())->toBe(1);
});

it('creates marketplace application relationships', function () {
    $applicant = User::factory()->create();
    $reviewer = User::factory()->create(['is_platform_admin' => true]);

    $application = MarketplaceApplication::create([
        'applicant_id' => $applicant->id,
        'proposed_name' => 'Proposed',
        'proposed_slug' => 'proposed',
        'status' => 'submitted',
        'reviewed_by' => $reviewer->id,
        'reviewed_at' => now(),
    ]);

    expect($application->applicant->id)->toBe($applicant->id);
    expect($application->reviewer->id)->toBe($reviewer->id);
});

it('allows user in multiple marketplaces', function () {
    $user = User::factory()->create();
    $m1 = Marketplace::create(['owner_id' => $user->id, 'name' => 'M1', 'slug' => 'm1', 'status' => 'pending']);
    $m2 = Marketplace::create(['owner_id' => $user->id, 'name' => 'M2', 'slug' => 'm2', 'status' => 'pending']);

    MarketplaceMembership::create(['marketplace_id' => $m1->id, 'user_id' => $user->id, 'role' => 'buyer']);
    MarketplaceMembership::create(['marketplace_id' => $m2->id, 'user_id' => $user->id, 'role' => 'seller']);

    expect($user->marketplaces()->count())->toBe(2);
});

it('enforces unique membership per marketplace and user', function () {
    $user = User::factory()->create();
    $marketplace = Marketplace::create(['owner_id' => $user->id, 'name' => 'M', 'slug' => 'm', 'status' => 'pending']);

    MarketplaceMembership::create(['marketplace_id' => $marketplace->id, 'user_id' => $user->id]);

    expect(fn () => MarketplaceMembership::create(['marketplace_id' => $marketplace->id, 'user_id' => $user->id]))
        ->toThrow(\Illuminate\Database\QueryException::class);
});

it('allows one subscription per marketplace', function () {
    $user = User::factory()->create();
    $marketplace = Marketplace::create(['owner_id' => $user->id, 'name' => 'M', 'slug' => 'm', 'status' => 'pending']);

    Subscription::create(['marketplace_id' => $marketplace->id]);

    expect(fn () => Subscription::create(['marketplace_id' => $marketplace->id]))
        ->toThrow(\Illuminate\Database\QueryException::class);
});

it('defaults platform admin to false', function () {
    $user = User::factory()->create();
    $user->refresh();
    expect($user->is_platform_admin)->toBeFalse();
});

it('supports platform admin true', function () {
    $user = User::factory()->create(['is_platform_admin' => true]);
    expect($user->is_platform_admin)->toBeTrue();
});

it('deletes marketplace memberships when marketplace deleted', function () {
    $user = User::factory()->create();
    $marketplace = Marketplace::create(['owner_id' => $user->id, 'name' => 'M', 'slug' => 'm', 'status' => 'pending']);
    MarketplaceMembership::create(['marketplace_id' => $marketplace->id, 'user_id' => $user->id]);

    $marketplace->delete();

    expect(MarketplaceMembership::count())->toBe(0);
});
