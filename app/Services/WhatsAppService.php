<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $apiUrl;
    protected $token;
    protected $isTestMode;

    public function __construct()
    {
        $this->apiUrl = config('whatsapp.api_url');
        $this->token = config('whatsapp.token');
        $this->isTestMode = config('whatsapp.test_mode', true);
    }

    public function sendMessage($phone, $message)
    {
        try {
            // Log setiap upaya pengiriman
            \Log::channel('whatsapp')->info('Attempting to send WhatsApp message', [
                'raw_phone' => $phone,
                'message' => $message,
                'timestamp' => now()->toDateTimeString()
            ]);

            // Format phone number
            $phone = $this->formatPhoneNumber($phone);

            // Log nomor yang sudah diformat
            \Log::channel('whatsapp')->info('Formatted phone number', [
                'formatted_phone' => $phone
            ]);

            if ($this->isTestMode) {
                \Log::channel('whatsapp')->info('TEST MODE: Message would be sent', [
                    'to' => $phone,
                    'message' => $message,
                    'timestamp' => now()->toDateTimeString()
                ]);
                return true;
            }

            // Production mode
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
            ])->post($this->apiUrl . '/messages', [
                'messaging_product' => 'whatsapp',
                'to' => $phone,
                'type' => 'text',
                'text' => [
                    'body' => $message
                ]
            ]);

            // Log response
            \Log::channel('whatsapp')->info('WhatsApp API Response', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            \Log::channel('whatsapp')->error('Error in WhatsApp service', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    protected function formatPhoneNumber($phone)
    {
        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Remove leading 0 if exists
        if (substr($phone, 0, 1) === '0') {
            $phone = substr($phone, 1);
        }

        // Add Indonesia country code
        return '62' . $phone;
    }
}
