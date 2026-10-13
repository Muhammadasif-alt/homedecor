<?php

namespace App\Policies;

use App\Models\User;

/**
 * System modules (settings, redirects) are reserved for active administrators.
 */
abstract class AdminOnlyPolicy
{
    protected function isAdmin(User $user): bool
    {
        return $user->is_active && $user->isAdmin();
    }

    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function view(User $user, mixed $model = null): bool
    {
        return $this->isAdmin($user);
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function update(User $user, mixed $model = null): bool
    {
        return $this->isAdmin($user);
    }

    public function delete(User $user, mixed $model = null): bool
    {
        return $this->isAdmin($user);
    }
}
