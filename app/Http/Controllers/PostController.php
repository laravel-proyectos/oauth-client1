<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PostController extends Controller
{
    public function store() {
        $this -> resolveAuthorization();

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. auth() -> user()->accessToken->access_token
        ]) -> post('http://api.josue.test/v1/posts', [
            'name' => 'Este es desde cliente',
            'slug' => 'este-es-desde-cliente',
            'extract' => 'erjskfsekj nfkse',
            'body' => 'jssje sejfehjsejk sejkl',
            'category_id' => 1
        ]);

        return $response -> json();
    }
}
