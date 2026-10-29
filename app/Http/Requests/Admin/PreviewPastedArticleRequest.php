<?php

namespace App\Http\Requests\Admin;

class PreviewPastedArticleRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'text' => ['required', 'string', 'max:300000'],
            'post_id' => ['nullable', 'integer', 'exists:blog_posts,id'],
            'pasted' => ['nullable', 'array', 'max:100'],
            'pasted.*.media_id' => ['required', 'integer', 'exists:media,id'],
            'pasted.*.alt' => ['required', 'string', 'max:255'],
            'pasted.*.caption' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'text.required' => 'Paste the article text first.',
            'text.max' => 'The pasted article is too long.',
            'post_id.exists' => 'That article no longer exists.',
            'pasted.*.media_id.exists' => 'A pasted photo is no longer in the media library. Paste the article again.',
        ];
    }
}
