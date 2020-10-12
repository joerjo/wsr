<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string|unique:users,phone',
            'document_number' => 'required|string|size:10',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => [
                'code' => 422,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ]], 422);
        }

        $user = new User();
        $user->password = Hash::make($request->get('password'));
        $user->last_name = $request->get('last_name');
        $user->first_name = $request->get('first_name');
        $user->document_number = $request->get('document_number');
        $user->phone = $request->get('phone');
        $user->save();

        return response()->json()->setStatusCode(204);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => [
                'code' => 422,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ]], 422);
        }

        if (Auth::attempt($request->only('phone', 'password'))) {
            $token = md5(Str::random(16));
            $user = Auth::user();
            $user->api_token = $token;
            $user->save();
            return response()->json(['data' => ['token' => $token]]);
        }

        return response()->json(['error' => [
            'code' => 401,
            'message' => 'Unauthorized',
            'errors' => ['phone or password incorrect']
        ]], 401);
    }
}
