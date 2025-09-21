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
            $firebase = (new Factory)->withServiceAccount(base_path('storage/app/time2play.json'));
        } else {
            throw new \Exception('Unsupported Firebase project.');
        }

        $this->messaging = $firebase->createMessaging();
    }


    public function sendNotification($token, $title, $body, $customData = [])
    {
        // Ensure the custom data is an associative array of key-value pairs
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification([
                'title' => $title,
                'body' => $body
            ])
            // ->withData($customData)  // Content_available and other data passed here
            ->withApnsConfig([
                'headers' => [
                    'apns-priority' => '10'
                ],
                'payload' => [
                    'aps' => [
                        'content-available' => 1  // Set content_available to true for iOS
                    ],
                    'customData' => [
                        'customData' => $customData  // Set content_available to true for iOS
                    ],
                ]
            ])
            ->withAndroidConfig([
                'priority' => 'high'
            ]);

        // Send the notification
        $this->messaging->send($message);
    }
}
