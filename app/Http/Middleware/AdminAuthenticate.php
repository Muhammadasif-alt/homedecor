<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;

/**
 * Session auth for the admin area: guests are sent to the admin login form
 * (no dependency on a global "login" route name).
 */
class AdminAuthenticate extends Authenticate
{
    protected function redirectTo(Request $request): ?string
    {
        return route('admin.login');
    }
}
