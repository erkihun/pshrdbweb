<?php

declare(strict_types=1);

namespace App\Actions\HomeSlides;

use App\Models\HomeSlide;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final readonly class CreateHomeSlideAction
{
    public function execute(array $data, UploadedFile $image): HomeSlide
    {
        $path = $image->store('branding/slides', 'public');

        $transitionStyle = $data['transition_style'] ?? 'wave';
        $contentAlignment = $data['content_alignment'] ?? 'center';

        return HomeSlide::create([
            ...$data,
            'image_path' => $path,
            'is_active' => (bool) ($data['is_active'] ?? true),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'transition_style' => $transitionStyle,
            'content_alignment' => $contentAlignment,
        ]);
    }
}
