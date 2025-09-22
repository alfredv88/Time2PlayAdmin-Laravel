<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }



    public function updateAdminPassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        // Assume admin is logged in and session holds their email
        $email = session('admin_email');

        if (!$email) {
            return redirect('login')->with('error', 'Session expired. Please log in again.');
        }

        $firestore = (new Factory)->withServiceAccount(base_path('bandmates.json'))->createFirestore();
        $admins = $firestore->database()->collection('admin')->where('email', '=', $email)->documents();

        if ($admins->isEmpty()) {
            return redirect()->back()->with('error', 'Admin not found.');
        }

        foreach ($admins as $admin) {
            $adminData = $admin->data();
            $storedPassword = $adminData['password'];

            if ($storedPassword !== $request->old_password) {
                return redirect()->back()->withErrors(['old_password' => 'Old password is incorrect.']);
            }

            // Update password in Firestore
            $admin->reference()->update([
                ['path' => 'password', 'value' => $request->new_password]
            ]);
        }

        return redirect()->back()->with('status', 'Password updated successfully.');
    }



    public function updatePrivacyPolicy(Request $request)
    {
        $request->validate([
            'privacy_pdf' => 'required|file|mimes:pdf|max:5120', // 5MB max
        ]);

        $file = $request->file('privacy_pdf');
        $filename = 'privacy-policy-' . time() . '.' . $file->getClientOriginalExtension();

        // Save to public/privacy-policies directory
        $file->move(public_path('privacy-policies'), $filename);

        // Generate full public URL
        $url = url('public/privacy-policies/' . $filename);

        // Save info to Firestore
        $firestore = (new \Kreait\Firebase\Factory)->withServiceAccount(base_path('bandmates.json'))->createFirestore();
        $firestore->database()->collection('policyDocuments')->add([
            'file_name' => $filename,
            'url' => $url,
            'uploaded_at' => now()->toDateTimeString(),
        ]);

        return redirect('policies')->with('status', 'Privacy Policy uploaded and stored successfully.');
    }



    public function editPolicyDocument($id)
    {
        // $firestore = (new \Kreait\Firebase\Factory)->withServiceAccount(base_path('bandmates.json'))->createFirestore();
        // $document = $firestore->database()->collection('policyDocuments')->document($id)->snapshot();

        // if (!$document->exists()) {
        //     return redirect('policies')->with('error', 'Document not found.');
        // }

        // $data = $document->data();
        // $data['id'] = $id;

        return view('add-doc');
    }



    public function updatePolicyDocument(Request $request, $id)
    {
        $request->validate([
            'privacy_pdf' => 'required|file|mimes:pdf|max:5120',
        ]);

        $file = $request->file('privacy_pdf');
        $filename = 'policy-' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('privacy-policies'), $filename);

        $url = url('public/privacy-policies/' . $filename);

        $firestore = (new \Kreait\Firebase\Factory)->withServiceAccount(base_path('bandmates.json'))->createFirestore();
        $firestore->database()->collection('policyDocuments')->document($id)->update([
            ['path' => 'file_name', 'value' => $filename],
            ['path' => 'url', 'value' => $url],
            ['path' => 'uploaded_at', 'value' => now()->toDateTimeString()],
        ]);

        return redirect('policies')->with('status', 'Policy document updated successfully!');
    }




    public function changePassword(Request $request)
    {
        // $request->validate([
        //     'newpassword' => 'required'
        // ]);
        // $email = session('otp_email');
        // if (!$email) {
        //     return view('auth.newPassword')->with('error', 'Session expired. Please try again.');
        // }

        // $password = $request->newpassword; // Securely hash the password

        // // Firestore setup
        // $firestore = (new Factory)->withServiceAccount(base_path('bandmates.json'))->createFirestore();
        // $database = $firestore->database();

        // // Find admin document by email
        // $admins = $database->collection('admin')->where('email', '=', $email)->documents();

        // if ($admins->isEmpty()) {
        //     return view('auth.newPassword')->with('error', 'Admin account not found.');
        // }

        // // Update password in Firestore (assuming one match only)
        // foreach ($admins as $admin) {
        //     $admin->reference()->update([
        //         ['path' => 'password', 'value' => $password]
        //     ]);
        // }

        // // Clear session and redirect
        // session()->forget(['otp', 'otp_email']);

        return redirect()->route('login')->with('status', 'Password updated successfully.');
    }



    public function settingsUpdate(Request $request)
    {
        // Validate the input data
        $request->validate([
            'current_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6',
            'confirm_password' => 'required|string|same:new_password',
        ]);

        // Initialize Firestore
        $factory = (new Factory)->withServiceAccount(__DIR__ . '/scrubs.json');
        $database = $factory->createFirestore()->database();

        // Retrieve the current admin document
        $adminQuery = $database->collection('admin')->document('hFlalxH0ICAgOO79rMLg');
        $adminSnapshot = $adminQuery->snapshot();

        // Check if document exists and get the current stored password
        if ($adminSnapshot->exists()) {
            $adminData = $adminSnapshot->data();
            $storedPassword = $adminData['password']; // Assuming the password field in Firestore is named 'password'

            // Compare the current password input with the stored password
            if ($storedPassword === $request->input('current_password')) {
                // Passwords match, proceed to update the password

                // Update the document with the new password
                $newPassword = $request->input('new_password');
                $adminQuery->set([
                    'password' => $newPassword
                ], ['merge' => true]);

                // Redirect back with success message
                return redirect()->back()->with('success', 'Password updated successfully.');
            } else {
                // Passwords don't match, redirect back with an error message
                return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
        } else {
            // Admin document not found
            return redirect()->back()->withErrors(['error' => 'Admin document not found.']);
        }
    }




    public function settings(Request $request)
    {
        session(['tab' => 'settings']);
        return view('auth.settings');
    }


    public function adminLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $email    = strtolower(trim($request->input('email')));
        $password = $request->input('password');

        try {
            // Read service account
            $saPath = base_path(env('FIREBASE_CREDENTIALS'));
            $sa     = json_decode(file_get_contents($saPath), true);
            if (!$sa) {
                throw new \Exception("Invalid service account file");
            }

            // 1) Get access token
            $jwtHeader = ['alg' => 'RS256', 'typ' => 'JWT'];
            $jwtClaim  = [
                'iss'   => $sa['client_email'],
                'scope' => 'https://www.googleapis.com/auth/datastore',
                'aud'   => 'https://oauth2.googleapis.com/token',
                'exp'   => time() + 3600,
                'iat'   => time(),
            ];
            $base64Url = fn($d) => rtrim(strtr(base64_encode(json_encode($d)), '+/', '-_'), '=');
            $segments  = [$base64Url($jwtHeader), $base64Url($jwtClaim)];
            openssl_sign(implode('.', $segments), $sig, $sa['private_key'], 'sha256WithRSAEncryption');
            $jwt = implode('.', $segments) . '.' . rtrim(strtr(base64_encode($sig), '+/', '-_'), '=');

            $ch = curl_init('https://oauth2.googleapis.com/token');
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
                CURLOPT_POSTFIELDS     => http_build_query([
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion'  => $jwt,
                ]),
            ]);
            $resp = curl_exec($ch);
            curl_close($ch);
            $tokenJson   = json_decode($resp, true);
            $accessToken = $tokenJson['access_token'] ?? null;
            if (!$accessToken) {
                throw new \Exception("Failed to get access token: " . $resp);
            }

            // 2) Query Firestore admin collection
            $projectId = env('FIREBASE_PROJECT_ID');
            $query = [
                'structuredQuery' => [
                    'from' => [['collectionId' => 'admin']],
                    'where' => [
                        'fieldFilter' => [
                            'field' => ['fieldPath' => 'email'],
                            'op'    => 'EQUAL',
                            'value' => ['stringValue' => $email],
                        ],
                    ],
                    'limit' => 1,
                ],
            ];

            $ch = curl_init("https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents:runQuery");
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_HTTPHEADER     => [
                    'Authorization: Bearer ' . $accessToken,
                    'Content-Type: application/json',
                ],
                CURLOPT_POSTFIELDS     => json_encode($query),
            ]);
            $resp = curl_exec($ch);
            curl_close($ch);
            $rows = json_decode($resp, true);

            $doc = null;
            foreach ($rows as $row) {
                if (!empty($row['document'])) {
                    $doc = $row['document'];
                    break;
                }
            }
            if (!$doc) {
                return back()->with('error', 'Invalid credentials')->withInput();
            }

            $docId  = basename($doc['name']);
            $fields = $doc['fields'] ?? [];
            $stored = $fields['password']['stringValue'] ?? null;

            if (!$stored) {
                return back()->with('error', 'Password not set')->withInput();
            }

            // 🔑 For now, since you said password is stored as plain text
            if ($stored !== $password) {
                return back()->with('error', 'Invalid credentials')->withInput();
            }

            // 3) Success: store uid in session
            session(['admin_id' => $docId]);
            $request->session()->regenerate();

            return redirect('/')->with('status', 'Login successful');
        } catch (\Throwable $e) {
            return back()->with('error', app()->environment('local') ? $e->getMessage() : 'Something went wrong')->withInput();
        }
    }

    // public function adminLogin(Request $request)
    // {
    //     try{
    //         $factory = (new Factory)->withServiceAccount(__DIR__.'/bible.json');
    //         $database = $factory->createFirestore()->database();
    //         $auth = $factory->createAuth();

    //         $email = $request->email;
    //         $clearTextPassword = $request->password;

    //         $signInResult = $auth->signInWithEmailAndPassword($email, $clearTextPassword);
    //         $user = $auth->getUserByEmail($email);
    //         $uid = $user->uid;
    //         $displayName = $user->displayName;

    //         session(['uid' => $user->uid]);
    //         session(['email' => $user->email]);
    //         session(['displayName' => $user->displayName]);

    //         if($signInResult){
    //             return redirect('/');
    //         }else{
    //             return redirect('login')."del";
    //         }
    //     }
    //     catch (\Kreait\Firebase\Auth\SignIn\FailedToSignIn $e)
    //     {
    //         $message = $e->getMessage();
    //         $request->session()->flash('message', $message);
    //         return redirect()->back();
    //     }
    //     catch (\Kreait\Firebase\Exception\InvalidArgumentException $e)
    //     {
    //         $message = $e->getMessage();
    //         $request->session()->flash('message', $message);
    //         return redirect()->back();
    //     }
    // }


    public function logout()
    {
        Session::flush();
        return redirect('login');
    }


    public function forgotPassword()
    {
        return view('auth.forgotPassword');
    }


    public function verifyEmail(Request $request)
    {
        // $request->validate([
        //     'email' => 'required|email',
        // ]);

        // $email = $request->email;
        $email = "sabir@gmail.com";

        // // Connect to Firestore
        // $firestore = (new Factory)
        //     ->withServiceAccount(base_path('bandmates.json'))
        //     ->createFirestore();

        // $admins = $firestore->database()
        //     ->collection('admin')
        //     ->where('email', '=', $email)
        //     ->documents();

        // // Check if email exists
        // if ($admins->isEmpty()) {
        //     return redirect()->back()->with('error', 'Email not found in our records.');
        // }

        // // Generate OTP
        // $otp = rand(1000, 9999);
        $otp = 1234;
        session(['otp' => $otp, 'otp_email' => $email]);

        // $subject = 'Your OTP Code for Bandmates';
        // $message = "Your OTP is: <strong>{$otp}</strong>. It will expire in 10 minutes.";

        // $response = Http::asForm()->post('https://Apis.appistaan.com/mailapi/index.php?key=sk286292djd926d', [
        //     'to' => $email,
        //     'subject' => $subject,
        //     'message' => $message
        // ]);

        // if ($response->json('success') == "1") {
        return view('auth.otp')->with('message', 'OTP sent to your email address.');
        // } else {
        //     return view('auth.forgotPassword')->with('error', 'Failed to send email: ' . $response->json('message'));
        // }

    }


    public function resendOtp()
    {
        $email = session('otp_email');

        if (!$email) {
            return redirect()->back()->with('error', 'No email found in session. Please try again.');
        }

        // Generate a new OTP
        $otp = rand(1000, 9999);
        session(['otp' => $otp]); // Replacing the old OTP with the new one

        // Email content
        $subject = 'Your OTP Code for Bandmates (Resend)';
        $message = "Your new OTP is: <strong>{$otp}</strong>. It will expire in 10 minutes.";

        // Send email using API
        $response = Http::asForm()->post('https://Apis.appistaan.com/mailapi/index.php?key=sk286292djd926d', [
            'to' => $email,
            'subject' => $subject,
            'message' => $message
        ]);

        if ($response->json('success') == "1") {
            return view('auth.otp')->with('message', 'OTP resent to your email address.');
        } else {
            return view('auth.forgotPassword')->with('error', 'Failed to resend email: ' . $response->json('message'));
        }
    }




    public function otp()
    {
        return view('auth.otp');
    }


    public function verify()
    {
        return view('auth.newPassword');
    }


    public function newPassword()
    {
        return view('auth.newPassword');
    }
}
