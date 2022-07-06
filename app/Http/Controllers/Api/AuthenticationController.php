<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'user_auth' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::auth(['user_auth' => $validated['user_auth']])->first();

        $code = 401;
        $response = [
            'code' => $code,
            'status' => 'UNAUTHORIZED',
        ];

        if ($user) {
            if (Hash::check($validated['password'], $user->password)) {
                $token = '';
                $studentTeacherAbilities = ['place-orders', 'cancel-orders'];
                $sellerAbilities = ['manage-products', 'take-orders', 'decline-orders', 'ready-orders'];
                $tellerAbilities = ['manage-payments'];

                if ($user->student != null || $user->teacher != null) {
                    $token = $user->createToken(env('SECRET_KEY', 'secret'), $studentTeacherAbilities);
                }

                if ($user->seller != null) {
                    $token = $user->createToken(env('SECRET_KEY', 'secret'), $sellerAbilities);
                }

                if ($user->teller != null) {
                    $token = $user->createToken(env('SECRET_KEY', 'secret'), $tellerAbilities);
                }

                $code = 200;
                $response['code'] = $code;
                $response['status'] = 'OK';
                $response['data'] = [
                    'signature' => $token->plainTextToken,
                    'expires_in' => (int)env('EXPIRE_TOKEN_TIME', 43800)
                ];
                return response()->json($response, $code);
            }
            $response['data'] = ['message' => 'Password salah.'];
            return response()->json($response, $code);
        }
        $response['data'] = ['message' => 'Pengguna tidak ditemukan.'];
        return response()->json($response, $code);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        $code = 200;
        $response = [
            'code' => $code,
            'status' => 'OK',
            'data' => [
                'message' => 'Berhasil Logout.'
            ]
        ];
        return response()->json($response, $code);
    }

    public function test(Request $request)
    {
        return response()->json($request);
    }
}
