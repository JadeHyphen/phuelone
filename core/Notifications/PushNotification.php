<?php

namespace Core\Notifications;

use Core\Auth\User;

/**
 * Class PushNotification
 *
 * Represents a push notification.
 */
class PushNotification extends Notification
{
    public function send(): void
    {
        // Logic to send a push notification
        echo "Push notification sent to {$this->recipient->toArray()['email']}: {$this->message}";
    }
}

?>
