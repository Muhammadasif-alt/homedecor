<?php

namespace App\Http\Requests\Admin;

class TestimonialRequest extends AdminRequest
{
    protected array $booleans = ['is_approved'];

    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:3000'],
            'rating' => ['nullable', 'integer', 'between:1,5'],
            'service_id' => ['nullable', 'integer', 'exists:services,id'],
            'project_id' => ['nullable', 'integer', 'exists:projects,id'],
            'photo_media_id' => $this->mediaRule(),
            'is_approved' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
        ];
    }

    protected function transform(array $data): array
    {
        // Plain text only: strip any markup pasted in.
        $data['content'] = trim(strip_tags($data['content']));
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }
}
