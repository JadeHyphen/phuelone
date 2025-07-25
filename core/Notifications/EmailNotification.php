<?php

namespace Core\Notifications;

use Core\Auth\User;

/**
 * Class EmailNotification
 *
 * Represents an email notification.
 */
class EmailNotification extends Notification
{
    public function send(): void
    {
        // Logic to send an email notification
        echo "Email sent to {$this->recipient->toArray()['email']}: {$this->message}";
    }
}

?>
