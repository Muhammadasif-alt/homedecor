<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Creates or updates the administrator account from ADMIN_EMAIL / ADMIN_PASSWORD (and optional ADMIN_NAME).
 * No credentials are ever hardcoded.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (! $email || ! $password) {
            $this->command?->warn('AdminUserSeeder skipped: set ADMIN_EMAIL and ADMIN_PASSWORD in .env to create the admin account.');

            return;
        }

        User::updateOrCreate(['email' => $email], [
            'name' => env('ADMIN_NAME', 'Administrator'),
            'password' => $password, // hashed by the model cast
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->command?->info("Admin user ready: {$email}");
    }
}
