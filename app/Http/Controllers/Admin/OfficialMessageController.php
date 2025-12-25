<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOfficialMessageRequest;
use App\Models\OfficialMessage;
use Illuminate\Support\Facades\Storage;

final class OfficialMessageController extends Controller
{
    public function edit()
    {
        $message = OfficialMessage::query()->first();

        if (! $message) {
            $message = OfficialMessage::query()->create([
                'name' => 'Higher Official Name',
                'title' => 'Position / Title',
                'message' => 'Write the official message here.',
                'is_active' => true,
            ]);
        }

        return view('admin.official-message.edit', compact('message'));
    }

    public function update(UpdateOfficialMessageRequest $request)
    {
        $message = OfficialMessage::query()->firstOrFail();

        if ($request->hasFile('photo')) {
            if ($message->photo_path) {
                Storage::disk('public')->delete($message->photo_path);
            }
            $message->photo_path = $request->file('photo')->store('branding/official', 'public');
        }

        $message->fill([
            'name' => $request->string('name')->toString(),
            'title' => $request->string('title')->toString(),
            'message' => $request->string('message')->toString(),
            'is_active' => (bool) $request->boolean('is_active'),
        ])->save();

        return redirect()->route('admin.official-message.edit')
            ->with('success', 'Official message updated.');
    }
}
