<?php

namespace App\Http\Requests;

use App\Models\Inquiry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

/**
 * Validates both the contact form and the consultation form. The route decides the type.
 */
class InquiryRequest extends FormRequest
{
    /** Bots typically submit in under 3 seconds. */
    private const MIN_SECONDS_TO_SUBMIT = 3;

    public function authorize(): bool
    {
        return true;
    }

    public function isConsultation(): bool
    {
        return $this->routeIs('inquiries.consultation');
    }

    public function rules(): array
    {
        $common = [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'phone' => ['nullable', 'string', 'max:40', 'regex:/^[0-9+()\-\s.]{6,40}$/'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            'consent' => ['accepted'],
            'website' => ['prohibited'], // honeypot
            'form_started' => ['required', 'string'],
        ];

        if (! $this->isConsultation()) {
            return $common + ['subject' => ['nullable', 'string', 'max:150']];
        }

        return array_merge($common, [
            'phone' => ['required', 'string', 'max:40', 'regex:/^[0-9+()\-\s.]{6,40}$/'],
            'preferred_contact' => ['required', Rule::in(array_keys(Inquiry::CONTACT_METHODS))],
            'service_id' => ['nullable', Rule::exists('services', 'id')->where('is_published', true)],
            'property_type' => ['nullable', Rule::in(array_keys(Inquiry::PROPERTY_TYPES))],
            'location' => ['nullable', 'string', 'max:190'],
            'budget' => ['nullable', Rule::in(array_keys(Inquiry::BUDGETS))],
            'preferred_date' => ['nullable', 'date', 'after_or_equal:today', 'before:+1 year'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'mimetypes:image/jpeg,image/png,image/webp', 'max:8192'],
        ]);
    }

    public function messages(): array
    {
        return [
            'consent.accepted' => 'Please confirm you agree to our privacy policy so we can reply.',
            'phone.regex' => 'Please enter a valid phone number.',
            'attachments.*.mimetypes' => 'Photos must be JPG, PNG or WebP images.',
            'attachments.*.max' => 'Each photo must be 8 MB or smaller.',
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator) {
            $started = rescue(fn () => (int) decrypt($this->input('form_started')), 0, false);
            if (! $started || now()->timestamp - $started < self::MIN_SECONDS_TO_SUBMIT) {
                $validator->errors()->add('form', 'Sorry, we could not verify this submission. Please try again.');
            }
        }];
    }
}
