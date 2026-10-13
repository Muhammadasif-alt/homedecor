@php $editing = $redirect->exists; @endphp

<x-admin.layout :title="$editing ? 'Edit redirect' : 'Add redirect'">
    <x-admin.page-header :title="$editing ? 'Edit redirect' : 'Add redirect'" :back="route('admin.redirects.index')" />

    <x-admin.form :action="$editing ? route('admin.redirects.update', $redirect) : route('admin.redirects.store')" :method="$editing ? 'PUT' : 'POST'" class="max-w-2xl space-y-6">
        <x-admin.card>
            <div class="grid gap-5">
                <x-admin.input name="from_path" label="Old path" :value="$redirect->from_path" required placeholder="/old-page"
                               hint="The path only — domains, query strings and trailing slashes are removed automatically." />
                <x-admin.input name="to_url" label="Redirect to" :value="$redirect->to_url" required placeholder="/new-page or https://…" />
                <x-admin.select name="status_code" label="Type" :options="[301 => '301 — Permanent (recommended)', 302 => '302 — Temporary']" :value="$redirect->status_code" />
            </div>
        </x-admin.card>
        <x-admin.form-actions :cancel="route('admin.redirects.index')" />
    </x-admin.form>
</x-admin.layout>
