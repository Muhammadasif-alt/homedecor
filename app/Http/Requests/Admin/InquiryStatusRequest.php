<?php

namespace App\Http\Requests\Admin;

use App\Models\Inquiry;
use Illuminate\Validation\Rule;

class InquiryStatusRequest extends AdminRequest
{
    public function rules(): array
    {
        return ['status' => ['required', Rule::in(array_keys(Inquiry::STATUSES))]];
    }
}
