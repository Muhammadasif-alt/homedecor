<?php

namespace App\Http\Requests\Admin;

use App\Models\Media;
use Illuminate\Validation\Rule;

class MediaRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'alt' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', Rule::in(array_keys(Media::CATEGORIES))],
        ];
    }
}
