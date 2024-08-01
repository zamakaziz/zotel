<?php 
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http; // Import the Http facade

class HolidayController extends Controller
{
    public function fetchHolidays()
    {
        // Get selected country and year from request, defaulting to 'US' and current year
        $country = request('country', 'US');
        $year = request('year', date('Y'));

        // Define a unique cache key based on country and year
        $cacheKey = 'holidays_' . $country . '_' . $year;

        // Attempt to get cached holidays
        $holidays = Cache::get($cacheKey);


     if (!$holidays) {
            // If not cached, fetch from the API
            $apiKey = env('CALENDARIFIC_API_KEY');
            $response = Http::get('https://calendarific.com/api/v2/holidays', [
                'api_key' => $apiKey,
                'country' => $country,
                'year' => $year
            ]);

            $holidays = $response->json();
            // Cache the holidays data for 24 hours
            Cache::put($cacheKey, $holidays, now()->addHours(24));
        } else {
            // Extract the holidays from the cached data
            $holidays = $holidays['response']['holidays'];
        }
         return view('holidays.index', ['holidays' => $holidays]);
    }
}

