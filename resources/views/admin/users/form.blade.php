@php
    $editing = $user->exists;
    $self = $editing && $user->is(auth()->user());
@endphp

<x-admin.layout :title="$editing ? 'Edit user' : 'Add user'">
    <x-admin.page-header :title="$editing ? $user->name : 'Add user'" :back="route('admin.users.index')" />

    <x-admin.form :action="$editing ? route('admin.users.update', $user) : route('admin.users.store')" :method="$editing ? 'PUT' : 'POST'" class="max-w-3xl space-y-6">
        <x-admin.card title="Account">
            <div class="grid gap-5 sm:grid-cols-2">
                <x-admin.input name="name" label="Name" :value="$user->name" required autocomplete="off" />
                <x-admin.input name="email" type="email" label="Email" :value="$user->email" required autocomplete="off" />
                <x-admin.select name="role" label="Role" :options="\App\Models\User::ROLES" :value="$user->role"
                                hint="Editors can manage content and leads, but not users, settings or redirects." />
                <div class="flex items-end pb-2">
                    @if ($self)
                        <input type="hidden" name="is_active" value="1">
                        <p class="text-sm text-charcoal-soft">You cannot deactivate your own account.</p>
                    @else
                        <x-admin.checkbox name="is_active" label="Active" :checked="$user->is_active" hint="Inactive users are signed out and cannot log in." />
                    @endif
                </div>
            </div>
        </x-admin.card>

        <x-admin.card :title="$editing ? 'Change password' : 'Password'" :description="$editing ? 'Leave blank to keep the current password.' : 'At least 10 characters, with letters and numbers.'">
            <div class="grid gap-5 sm:grid-cols-2">
                <x-admin.input name="password" type="password" label="Password" :required="! $editing" autocomplete="new-password" />
                <x-admin.input name="password_confirmation" type="password" label="Confirm password" :required="! $editing" autocomplete="new-password" />
            </div>
        </x-admin.card>

        <x-admin.form-actions :cancel="route('admin.users.index')" :label="$editing ? 'Save account' : 'Create account'" />
    </x-admin.form>
</x-admin.layout>
