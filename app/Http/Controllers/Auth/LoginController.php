<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function APIlogin(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        if(Auth::attempt($credentials))
        {
            $user = User::whereEmail($credentials['email'])->first();
            $token = Str::random(80);
            $user->forceFill([
                'api_token' => hash('sha256', $token),
            ])->save();

            return response()->json([
                'api_token' => $token
            ], 200);
        }

        return response()->json([
            'error' => 'Niepoprawny login lub hasło!'
        ], 401);
    }
}
