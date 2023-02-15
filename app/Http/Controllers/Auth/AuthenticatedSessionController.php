<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use App\Traits\Token;

class AuthenticatedSessionController extends Controller
{
    use Token;
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $response = Http::withHeaders([
            'Accept' => 'application/json'
        ]) ->post('http://api.josue.test/v1/login', [
            'email' => $request->email,
            'password' => $request->password
        ]);

        if ($response -> status() == 404) {
            return back()->withErrors('These credentials do not match our records.');
        }

        $service = $response -> json();
        // firstOrcreate
        $user = User::updateOrcreate([
            'email' => $request -> email
        ],$service['data']);

        if (!$user->accessToken) {
            $this -> getAccessToken($user, $service);
        }

        Auth::login($user, $request -> remember);
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
