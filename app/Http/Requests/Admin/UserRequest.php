<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Validator;

class UserRequest extends AdminRequest
{
    protected array $booleans = ['is_active'];

    public function rules(): array
    {
        $user = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
            'is_active' => ['boolean'],
            'password' => [$user ? 'nullable' : 'required', 'confirmed', Password::min(10)->letters()->numbers()],
        ];
    }

    /** You cannot lock yourself out: no self-deactivation or self-demotion. */
    public function after(): array
    {
        return [function (Validator $validator) {
            $user = $this->route('user');
            if (! $user || ! $user->is($this->user())) {
                return;
            }
            if (! $this->boolean('is_active')) {
                $validator->errors()->add('is_active', 'You cannot deactivate your own account.');
            }
            if ($this->input('role') !== 'admin') {
                $validator->errors()->add('role', 'You cannot remove your own administrator role.');
            }
        }];
    }

    protected function transform(array $data): array
    {
        if (blank($data['password'] ?? null)) {
            unset($data['password']); // keep the current password
        }

        return $data;
    }
}
