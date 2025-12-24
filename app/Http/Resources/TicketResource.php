<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'reference_code' => $this->reference_code,
            'status' => $this->status,
            'admin_reply' => $this->admin_reply,
            'replied_at' => optional($this->replied_at)->toISOString(),
            'created_at' => optional($this->created_at)->toISOString(),
        ];
    }
}
