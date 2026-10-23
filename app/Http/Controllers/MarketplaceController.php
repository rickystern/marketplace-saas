<?php

namespace App\Http\Controllers;

use App\Models\Marketplace;
use Illuminate\Support\Facades\Gate;

class MarketplaceController extends Controller
{
    public function show(Marketplace $marketplace)
    {
        Gate::authorize('view', $marketplace);

        return response()->json(['marketplace' => $marketplace->slug]);
    }
}
