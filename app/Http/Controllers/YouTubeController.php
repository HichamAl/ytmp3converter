<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class YouTubeController extends Controller
{
    public function convert(Request $request)
    {
        // Extract video ID from the request
        $id = $request->input('id');

        if (!$id) {
            return response()->json(['msg' => 'Missing YouTube video ID', 'code' => 400, 'status' => 'fail'], 400);
        }

        // Set up the API request
        $client = new Client();
        try {
            $response = $client->request('GET', 'https://youtube-mp36.p.rapidapi.com/dl', [
                'query' => ['id' => $id],
                'headers' => [
                    'x-rapidapi-host' => 'youtube-mp36.p.rapidapi.com',
                    'x-rapidapi-key' => env('RAPID_API_KEY'),
                ],
            ]);

            // Return the response as JSON
            return response($response->getBody(), 200)
                ->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response()->json([
                'msg' => 'API request failed: ' . $e->getMessage(),
                'code' => 500,
                'status' => 'fail'
            ], 500);
        }
    }
}
