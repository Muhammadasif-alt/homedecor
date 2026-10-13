<div>
    <label class="flex items-start gap-3 text-sm text-charcoal-soft">
        <input type="checkbox" name="consent" value="1" @checked(old('consent')) required
               class="mt-0.5 size-5 shrink-0 rounded-sm border-charcoal/30 accent-walnut">
        <span>I agree that my details may be used to respond to this enquiry, as described in the
            <a href="{{ url('privacy-policy') }}" class="text-walnut underline underline-offset-4">privacy policy</a>.</span>
    </label>
    @error('consent')<p class="mt-1.5 text-sm text-red-700">{{ $message }}</p>@enderror
</div>
