<?php

namespace App\Services;

use App\Models\WebhookEndpoint;
use App\Models\WebhookLog;
use App\Jobs\DispatchWebhookJob;
use Illuminate\Support\Facades\Log;

class WebhookService
{
    /**
     * Trigger a webhook event
     *
     * @param string $event
     * @param mixed $data
     * @return void
     */
    public function trigger($event, $data)
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('webhook_endpoints')) {
                return;
            }

            $payload = $this->formatPayload($event, $data);
            
            $endpoints = WebhookEndpoint::where('is_active', true)
                ->where(function ($query) use ($event) {
                    $query->whereJsonContains('events', $event)
                          ->orWhereNull('events')
                          ->orWhere('events', '[]');
                })
                ->get();

            foreach ($endpoints as $endpoint) {
                DispatchWebhookJob::dispatch($endpoint, $event, $payload);
            }
        } catch (\Throwable $e) {
            Log::error('WebhookService trigger error: ' . $e->getMessage());
        }
    }

    /**
     * Format the payload for the webhook
     *
     * @param string $event
     * @param mixed $data
     * @return array
     */
    protected function formatPayload($event, $data)
    {
        $payload = [
            'event' => $event,
            'timestamp' => now()->toIso8601String(),
            'data' => []
        ];

        if ($data instanceof \App\Models\Booking) {
            $payload['data'] = [
                'booking_id' => $data->id,
                'guest' => [
                    'name' => $data->name,
                    'email' => $data->email,
                    'phone' => $data->phone,
                ],
                'details' => [
                    'room_name' => $data->room_name,
                    'booking_date' => $data->booking_date,
                    'start_time' => $data->start_time,
                    'end_time' => $data->end_time,
                    'total_price' => $data->total_price,
                    'payment_status' => $data->payment_status,
                    'approval_status' => $data->approval_status,
                ]
            ];
        } elseif (is_array($data)) {
            $payload['data'] = $data;
        }

        return $payload;
    }
}
