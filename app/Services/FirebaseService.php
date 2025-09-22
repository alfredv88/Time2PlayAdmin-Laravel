<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Exception\Messaging\NotFound;
use Kreait\Firebase\Exception\Messaging\InvalidArgument;


class FirebaseService
{
    protected $messaging;

    public function __construct($project)
    {
        if ($project === 'time2play') {
            $firebase = (new Factory)->withServiceAccount(base_path('bandmates.json'));
        } else {
            throw new \Exception('Unsupported Firebase project.');
        }

        $this->messaging = $firebase->createMessaging();
    }


    public function sendNotification($token, $title, $body, $customData = [])
    {
        try {
            // Ensure the custom data is an associative array of key-value pairs
            $message = CloudMessage::withTarget('token', $token)
                ->withNotification([
                    'title' => $title,
                    'body' => $body
                ])
                ->withData($customData)
                ->withApnsConfig([
                    'headers' => [
                        'apns-priority' => '10'
                    ],
                    'payload' => [
                        'aps' => [
                            'content-available' => 1
                        ]
                    ]
                ])
                ->withAndroidConfig([
                    'priority' => 'high'
                ]);

            // Send the notification
            $this->messaging->send($message);
            Log::info('Notification sent successfully', ['token' => $token, 'title' => $title]);
            
        } catch (NotFound $e) {
            Log::error('Firebase token not found', ['token' => $token, 'error' => $e->getMessage()]);
            throw new \Exception('Token de notificación no válido');
        } catch (InvalidArgument $e) {
            Log::error('Invalid Firebase argument', ['error' => $e->getMessage()]);
            throw new \Exception('Argumentos de notificación inválidos');
        } catch (\Exception $e) {
            Log::error('Firebase notification error', ['error' => $e->getMessage()]);
            throw new \Exception('Error al enviar notificación: ' . $e->getMessage());
        }
    }
}
