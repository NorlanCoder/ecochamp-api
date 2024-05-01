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
    protected User $user;
    public function __construct(User $user)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->user = $user;
    }

    /**
     * Login
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

        // $credentials = request(['email', 'password']);

        // if (!$token = auth()->attempt($credentials)) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Identifiants incorrects'
        //     ], 401);
        // }
        $user = User::where('email', $request->email)->first();
        if(!$user){
            return response()->json([
                'status' => false,
                'message' => 'Email incorrects',
            ], 401);
        }
        $password_verify = Hash::check($request->password, $user->password);
        if(!$password_verify){
            return response()->json([
                'status' => false,
                'message' => 'Password incorrects',
            ], 401);
        }
        // return $user->createToken("API TOKEN")->accessToken;
        return response()->json([
            'status' => true,
            'message' => 'L\'utilisateur s\'est connecté avec succès',
            'token' => $user->createToken("API TOKEN")->plainTextToken,
            'user' => $user,
        ], 200);
    }

    /**
     * Register
     *
     * @return \Illuminate\Http\JsonResponse
     */

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

        // $token = $user->createToken("API TOKEN")->accessToken;

        return response()->json([
            'status' => true,
            'message' => 'L\'utilisateur a été créé avec succès',
            'token' => $user->createToken("API TOKEN")->plainTextToken,
            'user' => $user,
        ], 200);
    }

    /**
     * updatePassword
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request)
    {

        $validator = Validator::make([
            'old_password' => ['required', 'string', 'max:255'],
            'new_password' => ['required', 'string', 'max:255']
        ]);

        $user = auth()->user;

        if (Hash::check($user->password, $request->get('old_password'))) {
            $this->user->where('id', $user->id)->update([
                'password' => Hash::make($request->get('new_password'))
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Mot de passe modifié avec succes'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Mot de passe incorrect'
            ]);
        }
    }

    /**
     * respondWithToken
     *
     * @return \Illuminate\Http\JsonResponse
     */

    protected function respondWithToken($token, $user)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => $user,
        ], 200);
    }

    /**
     * Social Login
     *
     * @return \Illuminate\Http\JsonResponse
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
