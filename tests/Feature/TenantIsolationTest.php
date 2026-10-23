<?php

use App\Models\Marketplace;
use App\Models\MarketplaceMembership;
use App\Models\User;

it('denies unauthenticated marketplace access', function () {
    $owner = User::factory()->create();
    $marketplace = Marketplace::create(['owner_id' => $owner->id, 'name' => 'M', 'slug' => 'm', 'status' => 'active']);

    $this->get("/marketplaces/{$marketplace->slug}")->assertStatus(302);
});

it('allows active member', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $marketplace = Marketplace::create(['owner_id' => $owner->id, 'name' => 'M', 'slug' => 'm', 'status' => 'active']);
    MarketplaceMembership::create(['marketplace_id' => $marketplace->id, 'user_id' => $member->id, 'status' => 'active']);

    $this->actingAs($member)->get("/marketplaces/{$marketplace->slug}")->assertOk();
});

it('allows user in two marketplaces', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $m1 = Marketplace::create(['owner_id' => $owner->id, 'name' => 'M1', 'slug' => 'm1', 'status' => 'active']);
    $m2 = Marketplace::create(['owner_id' => $owner->id, 'name' => 'M2', 'slug' => 'm2', 'status' => 'active']);
    MarketplaceMembership::create(['marketplace_id' => $m1->id, 'user_id' => $member->id, 'status' => 'active']);
    MarketplaceMembership::create(['marketplace_id' => $m2->id, 'user_id' => $member->id, 'status' => 'active']);

    $this->actingAs($member)->get("/marketplaces/{$m1->slug}")->assertOk();
    $this->actingAs($member)->get("/marketplaces/{$m2->slug}")->assertOk();
});

it('denies no membership', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $marketplace = Marketplace::create(['owner_id' => $owner->id, 'name' => 'M', 'slug' => 'm', 'status' => 'active']);

    $this->actingAs($other)->get("/marketplaces/{$marketplace->slug}")->assertStatus(403);
});

it('denies suspended membership', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $marketplace = Marketplace::create(['owner_id' => $owner->id, 'name' => 'M', 'slug' => 'm', 'status' => 'active']);
    MarketplaceMembership::create(['marketplace_id' => $marketplace->id, 'user_id' => $member->id, 'status' => 'suspended']);

    $this->actingAs($member)->get("/marketplaces/{$marketplace->slug}")->assertStatus(403);
});

it('denies inactive marketplace', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $marketplace = Marketplace::create(['owner_id' => $owner->id, 'name' => 'M', 'slug' => 'm', 'status' => 'inactive']);
    MarketplaceMembership::create(['marketplace_id' => $marketplace->id, 'user_id' => $member->id, 'status' => 'active']);

    $this->actingAs($member)->get("/marketplaces/{$marketplace->slug}")->assertStatus(403);
});

it('denies non-admin admin route', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->get('/admin')->assertStatus(403);
});

it('allows platform admin admin route', function () {
    $admin = User::factory()->create(['is_platform_admin' => true]);
    $this->actingAs($admin)->get('/admin')->assertOk();
});

it('distinguishes view and manage policy', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $marketplace = Marketplace::create(['owner_id' => $owner->id, 'name' => 'M', 'slug' => 'm', 'status' => 'active']);
    MarketplaceMembership::create(['marketplace_id' => $marketplace->id, 'user_id' => $member->id, 'status' => 'active', 'role' => 'buyer']);

    expect($member->can('view', $marketplace))->toBeTrue();
    expect($member->can('manage', $marketplace))->toBeFalse();
});

it('denies platform admin ordinary marketplace without membership', function () {
    $owner = User::factory()->create();
    $admin = User::factory()->create(['is_platform_admin' => true]);
    $marketplace = Marketplace::create(['owner_id' => $owner->id, 'name' => 'M', 'slug' => 'm', 'status' => 'active']);

    $this->actingAs($admin)->get("/marketplaces/{$marketplace->slug}")->assertStatus(403);
});

it('allows owner to manage marketplace', function () {
    $owner = User::factory()->create();
    $marketplace = Marketplace::create(['owner_id' => $owner->id, 'name' => 'M', 'slug' => 'm', 'status' => 'active']);
    MarketplaceMembership::create(['marketplace_id' => $marketplace->id, 'user_id' => $owner->id, 'status' => 'active', 'role' => 'owner']);

    expect($owner->can('manage', $marketplace))->toBeTrue();
});

it('denies buyer manage marketplace', function () {
    $owner = User::factory()->create();
    $buyer = User::factory()->create();
    $marketplace = Marketplace::create(['owner_id' => $owner->id, 'name' => 'M', 'slug' => 'm', 'status' => 'active']);
    MarketplaceMembership::create(['marketplace_id' => $marketplace->id, 'user_id' => $buyer->id, 'status' => 'active', 'role' => 'buyer']);

    expect($buyer->can('manage', $marketplace))->toBeFalse();
});

it('denies cross-tenant slug substitution', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $m1 = Marketplace::create(['owner_id' => $owner->id, 'name' => 'M1', 'slug' => 'm1', 'status' => 'active']);
    $m2 = Marketplace::create(['owner_id' => $owner->id, 'name' => 'M2', 'slug' => 'm2', 'status' => 'active']);
    MarketplaceMembership::create(['marketplace_id' => $m1->id, 'user_id' => $member->id, 'status' => 'active']);

    $this->actingAs($member)->get("/marketplaces/{$m2->slug}")->assertStatus(403);
});

it('denies unauthenticated admin route', function () {
    $this->get('/admin')->assertStatus(302);
});

it('denies inactive membership', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $marketplace = Marketplace::create(['owner_id' => $owner->id, 'name' => 'M', 'slug' => 'm', 'status' => 'active']);
    MarketplaceMembership::create(['marketplace_id' => $marketplace->id, 'user_id' => $member->id, 'status' => 'inactive']);

    $this->actingAs($member)->get("/marketplaces/{$marketplace->slug}")->assertStatus(403);
});

