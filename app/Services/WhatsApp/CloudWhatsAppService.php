<?php

namespace App\Services\WhatsApp;

use App\Services\Contracts\WhatsAppService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class CloudWhatsAppService implements WhatsAppService
{
    public function sendText(string $phone, string $message, array $context = []): WhatsAppMessageResult
    {
        $token = config('services.whatsapp.token');
        $phoneId = config('services.whatsapp.phone_id');
        $endpoint = sprintf('%s/%s/messages', rtrim(config('services.whatsapp.base_url'), '/'), $phoneId);

        if (empty($token) || empty($phoneId)) {
            Log::info('WhatsApp mock send', compact('phone', 'message', 'context'));

            return new WhatsAppMessageResult(true, true, Str::uuid()->toString());
        }

        try {
            $response = Http::retry(
                config('services.whatsapp.retries', 3),
                config('services.whatsapp.backoff_ms', 200)
            )
                ->withToken($token)
                ->post($endpoint, [
                    'messaging_product' => 'whatsapp',
                    'to' => $phone,
                    'type' => 'text',
                    'text' => ['body' => $message],
                ] + $context);

            if ($response->successful()) {
                $messageId = $response->json('messages.0.id', Str::uuid()->toString());
                Log::info('WhatsApp message sent', ['id' => $messageId, 'phone' => $phone]);

                return new WhatsAppMessageResult(true, false, $messageId);
            }

            Log::warning('WhatsApp send failure', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            return new WhatsAppMessageResult(false, false, error: 'failed_http_'.$response->status());
        } catch (Throwable $e) {
            Log::error('WhatsApp send exception', ['message' => $e->getMessage()]);

            return new WhatsAppMessageResult(false, false, error: 'exception');
        }
    }
}
