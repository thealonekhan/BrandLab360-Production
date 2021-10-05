<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::post('/contact', function (Request $request) {
    // Log::info($request->all());
    $validator = Validator::make($request->all(), [
        'name'       => 'required|min:1|max:256',
        'email'      => 'required|email|max:256',
        'message'    => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'error' => 'Message not sent!',
        ], 400);
    } else {
        return response()->json([
            'message' => 'Message sent Successfully',
        ]);
    }

    
});