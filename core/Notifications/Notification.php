<?php

namespace Core\Notifications;

use Core\Auth\User;

/**
 * Class Notification
 *
 * Represents a notification sent to a user.
 */
class Notification
{
    protected string $message;
    protected User $recipient;

    public function __construct(string $message, User $recipient)
    {
        $this->message = $message;
        $this->recipient = $recipient;
    }

    public function send(): void
    {
        // Logic to send the notification (e.g., email, push, etc.)
        echo "Notification sent to {$this->recipient->toArray()['email']}: {$this->message}";
    }
}

?>
