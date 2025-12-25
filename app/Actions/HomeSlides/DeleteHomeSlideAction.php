<?php

declare(strict_types=1);

namespace App\Actions\HomeSlides;

use App\Models\HomeSlide;
use Illuminate\Support\Facades\Storage;

final readonly class DeleteHomeSlideAction
{
    public function execute(HomeSlide $slide): void
    {
        Storage::disk('public')->delete($slide->image_path);
        $slide->delete();
    }
}
