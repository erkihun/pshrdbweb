<?php

declare(strict_types=1);

namespace App\Actions\HomeSlides;

use App\Models\HomeSlide;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final readonly class UpdateHomeSlideAction
{
    public function execute(HomeSlide $slide, array $data, ?UploadedFile $image): HomeSlide
    {
        if ($image) {
            Storage::disk('public')->delete($slide->image_path);
            $slide->image_path = $image->store('branding/slides', 'public');
        }

        $transitionStyle = $data['transition_style'] ?? $slide->transition_style ?? 'wave';
        $contentAlignment = $data['content_alignment'] ?? $slide->content_alignment ?? 'center';

        $slide->fill([
            ...$data,
            'is_active' => (bool) ($data['is_active'] ?? $slide->is_active),
            'sort_order' => (int) ($data['sort_order'] ?? $slide->sort_order),
            'transition_style' => $transitionStyle,
            'content_alignment' => $contentAlignment,
        ])->save();

        return $slide;
    }
}
