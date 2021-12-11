<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);


        $email = $request->email;
        $password = $request->password;

        $client = new Client();
        try {

            $request = $client->post(config('service.passport.login_endpoint'), [
                "form_params" => [
                    "client_secret" => config('service.passport.client_secret'),
                    "grant_type" => "password",
                    "client_id" => config('service.passport.client_id'),
                    "username" => $email,
                    "password" => $password
                ]
            ]);
            return $request;
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);

        $checkuser = User::where('email', $request->input('email'))->first();
        if(!empty($checkuser)){
            return response()->json(['status' => 'user exist']);
        }

        try {
            $user = User::create([
                'username' =>  $request->input('username'),
                'email' =>  $request->input('email'),
                'password' => Hash::make($request->input('password'))
            ]);

            if ($user) {
                // Will call login method
                return $this->login($request);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        try {
            auth()->user()->tokens()->each(function ($token) {
                $token->delete();
            });

            return response()->json(['status' => 'success', 'message' => 'Logged out successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
