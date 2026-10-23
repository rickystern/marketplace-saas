<?php

namespace App\Http\Controllers;

use App\Models\Marketplace;
use App\Models\MarketplaceApplication;
use App\Models\MarketplaceMembership;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AdminMarketplaceApplicationController extends Controller
{
    public function index()
    {
        Gate::authorize('review', new \App\Models\MarketplaceApplication());

        $applications = MarketplaceApplication::where('status', 'submitted')->latest()->get();

        return inertia('Admin/MarketplaceApplications/Index', ['applications' => $applications]);
    }

    public function approve(MarketplaceApplication $application)
    {
        Gate::authorize('review', $application);

        try {
            DB::transaction(function () use ($application) {
                $application = MarketplaceApplication::lockForUpdate()->findOrFail($application->id);

                if ($application->status !== 'submitted') {
                    throw new \RuntimeException('Application already reviewed.');
                }

                if (Marketplace::where('slug', $application->proposed_slug)->exists()) {
                    throw new \RuntimeException('Slug conflict.');
                }

                $marketplace = Marketplace::create([
                    'owner_id' => $application->applicant_id,
                    'name' => $application->proposed_name,
                    'slug' => $application->proposed_slug,
                    'status' => 'pending',
                ]);

                MarketplaceMembership::create([
                    'marketplace_id' => $marketplace->id,
                    'user_id' => $application->applicant_id,
                    'role' => MarketplaceMembership::ROLE_OWNER,
                    'status' => 'active',
                ]);

                $application->update([
                    'status' => 'approved',
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->withErrors(['application' => $e->getMessage()]);
        }

        return back();
    }

    public function reject(MarketplaceApplication $application)
    {
        Gate::authorize('review', $application);

        if ($application->status !== 'submitted') {
            return back()->withErrors(['application' => 'Application already reviewed.']);
        }

        $application->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'rejection_reason' => request('rejection_reason'),
        ]);

        return back();
    }
}
