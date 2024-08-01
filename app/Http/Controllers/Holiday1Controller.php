<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Holiday;

class Holiday1Controller extends Controller
{
    public function fetchHolidays()
    {
        // Define a unique cache key
        $cacheKey = 'holidays_' . date('Y');

        // Check if the data is already cached
        $holidays = Cache::get($cacheKey);

        if (!$holidays) {
            // If not cached, fetch from the API
            $apiKey = env('CALENDARIFIC_API_KEY');
            $client = new \GuzzleHttp\Client();

            try {
                $response = $client->request('GET', 'https://calendarific.com/api/v2/holidays', [
                    'query' => [
                        'api_key' => $apiKey,
                        'country' => 'US',
                        'year' => date('Y')
                    ]
                ]);

                $holidays = json_decode($response->getBody()->getContents(), true);

                // Check if the response contains the 'response' key
                if (isset($holidays['response']['holidays'])) {
                    // Save the data in the cache for a specified time
                    Cache::put($cacheKey, $holidays, now()->addHours(24)); // Cache for 24 hours

                    // Save holidays to the database (optional)
                    foreach ($holidays['response']['holidays'] as $holiday) {
                        Holiday::updateOrCreate(
                            ['name' => $holiday['name'], 'date' => $holiday['date']['iso']],
                            ['type' => $holiday['type'][0]]
                        );
                    }
                } else {
                    // Log error and set holidays to an empty array
                    \Log::error('API response does not contain holidays.');
                    $holidays = ['response' => ['holidays' => []]];
                }
            } catch (\Exception $e) {
                \Log::error('Error fetching holidays: ' . $e->getMessage());
                return redirect()->route('holidays.index1')->with('error', 'Failed to fetch holidays. Please check the logs for more details.');
            }
        }

        // Pass the holidays data to the view
        return view('holidays.index1', ['holidays' => $holidays['response']['holidays']]);
    }

    public function index()
    {
        // Retrieve cached holidays
        $holidays = Cache::get('holidays_' . date('Y'));

        // If cache is empty, fetch holidays from the database
        if (!$holidays) {
            $holidays = Holiday::all();
        } else {
            // Ensure the 'response' key exists
            if (!isset($holidays['response']['holidays'])) {
                $holidays = ['response' => ['holidays' => []]];
            }
        }

        // Pass the holidays data to the view
        return view('holidays.index1', ['holidays' => $holidays['response']['holidays']]);
    }
}
