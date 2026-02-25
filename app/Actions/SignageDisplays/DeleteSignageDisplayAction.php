<?php

declare(strict_types=1);

namespace App\Actions\SignageDisplays;

use App\Models\SignageDisplay;

final class DeleteSignageDisplayAction
{
    public function execute(SignageDisplay $display): void
    {
        $display->delete();
    }
}
