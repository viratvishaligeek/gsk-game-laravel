<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\AppSettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationSoundController extends Controller
{
    public function index(AppSettingsService $settings): View
    {
        return view('backend.notification-sounds', [
            'pageName' => 'Notification Sound Settings',
            'privateSound' => $settings->value('notification_sound_private', ''),
            'publicSound' => $settings->value('notification_sound_public', ''),
        ]);
    }

    public function update(Request $request, AppSettingsService $settings): RedirectResponse
    {
        $validated = $request->validate([
            'notification_sound_private' => ['nullable', 'string', 'max:255', 'regex:/^[A-Za-z0-9_\/.-]*$/', 'not_regex:/\.\./'],
            'notification_sound_public' => ['nullable', 'string', 'max:255', 'regex:/^[A-Za-z0-9_\/.-]*$/', 'not_regex:/\.\./'],
        ]);

        $settings->save([
            'notification_sound_private' => $validated['notification_sound_private'] ?? '',
            'notification_sound_public' => $validated['notification_sound_public'] ?? '',
        ]);

        return redirect()->route('admin.notification-sounds.index')->with('success', 'Notification sound settings saved.');
    }
}
