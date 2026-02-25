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
                'name_am' => 'Higher Official Name',
                'name_en' => 'Higher Official Name',
                'title' => 'Position / Title',
                'title_am' => 'Position / Title',
                'title_en' => 'Position / Title',
                'message' => 'Write the official message here.',
                'message_am' => 'Write the official message here.',
                'message_en' => 'Write the official message here.',
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
            'name_am' => $request->string('name_am')->toString(),
            'name_en' => $request->string('name_en')->toString(),
            'title_am' => $request->string('title_am')->toString(),
            'title_en' => $request->string('title_en')->toString(),
            'message_am' => $request->string('message_am')->toString(),
            'message_en' => $request->string('message_en')->toString(),
            'name' => $request->string('name_en')->toString(),
            'title' => $request->string('title_en')->toString(),
            'message' => $request->string('message_en')->toString(),
            'is_active' => (bool) $request->boolean('is_active'),
        ])->save();

        return redirect()->route('admin.official-message.edit')
            ->with('success', 'Official message updated.');
    }
}
