<?php
// app/Services/GoogleAccessToken.php
namespace App\Services;

use Illuminate\Support\Facades\Cache;

class GoogleAccessToken
{
    public static function get(): string
    {
        return Cache::remember('gapi_token', now()->addMinutes(55), function () {
            $saPath = base_path(env('FIREBASE_CREDENTIALS'));
            $sa = json_decode(file_get_contents($saPath), true);

            $jwtHeader = ['alg' => 'RS256', 'typ' => 'JWT'];
            $jwtClaim = [
                'iss' => $sa['client_email'],
                'scope' => 'https://www.googleapis.com/auth/datastore',
                'aud' => 'https://oauth2.googleapis.com/token',
                'exp' => time() + 3600,
                'iat' => time(),
            ];
            $b64 = fn($d) => rtrim(strtr(base64_encode(json_encode($d)), '+/', '-_'), '=');
            $input = $b64($jwtHeader) . '.' . $b64($jwtClaim);
            openssl_sign($input, $sig, $sa['private_key'], 'sha256WithRSAEncryption');
            $jwt = $input . '.' . rtrim(strtr(base64_encode($sig), '+/', '-_'), '=');

            $ch = curl_init('https://oauth2.googleapis.com/token');
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
                CURLOPT_POSTFIELDS => http_build_query([
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwt,
                ]),
                CURLOPT_ENCODING => 'gzip', // allow gzip
            ]);
            $resp = curl_exec($ch);
            curl_close($ch);

            $json = json_decode($resp, true);
            if (empty($json['access_token'])) {
                throw new \Exception('Failed to fetch access token: ' . $resp);
            }
            return $json['access_token'];
        });
    }
}
