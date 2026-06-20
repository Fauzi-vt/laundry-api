<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    /**
     * Send status update notification to the user associated with a transaction.
     */
    public static function sendStatusNotification(Transaction $transaction)
    {
        $user = $transaction->user;
        if (!$user || !$user->fcm_token) {
            Log::info("FCM Skip: User has no FCM token for Transaction ID: " . $transaction->id);
            return;
        }

        $invoice = $transaction->invoice_code;
        $status = strtolower($transaction->status);

        $messages = [
            'baru'    => "Pesanan $invoice telah dibuat.",
            'cuci'    => "🧺 Pesanan $invoice sedang dicuci.",
            'kering'  => "💨 Pesanan $invoice sedang dikeringkan.",
            'setrika' => "👔 Pesanan $invoice sedang disetrika.",
            'selesai' => "✅ Pesanan $invoice selesai & siap diambil! 🎉",
            'diambil' => "🎉 Pesanan $invoice telah diambil. Terima kasih!",
        ];

        $titles = [
            'baru'    => "Pesanan Baru",
            'cuci'    => "Sedang Dicuci",
            'kering'  => "Sedang Dikeringkan",
            'setrika' => "Sedang Disetrika",
            'selesai' => "Siap Diambil! 🎉",
            'diambil' => "Pesanan Diambil",
        ];

        $title = $titles[$status] ?? "Status Diperbarui";
        $body = $messages[$status] ?? "Status pesanan $invoice diperbarui menjadi $status.";

        self::sendNotification($user->fcm_token, $title, $body, [
            'transaction_id' => (string) $transaction->id,
            'invoice_code' => $transaction->invoice_code,
            'status' => $transaction->status,
        ]);
    }

    /**
     * Send raw notification to FCM token.
     */
    public static function sendNotification(string $fcmToken, string $title, string $body, array $data = [])
    {
        try {
            $accessToken = self::getAccessToken();
            if (!$accessToken) {
                Log::error("FCM Error: Failed to get OAuth2 access token.");
                return;
            }

            $projectId = self::getProjectId();
            if (!$projectId) {
                Log::error("FCM Error: Project ID not configured.");
                return;
            }

            $payload = [
                'message' => [
                    'token' => $fcmToken,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => $data,
                    'android' => [
                        'notification' => [
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        ]
                    ]
                ]
            ];

            $response = Http::withToken($accessToken)
                ->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", $payload);

            if ($response->failed()) {
                Log::error("FCM Send Error: " . $response->body());
            } else {
                Log::info("FCM Sent successfully to token: " . substr($fcmToken, 0, 15) . "...");
            }
        } catch (\Exception $e) {
            Log::error("FCM Service Exception: " . $e->getMessage());
        }
    }

    /**
     * Get OAuth2 Access Token using Google Service Account private key
     */
    private static function getAccessToken()
    {
        $serviceAccountPath = storage_path('app/firebase-service-account.json');
        if (!file_exists($serviceAccountPath)) {
            Log::warning("FCM Warning: Service account file not found at $serviceAccountPath");
            return null;
        }

        $serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);
        if (!$serviceAccount || !isset($serviceAccount['private_key'])) {
            return null;
        }

        // Generate JWT
        $now = time();
        $payload = [
            'iss' => $serviceAccount['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600
        ];

        $header = ['alg' => 'RS256', 'typ' => 'JWT'];

        $base64UrlHeader = self::base64UrlEncode(json_encode($header));
        $base64UrlPayload = self::base64UrlEncode(json_encode($payload));

        $signature = '';
        $success = openssl_sign(
            $base64UrlHeader . "." . $base64UrlPayload,
            $signature,
            $serviceAccount['private_key'],
            'sha256WithRSAEncryption'
        );

        if (!$success) {
            return null;
        }

        $base64UrlSignature = self::base64UrlEncode($signature);
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        // POST request to exchange JWT for an Access Token
        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ]);

        if ($response->failed()) {
            Log::error("FCM OAuth2 Error: " . $response->body());
            return null;
        }

        return $response->json('access_token');
    }

    private static function getProjectId()
    {
        $serviceAccountPath = storage_path('app/firebase-service-account.json');
        if (!file_exists($serviceAccountPath)) {
            return env('FIREBASE_PROJECT_ID');
        }
        $serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);
        return $serviceAccount['project_id'] ?? env('FIREBASE_PROJECT_ID');
    }

    private static function base64UrlEncode($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }
}
