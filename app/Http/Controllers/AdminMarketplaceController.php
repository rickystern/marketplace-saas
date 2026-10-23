<?php

namespace App\Http\Controllers;

use App\Models\Marketplace;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AdminMarketplaceController extends Controller
{
    public function index()
    {
        Gate::authorize('platform-admin');

        $marketplaces = Marketplace::latest()->get();

        return inertia('Admin/Marketplaces/Index', ['marketplaces' => $marketplaces]);
    }

    public function activate(Marketplace $marketplace)
    {
        Gate::authorize('platform-admin');

        return $this->transition($marketplace, 'pending', 'active');
    }

    public function suspend(Marketplace $marketplace)
    {
        Gate::authorize('platform-admin');

        return $this->transition($marketplace, 'active', 'suspended');
    }

    public function reactivate(Marketplace $marketplace)
    {
        Gate::authorize('platform-admin');

        return $this->transition($marketplace, 'suspended', 'active');
    }

    private function transition(Marketplace $marketplace, string $from, string $to)
    {
        try {
            DB::transaction(function () use ($marketplace, $from, $to) {
                $locked = Marketplace::lockForUpdate()->findOrFail($marketplace->id);

                if ($locked->status !== $from) {
                    throw new \RuntimeException('Invalid marketplace status transition.');
                }

                $locked->update(['status' => $to]);
            });
        } catch (\RuntimeException $e) {
            return back()->withErrors(['marketplace' => $e->getMessage()]);
        }

        return back();
    }
}