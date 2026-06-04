<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class DarelimanAuthService
{
    /**
     * Authenticate a user against the Dareliman API.
     *
     * @param string $email
     * @param string $password
     * @return array Returns ['success' => bool, 'user' => array|null, 'token' => string|null, 'message' => string]
     */
    public function authenticate(string $email, string $password): array
    {
        $apiUrl = config('services.dareliman.api_url', 'https://sipdei.dareliman.tech/web_api/app_login');

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'Accept' => '*/*',
                    'Content-Type' => 'application/json',
                ])
                ->post($apiUrl, [
                    'email' => $email,
                    'password' => $password,
                ]);

            // Check if the HTTP request itself was successful
            if (!$response->successful()) {
                Log::warning('Dareliman API returned non-success HTTP status', [
                    'status' => $response->status(),
                    'email' => $email,
                ]);
                return [
                    'success' => false,
                    'user' => null,
                    'token' => null,
                    'message' => 'Gagal menghubungi server autentikasi. Silakan coba lagi.',
                ];
            }

            $data = $response->json();

            // Response is an array — take index [0]
            if (!is_array($data) || empty($data)) {
                return [
                    'success' => false,
                    'user' => null,
                    'token' => null,
                    'message' => 'Format respons tidak valid. Silakan coba lagi.',
                ];
            }

            $result = is_array($data[0] ?? null) ? $data[0] : $data;

            // Check status field
            if (!($result['status'] ?? false)) {
                return [
                    'success' => false,
                    'user' => null,
                    'token' => null,
                    'message' => $result['message'] ?? 'Email atau password salah.',
                ];
            }

            // Extract user data
            $user = $result['user'] ?? null;
            if (!$user || !isset($user['id'])) {
                return [
                    'success' => false,
                    'user' => null,
                    'token' => null,
                    'message' => 'Data pengguna tidak ditemukan dalam respons.',
                ];
            }

            return [
                'success' => true,
                'user' => $user,
                'token' => $result['token'] ?? null,
                'newtoken' => $result['newtoken'] ?? null,
                'message' => $result['message'] ?? 'Login berhasil.',
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Dareliman API connection error', [
                'error' => $e->getMessage(),
                'email' => $email,
            ]);
            return [
                'success' => false,
                'user' => null,
                'token' => null,
                'message' => 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda dan coba lagi.',
            ];
        } catch (Exception $e) {
            Log::error('Dareliman API unexpected error', [
                'error' => $e->getMessage(),
                'email' => $email,
            ]);
            return [
                'success' => false,
                'user' => null,
                'token' => null,
                'message' => 'Terjadi kesalahan yang tidak terduga. Silakan coba lagi nanti.',
            ];
        }
    }
}
