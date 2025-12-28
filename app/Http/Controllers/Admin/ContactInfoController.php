<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactInfoRequest;
use App\Http\Requests\UpdateContactInfoRequest;
use App\Models\ContactInfo;

class ContactInfoController extends Controller
{
    public function index()
    {
        $contactInfo = ContactInfo::current();

        return view('admin.contact-info.index', compact('contactInfo'));
    }

    public function create()
    {
        $contactInfo = ContactInfo::current();

        if ($contactInfo) {
            return redirect()->route('admin.contact-info.edit', $contactInfo);
        }

        return view('admin.contact-info.create');
    }

    public function store(StoreContactInfoRequest $request)
    {
        if (ContactInfo::current()) {
            return to_route('admin.contact-info.index');
        }

        ContactInfo::where('is_active', true)->update(['is_active' => false]);

        $data = $request->validated();
        $data['is_active'] = true;
        $data['updated_by'] = auth()->id();

        ContactInfo::create($data);

        return to_route('admin.contact-info.index')->with('success', 'Contact information saved.');
    }

    public function edit(ContactInfo $contactInfo)
    {
        return view('admin.contact-info.edit', compact('contactInfo'));
    }

    public function update(UpdateContactInfoRequest $request, ContactInfo $contactInfo)
    {
        $data = $request->validated();
        if (($data['is_active'] ?? true) === true) {
            ContactInfo::where('id', '!=', $contactInfo->id)->update(['is_active' => false]);
        }

        $contactInfo->update([
            ...$data,
            'updated_by' => auth()->id(),
        ]);

        return to_route('admin.contact-info.index')->with('success', 'Contact information updated.');
    }

    public function destroy(ContactInfo $contactInfo)
    {
        $contactInfo->update([
            'is_active' => false,
            'updated_by' => auth()->id(),
        ]);

        return to_route('admin.contact-info.index')->with('success', 'Contact information deactivated.');
    }
}
