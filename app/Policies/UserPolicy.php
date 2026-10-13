<?php

namespace App\Policies;

use App\Models\User;

/**
 * Only administrators manage accounts, and nobody can delete or deactivate themselves.
 */
class UserPolicy extends AdminOnlyPolicy
{
    public function delete(User $user, mixed $model = null): bool
    {
        return $this->isAdmin($user) && ! ($model instanceof User && $model->is($user));
    }

    public function deactivate(User $user, User $model): bool
    {
        return $this->isAdmin($user) && ! $model->is($user);
    }
}
