<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\FirebaseService;

class NotificationController extends Controller
{
    protected $firebaseService;

    // No need to inject the service here, since we will instantiate it based on the project
    public function __construct()
    {
        // This constructor can remain empty or be removed if not needed
    }

    public function sendNotification(Request $request)
    {
        // Create a new FirebaseService instance for 'mooreEquine'
        $firebaseService = new FirebaseService('bandmate');

        // Validate incoming request
        $request->validate([
            'fcm_token' => 'required|string',
            'title' => 'required|string',
            'body' => 'required|string',
            'data' => 'nullable|array',  // This allows the user to send custom data as an array
        ]);

        // Get data from the request
        $fcmToken = $request->input('fcm_token');
        $title = $request->input('title');
        $body = $request->input('body');
        $contentAvailable = "true"; // Convert boolean to string as required by Firebase
        $customData = $request->input('data', []); // Default to an empty array if not provided

        // Call the FirebaseService to send the notification
        try {
            $firebaseService->sendBanmateNotification($fcmToken, $title, $body, $customData);
            return response()->json([
                'success' => true,
                'message' => 'Notification sent successfully!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
