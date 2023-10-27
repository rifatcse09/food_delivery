<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RestaurantInfo;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class RestaurantInfoController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'lat' => 'required',
            'long' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error','errors' => $validator->messages()],Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = RestaurantInfo::create($request->all());

        return response()->json(['status' => 'success', 'data' => $data],200);
    }
    
}
