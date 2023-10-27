<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RestaurantInfo;
use App\Models\RiderInfo;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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

        $nearestRider = RiderInfo::select("rider_infos.id"

        ,DB::raw("6371 * acos(cos(radians(" . $restaurentLat . ")) 

        * cos(radians(rider_infos.lat)) 

        * cos(radians(rider_infos.long) - radians(" . $restaurentLong . ")) 

        + sin(radians(" .$restaurentLat. ")) 

        * sin(radians(rider_infos.lat))) AS distance"))

        ->groupBy("rider_infos.id")
        ->orderBy("distance")
        ->get();
  
        return response()->json(['status' => 'success', 'data' => $nearestRider],200);
      }

}
