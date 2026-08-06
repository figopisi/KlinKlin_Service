<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WablasService
{
    protected string $baseUrl;
    protected string $token;
    protected string $secret;

    public function __construct()
    {
        $this->baseUrl = config('services.wablas.base_url');
        $this->token   = config('services.wablas.token');
        $this->secret  = config('services.wablas.secret');
    }

    public function sendText(string $phone, string $message): array
    {
        $phone = $this->normalizePhone($phone);

        try {
            $response = Http::withHeaders([
                'Authorization' => "{$this->token}.{$this->secret}",
            ])->post("{$this->baseUrl}/api/send-message", [
                'phone'   => $phone,
                'message' => $message,
                'flag'    => 'instant',
            ]);

            $result = $response->json();

            Log::info('Wablas sendText response:', [
                'phone' => $phone,
                'response' => $result,
                'http_status' => $response->status(),
            ]);

            return $result ?? [];
        } catch (\Exception $e) {
            Log::error('Wablas send exception', ['phone' => $phone, 'error' => $e->getMessage()]);
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    // Nomor Indonesia sering diinput 08xx, Wablas butuh format 628xx
    protected function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        return $phone;
    }

    public function sendGroupText(string $groupId, string $message): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "{$this->token}.{$this->secret}",
            ])->post("{$this->baseUrl}/api/send-message", [
                'phone'   => $groupId,
                'message' => $message,
                'isGroup' => 'true',
                'flag'    => 'instant',
            ]);

            $result = $response->json();

            if (!($result['status'] ?? false)) {
                Log::warning('Wablas group send failed', ['group' => $groupId, 'response' => $result]);
            }

            return $result ?? [];
        } catch (\Exception $e) {
            Log::error('Wablas group send exception', ['group' => $groupId, 'error' => $e->getMessage()]);
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function sendList(string $phone, string $title, string $body, array $rows, string $buttonText = 'Pilih'): array
    {
        $phone = $this->normalizePhone($phone);

        try {
            $response = Http::withHeaders([
                'Authorization' => "{$this->token}.{$this->secret}",
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/api/v2/send-list", [
                'data' => [[
                    'phone' => $phone,
                    'message' => [
                        'title' => $title,
                        'body' => $body,
                        'footer' => 'KlinKlin Laundry',
                        'buttonText' => $buttonText,
                        'sections' => [[
                            'title' => $title,
                            'rows' => $rows, // masing-masing: ['title'=>.., 'description'=>.., 'rowId'=>..]
                        ]],
                    ],
                ]],
            ]);

            $result = $response->json();

            Log::info('Wablas sendList response:', [
                'phone' => $phone,
                'response' => $result,
                'http_status' => $response->status(),
            ]);

            return $result ?? [];
        } catch (\Throwable $e) {
            Log::error('Wablas sendList exception', ['phone' => $phone, 'error' => $e->getMessage()]);
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }
}