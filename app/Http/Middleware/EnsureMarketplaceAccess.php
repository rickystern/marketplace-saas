<?php

namespace App\Http\Middleware;

use App\Models\Marketplace;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMarketplaceAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $marketplace = $request->route('marketplace');

        if (! $marketplace instanceof Marketplace) {
            abort(404);
        }

        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        $membership = $marketplace->memberships()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (! $membership || $marketplace->status !== 'active') {
            abort(403);
        }

        $request->attributes->set('marketplace', $marketplace);

        return $next($request);
    }
}
