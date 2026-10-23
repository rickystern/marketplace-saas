<?php

use App\Models\User;

it('registers a user', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
});

it('validates registration', function () {
    $this->post('/register', [])->assertSessionHasErrors(['name', 'email', 'password']);
});

it('logs in and out', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();

    $this->post('/logout')->assertRedirect('/');
    $this->assertGuest();
});

it('rejects invalid login', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong',
    ])->assertSessionHasErrors('email');
});

it('protects dashboard', function () {
    $this->get('/dashboard')->assertRedirect(route('login'));
});

it('sends password reset link', function () {
    $user = User::factory()->create();

    $this->post('/forgot-password', ['email' => $user->email])
        ->assertSessionHas('status');
});
