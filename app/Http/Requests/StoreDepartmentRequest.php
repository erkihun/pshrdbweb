<?php

namespace App\Http\Requests;

class StoreDepartmentRequest extends DepartmentRequest
{
    public function rules(): array
    {
        return $this->commonRules();
    }
}
