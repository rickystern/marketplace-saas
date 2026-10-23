<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMarketplaceApplicationRequest;
use App\Models\MarketplaceApplication;
use Illuminate\Support\Facades\Gate;

class MarketplaceApplicationController extends Controller
{
    public function index()
    {
        $applications = auth()->user()->marketplaceApplications()->latest()->get();

        return inertia('MarketplaceApplications/Index', ['applications' => $applications]);
    }

    public function create()
    {
        return inertia('MarketplaceApplications/Create');
    }

    public function store(StoreMarketplaceApplicationRequest $request)
    {
        $user = $request->user();

        $pendingExists = MarketplaceApplication::where('applicant_id', $user->id)
            ->where('proposed_slug', $request->proposed_slug)
            ->where('status', 'submitted')
            ->exists();

        if ($pendingExists) {
            return back()->withErrors(['proposed_slug' => 'A pending application for this slug already exists.']);
        }

        MarketplaceApplication::create([
            'applicant_id' => $user->id,
            'proposed_name' => $request->proposed_name,
            'proposed_slug' => $request->proposed_slug,
            'status' => 'submitted',
        ]);

        return redirect()->route('marketplace-applications.index');
    }

    public function show(MarketplaceApplication $marketplaceApplication)
    {
        Gate::authorize('view', $marketplaceApplication);

        return inertia('MarketplaceApplications/Show', ['application' => $marketplaceApplication]);
    }
}
