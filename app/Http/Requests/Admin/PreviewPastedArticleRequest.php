<?php

namespace App\Http\Requests\Admin;

class PreviewPastedArticleRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'text' => ['required', 'string', 'max:300000'],
            'post_id' => ['nullable', 'integer', 'exists:blog_posts,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'text.required' => 'Paste the article text first.',
            'text.max' => 'The pasted article is too long.',
            'post_id.exists' => 'That article no longer exists.',
        ];
    }
}
