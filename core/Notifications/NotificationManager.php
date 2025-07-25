<?php

namespace Core\Notifications;

use Core\Auth\User;

/**
 * Class NotificationManager
 *
 * Manages sending notifications to users.
 */
class NotificationManager
{
    public function sendNotification(Notification $notification): void
    {
        $notification->send();
    }

    public function sendBulkNotifications(array $notifications): void
    {
        foreach ($notifications as $notification) {
            $notification->send();
        }
    }
}

?>
