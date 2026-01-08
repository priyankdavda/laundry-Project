<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public static function sendOrderCreatedMessage($mobile, $order)
    {
        $token = env('WHATSAPP_TOKEN');
        $phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID');

        $url = "https://graph.facebook.com/v18.0/{$phoneNumberId}/messages";

        $response = Http::withToken($token)->post($url, [
            "messaging_product" => "whatsapp",
            "to" => $mobile,
            "type" => "text",
            "text" => [
                "body" =>
"🧺 Laundry Order Confirmed

Order No: {$order->order_number}
Pickup: {$order->pickup_date} ({$order->pickup_timeslot})
Delivery: {$order->delivery_date} ({$order->delivery_timeslot})

Total: ₹{$order->total_amount}
Pending: ₹{$order->pending_amount}

Thank you 🙏"
            ]
        ]);

        if ($response->failed()) {
            Log::error('WhatsApp API Error', $response->json());
        }

        return $response;
    }
}
