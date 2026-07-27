<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send booking notification to the Principal via WhatsApp
     *
     * @param Booking $booking
     * @return bool
     */
    public function sendBookingNotification(Booking $booking)
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                return false;
            }
            $enabled = Setting::where('key', 'whatsapp_enabled')->value('value') === '1';
        } catch (\Throwable $e) {
            $enabled = false;
        }

        if (!$enabled) {
            Log::info("WhatsApp notifications are disabled.");
            return false;
        }

        try {
            $principalPhone = Setting::where('key', 'principal_phone')->value('value');
            if (empty($principalPhone)) {
                Log::warning("WhatsApp notifications are enabled, but Principal phone number is not set.");
                return false;
            }

            $provider = Setting::where('key', 'whatsapp_provider')->value('value') ?? 'ultramsg';
            $sender = Setting::where('key', 'whatsapp_sender')->value('value');
            $sid = Setting::where('key', 'whatsapp_sid')->value('value');
            $token = Setting::where('key', 'whatsapp_token')->value('value');
        } catch (\Throwable $e) {
            return false;
        }

        // Compile WhatsApp Message
        $roomName = ucwords(str_replace('-', ' ', $booking->room_name));
        $checkIn = \Carbon\Carbon::parse($booking->booking_date . ' ' . $booking->start_time)->format('d M Y, h:i A');
        $checkOut = \Carbon\Carbon::parse($booking->booking_date . ' ' . $booking->end_time)->format('d M Y, h:i A');
        $approveUrl = route('admin.bookings.approve.get', $booking->id);
        $rejectUrl = route('admin.bookings.reject.get', $booking->id);
        $reason = $booking->booking_reason ?: 'N/A';

        $message = "*New Booking Request Submitted*\n"
                 . "---------------------------------\n"
                 . "*Guest Name:* " . ucwords(strtolower($booking->name)) . "\n"
                 . "*Email:* {$booking->email}\n"
                 . "*Phone:* {$booking->phone}\n"
                 . "*Workspace:* {$roomName}\n"
                 . "*Check-In:* {$checkIn}\n"
                 . "*Check-Out:* {$checkOut}\n"
                 . "*Persons:* {$booking->no_of_persons}\n"
                 . "*Amount:* ₹" . number_format($booking->total_price, 2) . "\n"
                 . "*Purpose:* {$reason}\n\n"
                 . "*Quick Actions:*\n"
                 . "👉 *Approve:* {$approveUrl}\n"
                 . "👉 *Reject:* {$rejectUrl}";

        // Clean the phone number (remove spaces, plus, dashes, etc.)
        $cleanPhone = preg_replace('/[^0-9]/', '', $principalPhone);

        Log::info("Attempting to send WhatsApp notification via {$provider} to {$cleanPhone} (original: {$principalPhone}) for Booking ID: {$booking->id}");

        if ($provider === 'log') {
            Log::info("Simulated WhatsApp Message:\n" . $message);
            return true;
        }

        try {
            if ($provider === 'ultramsg') {
                if (empty($sid) || empty($token)) {
                    Log::error("Ultramsg configuration is missing Instance ID or API Token.");
                    return false;
                }

                $response = Http::asForm()->post("https://api.ultramsg.com/{$sid}/messages/chat", [
                    'token' => $token,
                    'to' => $cleanPhone,
                    'body' => $message
                ]);

                if ($response->successful()) {
                    Log::info("WhatsApp notification sent via Ultramsg successfully.");
                    return true;
                } else {
                    Log::error("Ultramsg API request failed: " . $response->body());
                    return false;
                }
            } elseif ($provider === 'twilio') {
                if (empty($sid) || empty($token) || empty($sender)) {
                    Log::error("Twilio configuration is missing Account SID, Auth Token, or Sender number.");
                    return false;
                }

                // Twilio requires leading '+' prefix for the numbers
                $twilioTo = '+' . $cleanPhone;
                $twilioFrom = '+' . preg_replace('/[^0-9]/', '', $sender);

                $response = Http::withBasicAuth($sid, $token)
                    ->asForm()
                    ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                        'From' => 'whatsapp:' . $twilioFrom,
                        'To' => 'whatsapp:' . $twilioTo,
                        'Body' => $message
                    ]);

                if ($response->successful()) {
                    Log::info("WhatsApp notification sent via Twilio successfully.");
                    return true;
                } else {
                    Log::error("Twilio WhatsApp API request failed: " . $response->body());
                    return false;
                }
            } elseif ($provider === 'callmebot') {
                if (empty($token)) {
                    Log::error("CallMeBot configuration is missing API Key (token).");
                    return false;
                }

                $formattedPhone = '+' . $cleanPhone;

                $response = Http::get("https://api.callmebot.com/whatsapp.php", [
                    'phone' => $formattedPhone,
                    'text' => $message,
                    'apikey' => $token
                ]);

                if ($response->successful()) {
                    Log::info("WhatsApp notification sent via CallMeBot successfully.");
                    return true;
                } else {
                    Log::error("CallMeBot API request failed: " . $response->body());
                    return false;
                }
            } elseif ($provider === 'meta') {
                if (empty($sid) || empty($token)) {
                    Log::error("Meta WhatsApp Cloud API configuration is missing Phone Number ID or Access Token.");
                    return false;
                }

                $templateName = empty($sender) ? 'booking_notification' : $sender;

                if ($templateName === 'text') {
                    // Send free-form text message (only works if user initiated contact within 24 hours)
                    $response = Http::withToken($token)->post("https://graph.facebook.com/v18.0/{$sid}/messages", [
                        'messaging_product' => 'whatsapp',
                        'recipient_type' => 'individual',
                        'to' => $cleanPhone,
                        'type' => 'text',
                        'text' => [
                            'body' => $message
                        ]
                    ]);
                } else {
                    // Send template message with parameters
                    $response = Http::withToken($token)->post("https://graph.facebook.com/v18.0/{$sid}/messages", [
                        'messaging_product' => 'whatsapp',
                        'recipient_type' => 'individual',
                        'to' => $cleanPhone,
                        'type' => 'template',
                        'template' => [
                            'name' => $templateName,
                            'language' => [
                                'code' => 'en'
                            ],
                            'components' => [
                                [
                                    'type' => 'body',
                                    'parameters' => [
                                        ['type' => 'text', 'text' => $booking->name],
                                        ['type' => 'text', 'text' => $roomName],
                                        ['type' => 'text', 'text' => $checkIn],
                                        ['type' => 'text', 'text' => $checkOut],
                                        ['type' => 'text', 'text' => '₹' . number_format($booking->total_price, 2)],
                                        ['type' => 'text', 'text' => $approveUrl],
                                        ['type' => 'text', 'text' => $rejectUrl]
                                    ]
                                ]
                            ]
                        ]
                    ]);
                }

                if ($response->successful()) {
                    Log::info("WhatsApp notification sent via Meta Cloud API successfully.");
                    return true;
                } else {
                    Log::error("Meta WhatsApp Cloud API request failed: " . $response->body());
                    return false;
                }
            }
        } catch (\Exception $e) {
            Log::error("Exception occurred while sending WhatsApp message: " . $e->getMessage());
        }

        return false;
    }
}
