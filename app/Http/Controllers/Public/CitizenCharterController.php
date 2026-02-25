<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CharterService;
use App\Models\Department;
use App\Models\Service;

class CitizenCharterController extends Controller
{
    public function index()
    {
        $departments = Department::where('is_active', true)
            ->withCount(['charterServices' => fn ($query) => $query->active()])
            ->orderBy('sort_order')
            ->get();
        $services = Service::where('is_active', true)
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        return view('citizen-charter.index', compact('departments', 'services'));
    }

    public function department(Department $department)
    {
        $services = $department->charterServices()
            ->active()
            ->orderBy('sort_order')
            ->get();

        return view('citizen-charter.department', compact('department', 'services'));
    }

    public function service(Department $department, CharterService $service)
    {
        if ($service->department_id !== $department->id) {
            abort(404);
        }

        $departmentServices = $department->charterServices()
            ->active()
            ->orderBy('sort_order')
            ->get();

        return view('citizen-charter.service', compact('department', 'service', 'departmentServices'));
    }
}
