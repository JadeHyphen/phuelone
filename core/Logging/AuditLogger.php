<?php

namespace Core\Logging;

use Core\Logging\Logger;

class AuditLogger extends Logger
{
    public function logAction(string $user, string $action, array $details = []): void
    {
        $this->info("User: $user performed action: $action", $details);
    }
}
