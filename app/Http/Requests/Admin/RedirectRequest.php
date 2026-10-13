<?php

namespace App\Http\Requests\Admin;

use App\Models\Redirect;
use Illuminate\Validation\Rule;

class RedirectRequest extends AdminRequest
{
    protected function prepare(): array
    {
        return filled($this->input('from_path'))
            ? ['from_path' => Redirect::normalize((string) $this->input('from_path'))]
            : [];
    }

    public function rules(): array
    {
        return [
            'from_path' => ['required', 'string', 'max:255', 'not_in:/', 'not_regex:#^/admin(/|$)#i',
                Rule::unique('redirects', 'from_path')->ignore($this->route('redirect'))],
            'to_url' => ['required', 'string', 'max:255', 'regex:#^(/|https?://)#i', 'different:from_path'],
            'status_code' => ['required', 'integer', Rule::in([301, 302])],
        ];
    }

    public function messages(): array
    {
        return [
            'from_path.not_in' => 'The home page cannot be redirected.',
            'from_path.not_regex' => 'Admin URLs cannot be redirected.',
            'to_url.regex' => 'Use a path starting with “/” or a full https:// URL.',
        ];
    }
}
