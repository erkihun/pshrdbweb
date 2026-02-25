<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCharterServiceRequest;
use App\Http\Requests\UpdateCharterServiceRequest;
use App\Models\CharterService;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CharterServiceController extends Controller
{
    public function index(Request $request)
    {
        $departments = Department::orderBy('sort_order')->get();

        $services = CharterService::with('department')
            ->when($request->filled('department'), fn ($query) => $query->where('department_id', $request->department))
            ->when($request->filled('status'), function ($query) use ($request) {
                if ($request->status === 'inactive') {
                    $query->where('is_active', false);
                } else {
                    $query->where('is_active', true);
                }
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = '%' . $request->search . '%';
                $query->where(function ($query) use ($term) {
                    $query->where('name_am', 'like', $term)
                        ->orWhere('name_en', 'like', $term);
                });
            })
            ->orderBy('sort_order')
            ->orderByDesc('updated_at')
            ->paginate(15)
            ->withQueryString();

        $filters = $request->only(['department', 'status', 'search']);

        return view('admin.charter-services.index', compact('services', 'departments', 'filters'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->orderBy('sort_order')->get();
        $service = new CharterService();

        return view('admin.charter-services.create', compact('service', 'departments'));
    }

    public function store(StoreCharterServiceRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $this->generateSlug($request->input('name_am'));
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['fee_currency'] = $data['fee_currency'] ?? 'ETB';
        $data['working_days'] = $data['working_days'] ?? [];

        CharterService::create($data);

        return redirect()
            ->route('admin.charter-services.index')
            ->with('success', __('common.citizen_charter.admin.created'));
    }

    public function show(CharterService $charterService)
    {
        $service = $charterService->load('department');

        return view('admin.charter-services.show', compact('service'));
    }

    public function edit(CharterService $charterService)
    {
        $departments = Department::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.charter-services.edit', [
            'service' => $charterService,
            'departments' => $departments,
        ]);
    }

    public function update(UpdateCharterServiceRequest $request, CharterService $charterService)
    {
        $data = $request->validated();
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['fee_currency'] = $data['fee_currency'] ?? 'ETB';
        $data['working_days'] = $data['working_days'] ?? [];

        if ($charterService->name_am !== $data['name_am']) {
            $data['slug'] = $this->generateSlug($data['name_am'], $charterService->id);
        }

        $charterService->update($data);

        return redirect()
            ->route('admin.charter-services.index')
            ->with('success', __('common.citizen_charter.admin.updated'));
    }

    public function destroy(CharterService $charterService)
    {
        $charterService->delete();

        return redirect()
            ->route('admin.charter-services.index')
            ->with('success', __('common.citizen_charter.admin.deleted'));
    }

    private function generateSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base ?: (string) Str::uuid();
        $counter = 1;

        while (
            CharterService::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base ? "{$base}-{$counter}" : (string) Str::uuid();
            $counter++;
        }

        return $slug;
    }
}
