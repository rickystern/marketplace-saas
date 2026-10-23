<?php

namespace App\Policies;

use App\Models\MarketplaceApplication;
use App\Models\User;

class MarketplaceApplicationPolicy
{
    public function view(User $user, MarketplaceApplication $application): bool
    {
        return $user->id === $application->applicant_id || $user->is_platform_admin;
    }

    public function review(User $user): bool
    {
        return $user->is_platform_admin;
    }
}
