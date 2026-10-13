<?php

namespace App\Http\Requests\Admin;

use App\Http\Controllers\Admin\Support\SettingsSchema;

class SettingRequest extends AdminRequest
{
    protected function prepare(): array
    {
        $bools = [];
        foreach (SettingsSchema::fields() as $key => $field) {
            if (($field['type'] ?? 'text') === 'bool') {
                $bools[$key] = $this->boolean($key);
            }
        }

        return $bools;
    }

    public function rules(): array
    {
        $rules = [];
        foreach (SettingsSchema::fields() as $key => $field) {
            $rules[$key] = match ($field['type'] ?? 'text') {
                'email' => ['nullable', 'email', 'max:255'],
                'tel' => ['nullable', 'string', 'max:40', 'regex:/^[0-9+()\s.-]{6,40}$/'],
                'url' => ['nullable', 'url:https,http', 'max:500'],
                'textarea' => ['nullable', 'string', 'max:2000'],
                'bool' => ['boolean'],
                'media' => $this->mediaRule(),
                default => ['nullable', 'string', 'max:255'],
            };
            if ($field['required'] ?? false) {
                $rules[$key][0] = 'required';
            }
        }

        $rules['map_embed_url'][] = 'starts_with:https://www.google.com/maps/embed,https://maps.google.com/';
        $rules['ga_measurement_id'][] = 'regex:/^G-[A-Z0-9]{4,20}$/i';

        return $rules + $this->rowRules('process_steps', SettingsSchema::PROCESS_FIELDS, 1000);
    }

    public function messages(): array
    {
        return [
            'map_embed_url.starts_with' => 'Paste the Google Maps “Embed a map” link (starts with https://www.google.com/maps/embed).',
            'ga_measurement_id.regex' => 'The measurement ID looks like G-XXXXXXXXXX.',
        ];
    }

    /** Values for Setting::put(): booleans as booleans, ids as ints, blanks as null. */
    protected function transform(array $data): array
    {
        $values = [];
        foreach (SettingsSchema::fields() as $key => $field) {
            $value = $data[$key] ?? null;
            $values[$key] = match ($field['type'] ?? 'text') {
                'bool' => (bool) $value,
                'media' => $value ? (int) $value : null,
                default => filled($value) ? trim((string) $value) : null,
            };
        }
        $values['process_steps'] = static::rows($data['process_steps'] ?? [], SettingsSchema::PROCESS_FIELDS);

        return $values;
    }
}
