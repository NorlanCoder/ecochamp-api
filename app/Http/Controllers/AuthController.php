<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:255']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Identifiants incorrects'
            ], 401);
        }

        return $this->respondWithToken($token, auth()->user);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'max:255', 'regex:/^[\+]?[(]?[0-9 ]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/', 'unique:users,phone_number'],
            'country' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'max:255'],
            'password' => ['required', 'min:8', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email']
        ]);

        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'phone_number' => $request->phone_number,
            'country' => $request->country,
            'city' => $request->city,
            'gender' => $request->gender,
            'password' => Hash::make($request->password),
            'email' => $request->email
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => $user
        ]);
    }

    protected function respondWithToken($token, $user)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => $user,
        ]);
    }

    /**
     * Social Login
     */
    public function socialRedirect(Request $request)
    {
        $provider = $request->input('provider');
        return Socialite::driver($provider)->redirect();
    }

    public function socialLogin(Request $request)
    {
        $provider = "facebook"; 
        $token = $request->input('access_token');

        $providerUser = Socialite::driver($provider)->user($token);
        
        $user = User::where('provider_name', $provider)->where('provider_id', $providerUser->id)->first();

        if($user == null){
            $user = User::create([
                'provider_name' => $provider,
                'provider_id' => $providerUser->id,
            ]);
        }

        $token = $user->createToken(env('APP_NAME'))->accessToken;

        return response()->json([
            'success' => true,
            'token' => $token
        ]);
    }
}
