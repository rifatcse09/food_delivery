<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RiderInfo;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class RiderInfoController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'lat' => 'required',
            'long' => 'required',
            'capture_time' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error','errors' => $validator->messages()],Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = RiderInfo::create($request->all());

        return response()->json(['status' => 'success', 'data' => $data],200);
    }


    public function haversineDistance($lat1, $lon1, $lat2, $lon2) {
        // Radius of the Earth in kilometers (mean value)
        $earthRadius = 6371;
    
        // Convert latitude and longitude from degrees to radians
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);
    
        // Haversine formula
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;
        $a = sin($dlat/2) * sin($dlat/2) + cos($lat1) * cos($lat2) * sin($dlon/2) * sin($dlon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;
    
        return $distance;
    }
    
    // Coordinates of two locations
    // $lat1 = 52.5200;  // Latitude of the first location
    // $lon1 = 13.4050;  // Longitude of the first location
    // $lat2 = 48.8566;  // Latitude of the second location
    // $lon2 = 2.3522;   // Longitude of the second location
    
    // // Calculate the distance between the two locations
    // $distance = haversineDistance($lat1, $lon1, $lat2, $lon2);
    
    // echo "The distance between the two locations is approximately " . round($distance, 2) . " kilometers.";
}
