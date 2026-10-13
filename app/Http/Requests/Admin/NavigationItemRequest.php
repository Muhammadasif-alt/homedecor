<?php

namespace App\Http\Requests\Admin;

use App\Models\NavigationItem;
use Illuminate\Validation\Rule;

class NavigationItemRequest extends AdminRequest
{
    protected array $booleans = ['is_button', 'is_enabled'];

    public function rules(): array
    {
        return [
            'menu' => ['required', Rule::in(array_keys(NavigationItem::MENUS))],
            'label' => ['required', 'string', 'max:100'],
            // Relative path (/services) or absolute http(s) URL — never javascript: etc.
            'url' => ['required', 'string', 'max:255', 'regex:#^(/|https?://|mailto:|tel:)#i'],
            'is_button' => ['boolean'],
            'is_enabled' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
        ];
    }

    public function messages(): array
    {
        return ['url.regex' => 'Use a path starting with “/” (e.g. /services) or a full https:// address.'];
    }

    protected function transform(array $data): array
    {
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }
}
