<?php

namespace App\Traits;
use Illuminate\Support\Facades\Http;

trait Token {

    public function getAccessToken($user, $service) {
        $response = Http::withHeaders([
            'Accept' => 'application/json'
        ]) ->post('http://wwww.josue.test/api.josue/public/oauth/token', [
            'grant_type' => "password",
            'client_id' => config('services.credentials.client_id'),
            'client_secret' => config('services.credentials.client_secret'),
            'username' => request('email'),
            'password' => request('password')
        ]);
    
        $access_token = $response -> json();
    
        $user -> accessToken() -> create([
            'service_id' => $service['data']['id'],
            'access_token' => $access_token['access_token'],
            'refresh_token' => $access_token['refresh_token'],
            'expires_at' => now()->addSecond($access_token['expires_in'])
        ]);

    }

}