<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;

class AdminController extends Controller
{
    public function index()
    {
        Gate::authorize('platform-admin');

        return response()->json(['ok' => true]);
    }
}
