<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Stevebauman\Purify\Facades\Purify;

/**
 * Base form request for the admin area.
 *
 * Authorization is enforced by policy middleware on the controller, so requests only
 * normalise and validate input. Controllers persist `payload()` — validated data with
 * rich text purified — never raw request input.
 */
abstract class AdminRequest extends FormRequest
{
    /** Checkbox fields: missing means false. */
    protected array $booleans = [];

    /** Rich-text (HTML) fields purified before saving. */
    protected array $richText = [];

    /** When set, an empty `slug` is generated from this field and every slug is normalised. */
    protected ?string $slugFrom = null;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $merge = [];
        foreach ($this->booleans as $field) {
            $merge[$field] = $this->boolean($field);
        }
        if ($this->slugFrom) {
            $merge['slug'] = Str::slug((string) ($this->input('slug') ?: $this->input($this->slugFrom)));
        }

        $this->merge($merge + $this->prepare());
    }

    /** Extra normalisation hook for subclasses (merged into the input). */
    protected function prepare(): array
    {
        return [];
    }

    /** Validated, purified data ready for mass assignment. */
    public function payload(): array
    {
        $data = $this->validated();
        foreach ($this->richText as $field) {
            if (array_key_exists($field, $data)) {
                $data[$field] = static::purify($data[$field]);
            }
        }

        return $this->transform($data);
    }

    /** Final shaping hook for subclasses (e.g. cleaning repeater rows). */
    protected function transform(array $data): array
    {
        return $data;
    }

    /**
     * Sanitise editor HTML. Trix's "Heading" button emits <h1>, but templates own the page H1,
     * so headings are demoted to <h2> before purifying (the purifier config disallows h1).
     */
    public static function purify(?string $html): string
    {
        $html = preg_replace(['/<h1(\s[^>]*)?>/i', '/<\/h1\s*>/i'], ['<h2>', '</h2>'], (string) $html);

        return trim(Purify::clean($html));
    }

    /**
     * Keep only the given keys of each repeater row, trim strings, cast media ids
     * and drop rows that are completely empty.
     */
    public static function rows(?array $rows, array $fields): array
    {
        return collect($rows ?? [])
            ->map(function ($row) use ($fields) {
                $clean = [];
                foreach ($fields as $field) {
                    $value = is_array($row) ? ($row[$field] ?? null) : null;
                    $value = is_string($value) ? trim($value) : $value;
                    if (str_ends_with($field, 'media_id')) {
                        $value = $value ? (int) $value : null;
                    }
                    $clean[$field] = $value === '' ? null : $value;
                }

                return $clean;
            })
            ->filter(fn ($row) => collect($row)->filter(fn ($v) => $v !== null)->isNotEmpty())
            ->values()
            ->all();
    }

    /** Validation rules for a repeater whose rows hold the given string fields. */
    protected function rowRules(string $name, array $fields, int $max = 1000): array
    {
        $rules = [$name => ['nullable', 'array', 'max:50']];
        foreach ($fields as $field) {
            $rules["{$name}.*.{$field}"] = str_ends_with($field, 'media_id')
                ? ['nullable', 'integer', 'exists:media,id']
                : ['nullable', 'string', "max:{$max}"];
        }

        return $rules;
    }

    /** Shared SEO field rules. */
    protected function seoRules(bool $canonical = true, bool $og = true, bool $robots = false): array
    {
        return array_filter([
            'meta_title' => ['nullable', 'string', 'max:120'],
            'meta_description' => ['nullable', 'string', 'max:320'],
            'canonical_url' => $canonical ? ['nullable', 'url:http,https', 'max:255'] : null,
            'og_media_id' => $og ? ['nullable', 'integer', 'exists:media,id'] : null,
            'robots_index' => $robots ? ['boolean'] : null,
        ]);
    }

    protected function mediaRule(): array
    {
        return ['nullable', 'integer', 'exists:media,id'];
    }

    public function attributes(): array
    {
        return [
            'meta_title' => 'meta title',
            'meta_description' => 'meta description',
            'canonical_url' => 'canonical URL',
            'og_media_id' => 'social share image',
        ];
    }
}
