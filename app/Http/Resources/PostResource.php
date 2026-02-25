<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'slug' => $this->slug,
            'title' => $this->display_title,
            'excerpt' => $this->display_excerpt,
            'body' => $this->display_body,
            'title_am' => $this->title_am,
            'title_en' => $this->title_en,
            'excerpt_am' => $this->excerpt_am,
            'excerpt_en' => $this->excerpt_en,
            'body_am' => $this->body_am,
            'body_en' => $this->body_en,
            'cover_image_url' => $this->cover_image_path ? asset('storage/' . $this->cover_image_path) : null,
            'published_at' => optional($this->published_at)->toISOString(),
            'created_at' => optional($this->created_at)->toISOString(),
            'updated_at' => optional($this->updated_at)->toISOString(),
        ];
    }
}
