<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class ServiceAreaRequest extends AdminRequest
{
    protected array $booleans = ['has_page', 'is_published'];

    protected array $richText = ['body'];

    protected ?string $slugFrom = 'name';

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('service_areas', 'slug')->ignore($this->route('serviceArea'))],
            'summary' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string', 'max:100000', 'required_if:has_page,true'],
            'has_page' => ['boolean'],
            'is_published' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
            ...$this->seoRules(canonical: false, og: false),
        ];
    }

    public function messages(): array
    {
        return ['body.required_if' => 'An area page needs its own unique content. Add the page text or untick “Has its own page”.'];
    }

    protected function transform(array $data): array
    {
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }
}
