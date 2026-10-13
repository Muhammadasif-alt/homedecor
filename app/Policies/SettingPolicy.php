<?php

namespace App\Policies;

/**
 * Setting records are managed by administrators only.
 */
class SettingPolicy extends AdminOnlyPolicy
{
}
