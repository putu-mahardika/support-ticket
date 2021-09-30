<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email:rfc,dns', 'exists:users,email'],
            'password' => ['required', 'min:8']
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'messages' => $validator->errors(),
                'data' => []
            ]);
        }

        $user = User::where('email', $request->email)->first();
        $token = null;
        if (Hash::check($request->password, $user->password)) {
            $token = $user->createToken('apiToken')->plainTextToken;
        }

        if (empty($token)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid login!',
            ]);
        }
        else {
            return response()->json([
                'success' => true,
                'message' => '',
                'data' => compact('user'),
                'token' => $token,
                'token_expire' => now()->addHour()
            ]);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        return response()->json([
            'success' => true,
            'message' => '',
            'data' => []
        ]);
    }
}
