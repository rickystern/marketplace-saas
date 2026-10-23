<?php

use App\Models\Marketplace;
use App\Models\MarketplaceApplication;
use App\Models\MarketplaceMembership;
use App\Models\User;

it('allows authenticated user to submit valid application', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/marketplace-applications', [
            'proposed_name' => 'My Market',
            'proposed_slug' => 'my-market',
        ])
        ->assertRedirect('/marketplace-applications');

    expect(MarketplaceApplication::where('applicant_id', $user->id)->count())->toBe(1);
});

it('denies unauthenticated application submission', function () {
    $this->post('/marketplace-applications', [
        'proposed_name' => 'My Market',
        'proposed_slug' => 'my-market',
    ])->assertRedirect('/login');
});

it('rejects invalid application data', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/marketplace-applications', [
            'proposed_name' => '',
            'proposed_slug' => 'BAD SLUG',
        ])
        ->assertSessionHasErrors(['proposed_name', 'proposed_slug']);
});

it('ignores applicant-submitted privileged fields', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/marketplace-applications', [
            'proposed_name' => 'My Market',
            'proposed_slug' => 'my-market',
            'status' => 'approved',
            'reviewed_by' => 999,
            'reviewed_at' => now(),
        ]);

    $application = MarketplaceApplication::first();

    expect($application->status)->toBe('submitted');
    expect($application->reviewed_by)->toBeNull();
    expect($application->reviewed_at)->toBeNull();
});

it('lets user view own applications but not others', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();

    $app = MarketplaceApplication::create([
        'applicant_id' => $other->id,
        'proposed_name' => 'Other',
        'proposed_slug' => 'other',
        'status' => 'submitted',
    ]);

it('denies non-admin admin list', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->get('/admin/marketplace-applications')->assertStatus(403);
});

it('allows admin to view pending applications', function () {
    $admin = User::factory()->create(['is_platform_admin' => true]);
    $this->actingAs($admin)->get('/admin/marketplace-applications')->assertOk();
});

it('allows admin to approve pending application', function () {
    $applicant = User::factory()->create();
    $admin = User::factory()->create(['is_platform_admin' => true]);
    $application = MarketplaceApplication::create([
        'applicant_id' => $applicant->id,
        'proposed_name' => 'My Market',
        'proposed_slug' => 'my-market',
        'status' => 'submitted',
    ]);

    $this->actingAs($admin)
        ->post("/admin/marketplace-applications/{$application->id}/approve")
        ->assertRedirect();

    $application->refresh();
    expect($application->status)->toBe('approved');
    expect($application->reviewed_by)->toBe($admin->id);
    expect($application->reviewed_at)->not->toBeNull();
});

it('approval creates pending marketplace and owner membership', function () {
    $applicant = User::factory()->create();
    $admin = User::factory()->create(['is_platform_admin' => true]);
    $application = MarketplaceApplication::create([
        'applicant_id' => $applicant->id,
        'proposed_name' => 'My Market',
        'proposed_slug' => 'my-market',
        'status' => 'submitted',
    ]);

    $this->actingAs($admin)
        ->post("/admin/marketplace-applications/{$application->id}/approve");

    $marketplace = Marketplace::where('slug', 'my-market')->first();
    expect($marketplace)->not->toBeNull();
    expect($marketplace->status)->toBe('pending');
    expect($marketplace->owner_id)->toBe($applicant->id);

    $membership = MarketplaceMembership::where('marketplace_id', $marketplace->id)->where('user_id', $applicant->id)->first();
    expect($membership)->not->toBeNull();
    expect($membership->role)->toBe('owner');
    expect($membership->status)->toBe('active');
});

it('allows admin to reject pending application', function () {
    $applicant = User::factory()->create();
    $admin = User::factory()->create(['is_platform_admin' => true]);
    $application = MarketplaceApplication::create([
        'applicant_id' => $applicant->id,
        'proposed_name' => 'My Market',
        'proposed_slug' => 'my-market',
        'status' => 'submitted',
    ]);

    $this->actingAs($admin)
        ->post("/admin/marketplace-applications/{$application->id}/reject", [
            'rejection_reason' => 'Not suitable',
        ])
        ->assertRedirect();

    $application->refresh();
    expect($application->status)->toBe('rejected');
    expect($application->rejection_reason)->toBe('Not suitable');
    expect(Marketplace::where('slug', 'my-market')->exists())->toBeFalse();
});

it('prevents double review', function () {
    $applicant = User::factory()->create();
    $admin = User::factory()->create(['is_platform_admin' => true]);
    $application = MarketplaceApplication::create([
        'applicant_id' => $applicant->id,
        'proposed_name' => 'My Market',
        'proposed_slug' => 'my-market',
        'status' => 'approved',
        'reviewed_by' => $admin->id,
        'reviewed_at' => now(),
    ]);

    $this->actingAs($admin)
        ->post("/admin/marketplace-applications/{$application->id}/approve")
        ->assertSessionHasErrors();
});

it('slug conflict does not modify existing marketplace', function () {
    $applicant = User::factory()->create();
    $admin = User::factory()->create(['is_platform_admin' => true]);
    $existingOwner = User::factory()->create();
    $existing = Marketplace::create([
        'owner_id' => $existingOwner->id,
        'name' => 'Existing',
        'slug' => 'conflict-slug',
        'status' => 'pending',
    ]);
    $application = MarketplaceApplication::create([
        'applicant_id' => $applicant->id,
        'proposed_name' => 'Conflict',
        'proposed_slug' => 'conflict-slug',
        'status' => 'submitted',
    ]);

    $this->actingAs($admin)
        ->post("/admin/marketplace-applications/{$application->id}/approve")
        ->assertSessionHasErrors();

    $existing->refresh();
    expect($existing->owner_id)->toBe($existingOwner->id);
    expect(Marketplace::where('slug', 'conflict-slug')->count())->toBe(1);
});

it('pending marketplace remains inaccessible via ordinary route', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $marketplace = Marketplace::create([
        'owner_id' => $owner->id,
        'name' => 'Pending Market',
        'slug' => 'pending-market',
        'status' => 'pending',
    ]);
    MarketplaceMembership::create([
        'marketplace_id' => $marketplace->id,
        'user_id' => $member->id,
        'status' => 'active',
    ]);

    $this->actingAs($member)
        ->get('/marketplaces/pending-market')
        ->assertStatus(403);
});


    $this->actingAs($user)->get("/marketplace-applications/{$app->id}")->assertStatus(403);
});

it('prevents duplicate pending slug applications', function () {
    $user = User::factory()->create();

    MarketplaceApplication::create([
        'applicant_id' => $user->id,
        'proposed_name' => 'My Market',
        'proposed_slug' => 'my-market',
        'status' => 'submitted',
    ]);

    $this->actingAs($user)
        ->post('/marketplace-applications', [
            'proposed_name' => 'My Market 2',
            'proposed_slug' => 'my-market',
        ])
        ->assertSessionHasErrors(['proposed_slug']);
});
