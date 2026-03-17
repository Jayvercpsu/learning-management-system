<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    public function markAsRead(string $notificationId): RedirectResponse
    {
        $notification = $this->findUserNotification($notificationId);

        if ($notification->read_at === null) {
            $notification->markAsRead();
        }

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead(): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }

    public function open(string $notificationId): RedirectResponse
    {
        $notification = $this->findUserNotification($notificationId);

        if ($notification->read_at === null) {
            $notification->markAsRead();
        }

        $actionUrl = (string) data_get($notification->data, 'action_url', '');
        if ($this->isAllowedRedirect($actionUrl)) {
            return redirect()->to($actionUrl);
        }

        return back()->with('info', 'Notification opened.');
    }

    private function findUserNotification(string $notificationId): DatabaseNotification
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        return $user->notifications()
            ->where('id', $notificationId)
            ->firstOrFail();
    }

    private function isAllowedRedirect(string $url): bool
    {
        if ($url === '') {
            return false;
        }

        if (Str::startsWith($url, '/')) {
            return true;
        }

        $targetHost = Str::lower((string) parse_url($url, PHP_URL_HOST));
        if ($targetHost === '') {
            return false;
        }

        $allowedHosts = array_filter([
            Str::lower((string) parse_url((string) config('app.url'), PHP_URL_HOST)),
            request()->getHost() ? Str::lower((string) request()->getHost()) : null,
        ]);

        if (in_array($targetHost, $allowedHosts, true)) {
            return true;
        }

        $localAliases = ['localhost', '127.0.0.1', '::1'];
        $targetIsLocal = in_array($targetHost, $localAliases, true);
        $allowedHasLocal = count(array_intersect($allowedHosts, $localAliases)) > 0;

        return $targetIsLocal && $allowedHasLocal;
    }
}
