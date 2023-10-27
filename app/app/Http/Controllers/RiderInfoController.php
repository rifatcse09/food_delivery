<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RestaurantInfo;
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

    public function getRiders($id) {

        $restaurent = RestaurantInfo::find($id); 

        $rider = RiderInfo::get();
 
        $restaurentLat = $restaurent->lat;
        $restaurentLong = $restaurent->long;

        $nearestRider = RiderInfo::selectRaw('*, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))) AS distance')
        ->addBinding($restaurentLat, 'select')
        ->addBinding($restaurentLong, 'select')
        ->orderBy('distance')
        ->first();
  
        return response()->json(['status' => 'success', 'data' => $nearestRider],200);
      }

}
