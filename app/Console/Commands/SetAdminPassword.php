<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

/**
 * Sets an admin user's password from a hidden prompt, so it never appears in the command line,
 * shell history or chat. The site has no forgot-password page, so this is how the owner resets it.
 */
class SetAdminPassword extends Command
{
    protected $signature = 'admin:password {email : Login email of the admin user}';

    protected $description = 'Set a new password for an admin user (asked for without showing it)';

    private const MIN_LENGTH = 10;

    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->first();
        if (! $user) {
            $this->error('No user has that email.');

            return self::FAILURE;
        }

        $password = (string) $this->secret('New password (at least '.self::MIN_LENGTH.' characters, not shown as you type)');
        if (mb_strlen($password) < self::MIN_LENGTH) {
            $this->error('Too short. Nothing was changed.');

            return self::FAILURE;
        }

        if ($password !== (string) $this->secret('Type it again')) {
            $this->error('The two passwords did not match. Nothing was changed.');

            return self::FAILURE;
        }

        $user->update(['password' => $password]);
        $this->info("Password updated for {$user->email}.");

        return self::SUCCESS;
    }
}
