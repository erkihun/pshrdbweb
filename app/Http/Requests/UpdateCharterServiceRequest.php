<?php

namespace App\Http\Requests;

class UpdateCharterServiceRequest extends CharterServiceRequest
{
    public function rules(): array
    {
        return $this->commonRules();
    }
}
