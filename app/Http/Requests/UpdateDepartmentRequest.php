<?php

namespace App\Http\Requests;

class UpdateDepartmentRequest extends DepartmentRequest
{
    public function rules(): array
    {
        return $this->commonRules();
    }
}
