<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\AccountActivityNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Throwable;

class ActivityNotificationService
{
    public function isEnabled(): bool
    {
        return Schema::hasTable('notifications');
    }

    public function notifyUser(
        ?User $user,
        string $title,
        string $message,
        ?string $actionUrl = null,
        string $actionLabel = 'Open'
    ): void {
        if (! $user || ! $this->isEnabled()) {
            return;
        }

        try {
            $user->notify(new AccountActivityNotification(
                $title,
                $message,
                $actionUrl,
                $actionLabel
            ));
        } catch (Throwable $e) {
            Log::warning('Activity notification failed for single user', [
                'user_id' => $user->id,
                'title' => $title,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function notifyUsers(
        iterable $users,
        string $title,
        string $message,
        ?string $actionUrl = null,
        string $actionLabel = 'Open'
    ): void {
        if (! $this->isEnabled()) {
            return;
        }

        foreach ($users as $user) {
            if (! $user instanceof User) {
                continue;
            }

            $this->notifyUser($user, $title, $message, $actionUrl, $actionLabel);
        }
    }
}
