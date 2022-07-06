<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request)
    {
        $code = 200;
        $status = 'OK';
        $response = [
            'code' => $code,
            'status' => $status,
            'data' => [
                'user' => $request->user()
            ]
        ];
        return response()->json($response, $code);
    }
}
