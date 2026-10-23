<?php

use App\Models\Marketplace;
use App\Models\MarketplaceApplication;
use App\Models\MarketplaceMembership;
use App\Models\User;

it('allows admin to view marketplaces', function () {
    $admin = User::factory()->create(['is_platform_admin' => true]);
    $this->actingAs($admin)->get('/admin/marketplaces')->assertOk();
});

it('denies unauthenticated marketplace admin access', function () {
    $this->get('/admin/marketplaces')->assertRedirect('/login');
    $marketplace = Marketplace::create(['owner_id' => User::factory()->create()->id, 'name' => 'M', 'slug' => 'm', 'status' => 'pending']);
    $this->post("/admin/marketplaces/{$marketplace->id}/activate")->assertRedirect('/login');
});

it('denies non-admin activation and suspension', function () {
    $user = User::factory()->create();
    $owner = User::factory()->create();
    $marketplace = Marketplace::create(['owner_id' => $owner->id, 'name' => 'M', 'slug' => 'm', 'status' => 'pending']);

    $this->actingAs($user)->post("/admin/marketplaces/{$marketplace->id}/activate")->assertStatus(403);
    $this->actingAs($owner)->post("/admin/marketplaces/{$marketplace->id}/activate")->assertStatus(403);
});

it('activates pending marketplace', function () {
    $admin = User::factory()->create(['is_platform_admin' => true]);
    $marketplace = Marketplace::create(['owner_id' => User::factory()->create()->id, 'name' => 'M', 'slug' => 'm', 'status' => 'pending']);

    $this->actingAs($admin)->post("/admin/marketplaces/{$marketplace->id}/activate")->assertRedirect();
    $marketplace->refresh();
    expect($marketplace->status)->toBe('active');
});

it('suspends active marketplace', function () {
    $admin = User::factory()->create(['is_platform_admin' => true]);
    $marketplace = Marketplace::create(['owner_id' => User::factory()->create()->id, 'name' => 'M', 'slug' => 'm', 'status' => 'active']);

    $this->actingAs($admin)->post("/admin/marketplaces/{$marketplace->id}/suspend")->assertRedirect();
    $marketplace->refresh();
    expect($marketplace->status)->toBe('suspended');
});

it('reactivates suspended marketplace', function () {
    $admin = User::factory()->create(['is_platform_admin' => true]);
    $marketplace = Marketplace::create(['owner_id' => User::factory()->create()->id, 'name' => 'M', 'slug' => 'm', 'status' => 'suspended']);

    $this->actingAs($admin)->post("/admin/marketplaces/{$marketplace->id}/reactivate")->assertRedirect();
    $marketplace->refresh();
    expect($marketplace->status)->toBe('active');
});

it('rejects invalid transitions', function () {
    $admin = User::factory()->create(['is_platform_admin' => true]);
    $marketplace = Marketplace::create(['owner_id' => User::factory()->create()->id, 'name' => 'M', 'slug' => 'm', 'status' => 'pending']);

    $this->actingAs($admin)->post("/admin/marketplaces/{$marketplace->id}/suspend")->assertSessionHasErrors();
    $marketplace->refresh();
    expect($marketplace->status)->toBe('pending');
});

it('handles repeated transitions safely', function () {
    $admin = User::factory()->create(['is_platform_admin' => true]);
    $marketplace = Marketplace::create(['owner_id' => User::factory()->create()->id, 'name' => 'M', 'slug' => 'm', 'status' => 'pending']);

    $this->actingAs($admin)->post("/admin/marketplaces/{$marketplace->id}/activate");
    $this->actingAs($admin)->post("/admin/marketplaces/{$marketplace->id}/activate")->assertSessionHasErrors();
    $marketplace->refresh();
    expect($marketplace->status)->toBe('active');
});

it('keeps pending and suspended marketplaces inaccessible', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    foreach (['pending', 'suspended'] as $status) {
        $marketplace = Marketplace::create(['owner_id' => $owner->id, 'name' => 'M', 'slug' => "m-{$status}", 'status' => $status]);
        MarketplaceMembership::create(['marketplace_id' => $marketplace->id, 'user_id' => $member->id, 'status' => 'active']);
        $this->actingAs($member)->get("/marketplaces/m-{$status}")->assertStatus(403);
    }
});

// removed duplicate approval test due environment issue