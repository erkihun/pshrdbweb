<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Support\Facades\Schema;

class OrganizationContactController extends Controller
{
    public function index()
    {
        $query = Organization::query()
            ->where('is_active', true);

        if (Schema::hasColumn('organizations', 'is_main')) {
            $query->orderByDesc('is_main');
        }

        $organizations = $query
            ->orderBy('created_at')
            ->get();

        abort_if($organizations->isEmpty(), 404);

        return view('organization.contact', compact('organizations'));
    }
}
