<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends ResourceController
{
    protected static string $model = User::class;

    protected static string $parameter = 'user';

    public function index(Request $request): View
    {
        $users = User::query()
            ->when($request->query('q'), fn ($q, $term) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$term}%")->orWhere('email', 'like', "%{$term}%")))
            ->when($request->query('role'), fn ($q, $role) => $q->where('role', $role))
            ->orderBy('name')
            ->paginate(25)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.form', ['user' => new User(['role' => 'editor', 'is_active' => true])]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $user = User::create($request->payload());

        return redirect()->route('admin.users.index')->with('success', "Account for {$user->name} created.");
    }

    public function edit(User $user): View
    {
        return view('admin.users.form', compact('user'));
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $user->update($request->payload());

        return redirect()->route('admin.users.index')->with('success', 'Account saved.');
    }

    /** Policy blocks deleting yourself. */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Account deleted.');
    }
}
