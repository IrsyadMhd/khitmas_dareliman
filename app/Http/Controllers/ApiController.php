<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Get all registration data.
     * Protected by API Key.
     */
    public function getPendaftar(Request $request)
    {
        // Get the API key from header (X-API-KEY) or query string (?api_key=)
        $apiKey = $request->header('X-API-KEY') ?? $request->query('api_key');
        
        $validKey = env('APP_API_KEY', 'DARELIMAN-KHITMAS-2026-SECURE'); // Default fallback key if not set in .env

        if (!$apiKey || $apiKey !== $validKey) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Invalid or missing API Key.'
            ], 401);
        }

        $data = Pendaftaran::all();

        return response()->json([
            'success' => true,
            'count' => $data->count(),
            'data' => $data
        ]);
    }
}
