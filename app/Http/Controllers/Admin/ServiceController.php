<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use App\Services\AuditLogService;
use App\Services\PublicCacheService;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::orderBy('sort_order')->paginate(10);

        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['title'] = $data['title_en'];
        $data['description'] = $data['description_en'];
        $data['requirements'] = $data['requirements_en'] ?? null;
        $data['slug'] = $this->uniqueSlug($data['title_en']);

        $service = Service::create($data);

        AuditLogService::log('services.created', 'service', $service->id, [
            'title' => $service->title,
            'slug' => $service->slug,
        ]);

        AuditLogService::log($service->is_active ? 'services.activated' : 'services.deactivated', 'service', $service->id, [
            'title' => $service->title,
            'slug' => $service->slug,
        ]);

        PublicCacheService::bump('services');

        return redirect()
            ->route('admin.services.show', $service)
            ->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return view('admin.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        $data = $request->validated();
        $wasActive = $service->is_active;
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $data['title'] = $data['title_en'];
        $data['description'] = $data['description_en'];
        $data['requirements'] = $data['requirements_en'] ?? null;

        if ($service->title_en !== $data['title_en']) {
            $data['slug'] = $this->uniqueSlug($data['title_en'], $service->id);
        }

        $service->update($data);

        AuditLogService::log('services.updated', 'service', $service->id, [
            'title' => $service->title,
            'slug' => $service->slug,
        ]);

        if ($wasActive !== $service->is_active) {
            AuditLogService::log($service->is_active ? 'services.activated' : 'services.deactivated', 'service', $service->id, [
                'title' => $service->title,
                'slug' => $service->slug,
            ]);
        }

        PublicCacheService::bump('services');

        return redirect()
            ->route('admin.services.show', $service)
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        AuditLogService::log('services.deleted', 'service', $service->id, [
            'title' => $service->title,
            'slug' => $service->slug,
        ]);

        $service->delete();

        PublicCacheService::bump('services');

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }

    private function uniqueSlug(string $title, ?string $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base !== '' ? $base : (string) Str::uuid();
        $counter = 1;

        while (
            Service::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base !== '' ? $base . '-' . $counter : (string) Str::uuid();
            $counter++;
        }

        return $slug;
    }
}
