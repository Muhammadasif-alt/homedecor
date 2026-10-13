<?php

namespace App\Http\Controllers\Admin\Support;

use App\Http\Requests\Admin\AdminRequest;
use App\Models\Media;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

/**
 * Describes the editable `data` keys of each page section type (see docs/ARCHITECTURE.md),
 * so the admin can render type-aware forms, validate them and shape the saved JSON.
 * Keys not described here are edited through the "advanced JSON" textarea.
 */
class SectionSchema
{
    /** Section types whose `media_id` (main image) is rendered. */
    public const MEDIA_TYPES = ['hero', 'text_image', 'cta'];

    /** Section types whose body is long-form rich text. */
    public const RICH_BODY_TYPES = ['text_image'];

    public const BUTTON_STYLES = ['primary' => 'Primary', 'outline' => 'Outline', 'light' => 'Light (on dark images)'];

    public static function fields(string $type): array
    {
        $limit = fn (string $hint = 'Leave blank to show all.') => ['type' => 'int', 'label' => 'Number of items', 'hint' => $hint];
        $featured = ['type' => 'bool', 'label' => 'Featured items only'];
        $buttons = ['type' => 'items', 'label' => 'Buttons', 'add' => 'Add button', 'item' => 'Button', 'fields' => [
            'label' => 'Label',
            'url' => ['label' => 'Link (e.g. /book-consultation)', 'placeholder' => '/contact'],
            'style' => ['label' => 'Style', 'type' => 'select', 'options' => self::BUTTON_STYLES],
        ]];

        return match ($type) {
            'announcement' => [
                'link_label' => ['type' => 'text', 'label' => 'Link label'],
                'link_url' => ['type' => 'text', 'label' => 'Link URL'],
            ],
            'hero' => [
                'buttons' => $buttons,
                'size' => ['type' => 'select', 'label' => 'Height', 'options' => ['full' => 'Full screen (home)', 'page' => 'Page header']],
            ],
            'stats' => [
                'items' => ['type' => 'items', 'label' => 'Figures', 'add' => 'Add figure', 'item' => 'Figure',
                    'hint' => 'Verified figures only — the section is hidden when empty.',
                    'fields' => ['value' => 'Value (e.g. 120+)', 'label' => 'Label']],
            ],
            'text_image' => [
                'image_position' => ['type' => 'select', 'label' => 'Image position', 'options' => ['right' => 'Right', 'left' => 'Left']],
                'bullets' => ['type' => 'lines', 'label' => 'Bullet points', 'hint' => 'One per line.'],
                'button' => ['type' => 'link', 'label' => 'Button'],
            ],
            'services', 'projects' => ['limit' => $limit(), 'featured_only' => $featured],
            'before_after' => ['limit' => $limit('Defaults to 2.')],
            'features' => [
                'items' => ['type' => 'items', 'label' => 'Features', 'add' => 'Add feature', 'item' => 'Feature', 'fields' => [
                    'icon' => ['label' => 'Icon name', 'placeholder' => 'e.g. sparkles'],
                    'title' => 'Title',
                    'text' => ['label' => 'Text', 'type' => 'textarea'],
                ]],
                'columns' => ['type' => 'select', 'label' => 'Columns', 'options' => ['2' => '2', '3' => '3', '4' => '4'], 'cast' => 'int'],
            ],
            'process' => [
                'layout' => ['type' => 'select', 'label' => 'Layout', 'options' => ['grid' => 'Grid', 'timeline' => 'Timeline'],
                    'hint' => 'Steps are edited in Settings → Process steps.'],
            ],
            'styles' => [
                'items' => ['type' => 'items', 'label' => 'Styles', 'add' => 'Add style', 'item' => 'Style', 'fields' => [
                    'title' => 'Title',
                    'text' => ['label' => 'Text', 'type' => 'textarea'],
                    'media_id' => ['label' => 'Image', 'type' => 'media'],
                ]],
            ],
            'gallery' => [
                'media_ids' => ['type' => 'gallery', 'label' => 'Hand-picked images', 'hint' => 'When empty, images are taken from the category below.'],
                'category' => ['type' => 'select', 'label' => 'Media category', 'options' => Media::CATEGORIES],
                'limit' => $limit('Defaults to 9.'),
            ],
            'portfolio' => ['page_size' => ['type' => 'int', 'label' => 'Projects per page']],
            'testimonials' => ['limit' => $limit()],
            'blog' => ['limit' => $limit('Defaults to 3.')],
            'faqs' => [
                'scope' => ['type' => 'select', 'label' => 'Questions shown', 'options' => ['home' => 'Only “show on home” FAQs', 'all' => 'All general FAQs, grouped by category']],
            ],
            'team' => [
                'items' => ['type' => 'items', 'label' => 'Team members', 'add' => 'Add person', 'item' => 'Person',
                    'hint' => 'Real people only — the section is hidden when empty.',
                    'fields' => [
                        'name' => 'Name',
                        'role' => 'Role',
                        'bio' => ['label' => 'Short bio', 'type' => 'textarea'],
                        'media_id' => ['label' => 'Photo', 'type' => 'media'],
                    ]],
            ],
            'cta' => ['buttons' => $buttons],
            default => [],
        };
    }

