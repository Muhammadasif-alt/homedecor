<?php

namespace App\Http\Requests\Admin;

class InquiryNoteRequest extends AdminRequest
{
    public function rules(): array
    {
        return ['body' => ['required', 'string', 'max:5000']];
    }

    public function attributes(): array
    {
        return ['body' => 'note'];
    }
}
