<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Event extends Model
{
    protected $fillable = [
        'title_primary',
        'title_secondary',
        'title_suffix',
        'title_segments',
        'description',
        'image_path',
        'features',
        'content_blocks',
        'cta_text',
        'cta_link',
        'status',
        'show_on_homepage',
        'sort_order',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'features' => 'array',
        'title_segments' => 'array',
        'content_blocks' => 'array',
        'show_on_homepage' => 'boolean',
        'sort_order' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    protected $appends = ['display_title_segments', 'display_content_blocks'];

    /**
     * Title segments for versatile display. Uses title_segments when set,
     * otherwise builds from title_primary, title_secondary, title_suffix.
     */
    public function getDisplayTitleSegmentsAttribute(): array
    {
        $segments = $this->title_segments;
        if (! empty($segments) && is_array($segments)) {
            return $segments;
        }
        $out = [
            ['text' => $this->title_primary ?? '', 'color' => 'green'],
            ['text' => ' ' . ($this->title_secondary ?? ''), 'color' => 'yellow'],
        ];
        if (! empty($this->title_suffix)) {
            $out[] = ['text' => ' ' . $this->title_suffix, 'color' => null];
        }
        return $out;
    }

    /**
     * Content blocks for versatile display. Uses content_blocks when set,
     * otherwise builds from description + features.
     */
    public function getDisplayContentBlocksAttribute(): array
    {
        $blocks = $this->content_blocks;
        if (! empty($blocks) && is_array($blocks)) {
            return $blocks;
        }
        $out = [];
        if (! empty($this->description)) {
            $out[] = ['type' => 'paragraph', 'text' => $this->description];
        }
        if (! empty($this->features) && is_array($this->features)) {
            foreach ($this->features as $idx => $feat) {
                $out[] = [
                    'type' => 'feature',
                    'title' => $feat['title'] ?? '',
                    'description' => $feat['description'] ?? '',
                    'icon' => $idx % 2 === 0 ? 'plus' : 'flame',
                ];
            }
        }
        return $out;
    }

    public static function rules(): array
    {
        return [
            'title_primary' => [Rule::requiredIf(fn () => empty(request()->title_segments)), 'nullable', 'string', 'max:255'],
            'title_secondary' => [Rule::requiredIf(fn () => empty(request()->title_segments)), 'nullable', 'string', 'max:255'],
            'title_suffix' => 'nullable|string|max:255',
            'title_segments' => 'nullable|array',
            'title_segments.*.text' => 'required_with:title_segments|string|max:255',
            'title_segments.*.color' => 'nullable|string|in:green,yellow,white',
            'description' => [Rule::requiredIf(fn () => empty(request()->content_blocks)), 'nullable', 'string', 'max:2000'],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'image_path' => 'required_without:image|nullable|string|max:500',
            'features' => 'nullable|array',
            'features.*.title' => 'required_with:features|string|max:255',
            'features.*.description' => 'required_with:features|string|max:500',
            'content_blocks' => 'nullable|array',
            'content_blocks.*.type' => 'required_with:content_blocks|string|in:paragraph,heading,feature',
            'content_blocks.*.text' => 'nullable|string|max:2000',
            'content_blocks.*.title' => 'nullable|string|max:255',
            'content_blocks.*.description' => 'nullable|string|max:500',
            'content_blocks.*.icon' => 'nullable|string|in:plus,flame,star',
            'cta_text' => 'required|string|max:100',
            'cta_link' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
            'show_on_homepage' => 'boolean',
            'sort_order' => 'integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ];
    }

    public static function updateRules(Event $event): array
    {
        $rules = self::rules();
        $rules['image_path'] = 'nullable|string|max:500';
        $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120';
        return $rules;
    }

    /**
     * Scope for published events.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope: only events that are within their display schedule (or have no schedule).
     */
    public function scopeScheduled($query)
    {
        $now = now();
        return $query->where(function ($q) use ($now) {
            $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
        })->where(function ($q) use ($now) {
            $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
        });
    }

    /**
     * Get the event to display on the homepage (one featured, within schedule).
     */
    public static function getFeaturedForHomepage(): ?self
    {
        return static::published()
            ->where('show_on_homepage', true)
            ->scheduled()
            ->orderBy('sort_order')
            ->first();
    }
}
