<?php

namespace App\Http\Requests\Admin;

use App\Models\Faq;
use Illuminate\Validation\Rule;

class FaqRequest extends AdminRequest
{
    protected array $booleans = ['show_on_home', 'is_published'];

    public function rules(): array
    {
        return [
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string', 'max:5000'],
            'service_id' => ['nullable', 'integer', 'exists:services,id'],
            'category' => ['required', Rule::in(array_keys(Faq::CATEGORIES))],
            'show_on_home' => ['boolean'],
            'is_published' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
        ];
    }

    protected function transform(array $data): array
    {
        $data['answer'] = static::purify($data['answer']);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }
}
