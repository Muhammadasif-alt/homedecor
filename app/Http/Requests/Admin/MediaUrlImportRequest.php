<?php

namespace App\Http\Requests\Admin;

/** A pasted image given by its web address, imported into the media library. */
class MediaUrlImportRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'url' => ['required', 'url:http,https', 'max:4000'],
            'alt' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'url.required' => 'The pasted image has no web address.',
            'url.url' => 'The pasted image address is not a web link.',
        ];
    }
}
