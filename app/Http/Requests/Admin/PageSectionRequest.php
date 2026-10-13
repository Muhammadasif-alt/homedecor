<?php

namespace App\Http\Requests\Admin;

use App\Http\Controllers\Admin\Support\SectionSchema;
use Illuminate\Validation\Validator;

class PageSectionRequest extends AdminRequest
{
    protected array $booleans = ['is_enabled'];

    private function type(): string
    {
        return $this->route('section')->type;
    }

    protected function prepare(): array
    {
        $data = (array) $this->input('data', []);
        foreach (SectionSchema::booleans($this->type()) as $key) {
            $data[$key] = filter_var($data[$key] ?? false, FILTER_VALIDATE_BOOLEAN);
        }

        return ['data' => $data];
    }

    public function rules(): array
    {
        return [
            'eyebrow' => ['nullable', 'string', 'max:255'],
            'heading' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable', 'string', 'max:20000'],
            'media_id' => $this->mediaRule(),
            'is_enabled' => ['boolean'],
            'data' => ['nullable', 'array'],
            'data_json' => ['nullable', 'json', 'max:20000'],
            ...SectionSchema::rules($this->type()),
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator) {
            $json = $this->input('data_json');
            if (filled($json) && ! is_array(json_decode($json, true))) {
                $validator->errors()->add('data_json', 'The advanced data must be a JSON object, e.g. {"key": "value"}.');
            }
        }];
    }

    public function payload(): array
    {
        $validated = $this->validated();

        return [
            'eyebrow' => $validated['eyebrow'] ?? null,
            'heading' => $validated['heading'] ?? null,
            'body' => filled($validated['body'] ?? null) ? static::purify($validated['body']) : null,
            'media_id' => $validated['media_id'] ?? null,
            'is_enabled' => $validated['is_enabled'],
            'data' => SectionSchema::build(
                $this->type(),
                $validated['data'] ?? [],
                filled($validated['data_json'] ?? null) ? (array) json_decode($validated['data_json'], true) : [],
            ),
        ];
    }
}
