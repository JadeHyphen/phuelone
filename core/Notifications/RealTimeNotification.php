<?php

namespace Core\Notifications;

use Core\Auth\User;

/**
 * Class RealTimeNotification
 *
 * Represents a real-time notification using WebSockets.
 */
class RealTimeNotification extends Notification
{
    public function send(): void
    {
        // Logic to send a real-time notification
        echo "Real-time notification sent to {$this->recipient->toArray()['email']}: {$this->message}";
    }
}

?>
