<?php

namespace App\Policies;

use App\Models\User;

/**
 * Content modules (pages, services, projects, media, blog, testimonials, FAQs,
 * inquiries, navigation, service areas) can be managed by any active admin or editor.
 */
abstract class ContentPolicy
{
    protected function canManage(User $user): bool
    {
        return $user->is_active && array_key_exists($user->role, User::ROLES);
    }

    public function viewAny(User $user): bool
    {
        return $this->canManage($user);
    }

    public function view(User $user, mixed $model = null): bool
    {
        return $this->canManage($user);
    }

    public function create(User $user): bool
    {
        return $this->canManage($user);
    }

    public function update(User $user, mixed $model = null): bool
    {
        return $this->canManage($user);
    }

    public function delete(User $user, mixed $model = null): bool
    {
        return $this->canManage($user);
    }

    public function restore(User $user, mixed $model = null): bool
    {
        return $this->canManage($user);
    }

    public function forceDelete(User $user, mixed $model = null): bool
    {
        return $user->isAdmin();
    }
}
