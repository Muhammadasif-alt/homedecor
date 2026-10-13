<x-admin.layout title="Dashboard">
    <x-admin.page-header title="Welcome back, {{ strtok(auth()->user()->name, ' ') }}"
                         description="An overview of your website content and the latest enquiries." />

    <div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-5">
        @foreach ($stats as $stat)
            <a href="{{ route($stat['route'], $stat['query'] ?? []) }}"
               class="rounded-lg border border-charcoal/10 bg-white p-5 shadow-sm transition hover:border-sand hover:shadow focus-visible:outline-walnut">
                <p class="text-xs font-semibold uppercase tracking-wider text-charcoal-soft">{{ $stat['label'] }}</p>
                <p class="mt-2 font-serif text-4xl text-charcoal">{{ $stat['value'] }}</p>
            </a>
        @endforeach
    </div>

    <div class="grid items-start gap-6 lg:grid-cols-5">
        <x-admin.card title="Recent leads" class="lg:col-span-3" :padding="false">
            <x-slot:actions>
                <x-admin.button :href="route('admin.inquiries.index')" variant="secondary" size="sm">View all</x-admin.button>
            </x-slot:actions>
            <ul class="divide-y divide-charcoal/5">
                @forelse ($leads as $lead)
                    <li>
                        <a href="{{ route('admin.inquiries.show', $lead) }}" class="flex items-center gap-4 px-5 py-3 hover:bg-ivory/60 sm:px-6">
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold">{{ $lead->name }}</p>
                                <p class="truncate text-xs text-charcoal-soft">
                                    {{ ucfirst($lead->type) }}{{ $lead->service ? ' · '.$lead->service->name : '' }} · {{ $lead->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <x-admin.status-badge :status="$lead->status" :label="$lead->statusLabel()" />
                        </a>
                    </li>
                @empty
                    <li class="px-6 py-10 text-center text-sm text-charcoal-soft">No enquiries yet. They will appear here as soon as someone contacts you.</li>
                @endforelse
            </ul>
        </x-admin.card>

        @php $done = collect($checks)->where('ok', true)->count(); @endphp
        <x-admin.card title="Launch checklist" description="{{ $done }} of {{ count($checks) }} complete — fill these in before going live." class="lg:col-span-2" :padding="false">
            <ul class="divide-y divide-charcoal/5">
                @foreach ($checks as $check)
                    <li class="flex items-start gap-3 px-5 py-3 sm:px-6">
                        <span @class([
                            'mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full',
                            'bg-emerald-100 text-emerald-700' => $check['ok'],
                            'bg-amber-100 text-amber-700' => ! $check['ok'],
                        ])>
                            <x-admin.icon :name="$check['ok'] ? 'check' : 'alert'" class="size-3.5" />
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium">{{ $check['label'] }}</p>
                            @unless ($check['ok'])
                                <p class="text-xs text-charcoal-soft">{{ $check['hint'] }}</p>
                            @endunless
                        </div>
                        @if (! $check['ok'] && $check['route'] && \Illuminate\Support\Facades\Route::has($check['route'])
                             && (auth()->user()->isAdmin() || ! in_array($check['route'], ['admin.settings.edit', 'admin.users.index'])))
                            <a href="{{ route($check['route']) }}" class="shrink-0 text-xs font-semibold text-walnut hover:underline">Fix</a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </x-admin.card>
    </div>
</x-admin.layout>
