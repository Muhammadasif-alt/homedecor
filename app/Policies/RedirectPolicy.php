<?php

namespace App\Policies;

/**
 * Redirect records are managed by administrators only.
 */
class RedirectPolicy extends AdminOnlyPolicy
{
}
