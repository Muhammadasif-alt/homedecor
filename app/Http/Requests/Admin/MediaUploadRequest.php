<?php

namespace App\Http\Requests\Admin;

use App\Models\Media;
use Illuminate\Validation\Rule;

/**
 * Image uploads: JPG/JPEG/JFIF, PNG, WebP up to 10 MB each. The MIME type is sniffed
 * from the file contents; ImageOptimizer re-checks the real image type with getimagesize().
 */
class MediaUploadRequest extends AdminRequest
{
    public const MAX_KB = 10240;

    public const FILE_RULES = ['required', 'file', 'max:'.self::MAX_KB, 'mimetypes:image/jpeg,image/png,image/webp', 'extensions:jpg,jpeg,jfif,png,webp'];

    public function rules(): array
    {
        return [
            'files' => ['required', 'array', 'min:1', 'max:20'],
            'files.*' => self::FILE_RULES,
            'category' => ['nullable', Rule::in(array_keys(Media::CATEGORIES))],
            'alt' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'files.*.max' => 'Each image must be 10 MB or smaller.',
            'files.*.mimetypes' => 'Only JPG, JFIF, PNG and WebP images are allowed.',
            'files.*.extensions' => 'Only .jpg, .jpeg, .jfif, .png and .webp files are allowed.',
        ];
    }
}
