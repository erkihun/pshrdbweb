<?php

namespace App\Http\Requests;

class StoreCharterServiceRequest extends CharterServiceRequest
{
    public function rules(): array
    {
        return $this->commonRules();
    }
}
