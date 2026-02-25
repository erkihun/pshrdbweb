<?php

declare(strict_types=1);

namespace App\Actions\SignageTemplates;

use App\Models\SignageTemplate;

final class DeleteSignageTemplateAction
{
    public function execute(SignageTemplate $template): void
    {
        $template->delete();
    }
}
