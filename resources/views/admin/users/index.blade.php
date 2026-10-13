<x-admin.layout title="Users">
    <x-admin.page-header title="Users" description="Administrators manage everything; editors manage content and leads."
                         :action="route('admin.users.create')" action-label="Add user" />

    <x-admin.filters :action="route('admin.users.index')" placeholder="Search name or email…">
        <x-admin.filter-select name="role" :options="\App\Models\User::ROLES" placeholder="All roles" />
    </x-admin.filters>

    <x-admin.table :headers="['Name', 'Email', 'Role', 'Status', '']" :paginator="$users" empty="No users found.">
        @foreach ($users as $user)
            <tr>
                <td>
                    <a href="{{ route('admin.users.edit', $user) }}" class="font-semibold hover:text-walnut">{{ $user->name }}</a>
                    @if ($user->is(auth()->user()))<span class="ml-1 text-xs text-charcoal-soft">(you)</span>@endif
                </td>
                <td class="text-charcoal-soft">{{ $user->email }}</td>
                <td>{{ \App\Models\User::ROLES[$user->role] ?? $user->role }}</td>
                <td><x-admin.status-badge :status="$user->is_active ? 'active' : 'inactive'" /></td>
                <x-admin.row-actions>
                    <x-admin.icon-link :href="route('admin.users.edit', $user)" label="Edit {{ $user->name }}" />
                    @can('delete', $user)
                        <x-admin.delete-button :action="route('admin.users.destroy', $user)" label="Delete {{ $user->name }}"
                                               confirm="Delete this account? Their notes and articles are kept." />
                    @endcan
                </x-admin.row-actions>
            </tr>
        @endforeach
    </x-admin.table>
</x-admin.layout>
