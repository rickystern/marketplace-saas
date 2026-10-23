<?php

use App\Models\Marketplace;
use App\Models\MarketplaceMembership;
use App\Models\User;

it('authorizes memberships independently across marketplaces', function () {
    $user = User::factory()->create();
    $owner = User::factory()->create();
    $m1 = Marketplace::create(['owner_id' => $owner->id, 'name' => 'M1', 'slug' => 'm1', 'status' => 'active']);
    $m2 = Marketplace::create(['owner_id' => $owner->id, 'name' => 'M2', 'slug' => 'm2', 'status' => 'active']);
    MarketplaceMembership::create(['marketplace_id' => $m1->id, 'user_id' => $user->id, 'status' => 'active', 'role' => 'owner']);
    MarketplaceMembership::create(['marketplace_id' => $m2->id, 'user_id' => $user->id, 'status' => 'inactive', 'role' => 'owner']);

    expect($user->can('manage', $m1))->toBeTrue();
    expect($user->can('manage', $m2))->toBeFalse();
});

it('denies platform admin implicit ordinary access', function () {
    $admin = User::factory()->create(['is_platform_admin' => true]);
    $owner = User::factory()->create();
    $marketplace = Marketplace::create(['owner_id' => $owner->id, 'name' => 'M', 'slug' => 'm', 'status' => 'active']);

    expect($admin->can('view', $marketplace))->toBeFalse();
    expect($admin->can('manage', $marketplace))->toBeFalse();
});