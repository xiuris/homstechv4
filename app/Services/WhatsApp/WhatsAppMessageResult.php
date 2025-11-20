<?php

namespace App\Services\WhatsApp;

class WhatsAppMessageResult
{
    public function __construct(
        public readonly bool $success,
        public readonly bool $mocked,
        public readonly ?string $messageId = null,
        public readonly ?string $error = null,
    ) {}
}
