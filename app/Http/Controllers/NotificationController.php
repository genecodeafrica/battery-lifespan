<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with([
            'battery',
            'prediction',
        ])
        ->where('user_id', Auth::id())
        ->latest()
        ->paginate(15);

        return view(
            'notifications.index',
            compact('notifications')
        );
    }

    public function read(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->update([
            'is_read' => true,
        ]);

        if ($notification->battery_id) {
            return redirect()->route(
                'batteries.show',
                $notification->battery_id
            );
        }

        return redirect()
            ->route('notifications.index')
            ->with(
                'success',
                'Notification marked as read.'
            );
    }

    public function markAllRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
            ]);

        return back()->with(
            'success',
            'All notifications have been marked as read.'
        );
    }
}