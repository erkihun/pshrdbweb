<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->display_title,
            'description' => $this->display_description,
            'title_am' => $this->title_am,
            'title_en' => $this->title_en,
            'description_am' => $this->description_am,
            'description_en' => $this->description_en,
            'file_type' => $this->file_type,
            'file_size' => (int) $this->file_size,
            'file_url' => $this->file_path ? asset('storage/' . $this->file_path) : null,
            'published_at' => optional($this->published_at)->toISOString(),
            'created_at' => optional($this->created_at)->toISOString(),
            'updated_at' => optional($this->updated_at)->toISOString(),
        ];
    }
}
