<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function leadership()
    {
        $department = Department::where('slug', 'leadership')->first();

        $query = Staff::where('is_active', true)
            ->where(function ($q) use ($department) {
                $q->where('is_featured', true);
                if ($department) {
                    $q->orWhere('department_id', $department->id);
                }
            })
            ->orderBy('sort_order');

        $staff = $query->get();

        return view('staff.leadership', compact('staff', 'department'));
    }

    public function index(Request $request)
    {
        $departments = Department::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $query = Staff::with('department')
            ->where('is_active', true)
            ->orderBy('sort_order');

        if ($request->filled('department')) {
            $selected = Department::where('slug', $request->get('department'))
                ->where('is_active', true)
                ->first();

            if ($selected) {
                $query->where('department_id', $selected->id);
            }
        }

        $staff = $query->paginate(12)->withQueryString();

        return view('staff.index', compact('staff', 'departments'));
    }

    public function show(Staff $staff)
    {
        if (! $staff->is_active) {
            abort(404);
        }

        return view('staff.show', compact('staff'));
    }
}
