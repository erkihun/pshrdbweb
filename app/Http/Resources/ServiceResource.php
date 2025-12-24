<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->display_title,
            'description' => $this->display_description,
            'requirements' => $this->display_requirements,
            'title_am' => $this->title_am,
            'title_en' => $this->title_en,
            'description_am' => $this->description_am,
            'description_en' => $this->description_en,
            'requirements_am' => $this->requirements_am,
            'requirements_en' => $this->requirements_en,
            'is_active' => (bool) $this->is_active,
            'sort_order' => (int) $this->sort_order,
            'created_at' => optional($this->created_at)->toISOString(),
            'updated_at' => optional($this->updated_at)->toISOString(),
        ];
    }
}
