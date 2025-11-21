<?php

namespace App\Services\Contracts;

use App\Services\WhatsApp\WhatsAppMessageResult;

interface WhatsAppService
{
    public function sendText(string $phone, string $message, array $context = []): WhatsAppMessageResult;
}
