{{-- Honeypot + signed start time; validated in App\Http\Requests\InquiryRequest. --}}
<div class="absolute -left-[9999px]" aria-hidden="true">
    <label for="website">Leave this field empty</label>
    <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
</div>
<input type="hidden" name="form_started" value="{{ encrypt(now()->timestamp) }}">
@error('form')
    <p class="rounded-sm bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">{{ $message }}</p>
@enderror
