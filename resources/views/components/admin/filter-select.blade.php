@props(['name', 'options' => [], 'placeholder' => 'All'])

<label class="sm:w-48">
    <span class="sr-only">{{ $placeholder }}</span>
    <select name="{{ $name }}" class="block w-full rounded border border-charcoal/15 bg-white px-3 py-2 text-sm focus:border-walnut focus:outline-none focus:ring-2 focus:ring-walnut/25">
        <option value="">{{ $placeholder }}</option>
        @foreach ($options as $value => $label)
            <option value="{{ $value }}" @selected((string) request($name) === (string) $value)>{{ $label }}</option>
        @endforeach
    </select>
</label>
