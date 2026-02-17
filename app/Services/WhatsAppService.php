<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    public static function sendInvoiceWithPdf($mobile, $order, $invoiceUrl)
    {
        $token = env('WHATSAPP_TOKEN');
        $phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID');

        $url = "https://graph.facebook.com/v18.0/{$phoneNumberId}/messages";

        return Http::withToken($token)->post($url, [
            "messaging_product" => "whatsapp",
            "to" => $mobile,
            "type" => "template",
            "template" => [
                "name" => "invoice_ready",
                "language" => ["code" => "en"],
                "components" => [
                    [
                        "type" => "body",
                        "parameters" => [
                            ["type" => "text", "text" => $order->customer_name],
                            ["type" => "text", "text" => $order->order_number],
                            ["type" => "text", "text" => $order->total_amount],
                        ]
                    ],
                    [
                        "type" => "header",
                        "parameters" => [
                            [
                                "type" => "document",
                                "document" => [
                                    "link" => $invoiceUrl,
                                    "filename" => "Invoice_{$order->order_number}.pdf"
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    }


public static function sendInvoiceTemplate($mobile, $order, $invoiceUrl)
{
    $token = env('WHATSAPP_TOKEN');
    $phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID');

    $url = "https://graph.facebook.com/v24.0/{$phoneNumberId}/messages";

    return Http::withToken($token)->post($url, [
        "messaging_product" => "whatsapp",
        "to" => $mobile,
        "type" => "template",
        "template" => [
            "name" => "invoice_ready",
            "language" => [
                "code" => "en"
            ],
            "components" => [
                [
                    "type" => "body",
                    "parameters" => [
                        [
                            "type" => "text",
                            "text" => $order->customer_name
                        ],
                        [
                            "type" => "text",
                            "text" => $order->order_number
                        ],
                        [
                            "type" => "text",
                            "text" => $invoiceUrl
                        ]
                    ]
                ]
            ]
        ]
    ]);
}



}