    /** Validation rules for the `data.*` keys of a type. */
    public static function rules(string $type): array
    {
        $rules = [];
        foreach (self::fields($type) as $key => $field) {
            $path = "data.{$key}";
            match ($field['type']) {
                'text' => $rules[$path] = ['nullable', 'string', 'max:255'],
                'int' => $rules[$path] = ['nullable', 'integer', 'min:1', 'max:200'],
                'bool' => $rules[$path] = ['boolean'],
                'select' => $rules[$path] = ['nullable', Rule::in(array_map('strval', array_keys($field['options'])))],
                'lines' => $rules[$path] = ['nullable', 'string', 'max:5000'],
                'link' => $rules += [$path => ['nullable', 'array'], "{$path}.label" => ['nullable', 'string', 'max:100'], "{$path}.url" => ['nullable', 'string', 'max:255']],
                'gallery' => $rules += [$path => ['nullable', 'array', 'max:60'], "{$path}.*" => ['integer', 'exists:media,id']],
                'items' => $rules += self::itemRules($path, $field['fields']),
            };
        }

        return $rules;
    }

    /** Booleans that must be normalised (unchecked = false). */
    public static function booleans(string $type): array
    {
        return array_keys(array_filter(self::fields($type), fn ($f) => $f['type'] === 'bool'));
    }

    /**
     * Build the stored JSON: structured keys (cast + cleaned) win over extra keys from the advanced JSON box.
     */
    public static function build(string $type, array $input, array $extra = []): array
    {
        $fields = self::fields($type);
        $data = Arr::except($extra, array_keys($fields));

        foreach ($fields as $key => $field) {
            $value = $input[$key] ?? null;
            $value = match ($field['type']) {
                'int' => filled($value) ? (int) $value : null,
                'bool' => (bool) $value,
                'select' => filled($value) ? (($field['cast'] ?? null) === 'int' ? (int) $value : $value) : null,
                'lines' => collect(preg_split('/\r\n|\r|\n/', (string) $value))->map(fn ($l) => trim($l))->filter()->values()->all(),
                'link' => filled($value['label'] ?? null) || filled($value['url'] ?? null)
                    ? ['label' => (string) ($value['label'] ?? ''), 'url' => (string) ($value['url'] ?? '')] : null,
                'gallery' => collect($value ?? [])->filter()->map(fn ($id) => (int) $id)->unique()->values()->all(),
                'items' => AdminRequest::rows($value, array_keys($field['fields'])),
                default => filled($value) ? $value : null,
            };

            if ($value !== null && $value !== []) {
                $data[$key] = $value;
            }
        }

        return $data;
    }

    /** Value shown in the form for a structured key. */
    public static function formValue(array $field, mixed $value): mixed
    {
        return match ($field['type']) {
            'lines' => implode("\n", (array) $value),
            default => $value,
        };
    }

    /** Data keys not covered by the structured form (shown as JSON). */
    public static function extra(string $type, ?array $data): array
    {
        return Arr::except($data ?? [], array_keys(self::fields($type)));
    }

    private static function itemRules(string $path, array $fields): array
    {
        $rules = [$path => ['nullable', 'array', 'max:50']];
        foreach ($fields as $key => $config) {
            $type = is_array($config) ? ($config['type'] ?? 'text') : 'text';
            $rules["{$path}.*.{$key}"] = match ($type) {
                'media' => ['nullable', 'integer', 'exists:media,id'],
                'select' => ['nullable', Rule::in(array_keys($config['options']))],
                'textarea' => ['nullable', 'string', 'max:2000'],
                default => ['nullable', 'string', 'max:255'],
            };
        }

        return $rules;
    }
}
