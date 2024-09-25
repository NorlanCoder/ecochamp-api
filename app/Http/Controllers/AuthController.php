<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use App\Notifications\ResetPasswordNotification;
use Ichtrojan\Otp\Models\Otp as Model;
use Ichtrojan\Otp\Otp;

class AuthController extends Controller
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    protected User $user;

    private $otp;


    public function __construct(User $user)
    {
        // $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->user = $user;
        $this->otp = new Otp();
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
            'fullname' => ['required', 'string'],
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
            'fullname' => $request->fullname,
            'phone_number' => $request->phone_number,
            'country' => $request->country,
            'city' => $request->city,
            'gender' => $request->gender,
            'password' => Hash::make($request->password),
            'email' => $request->email
        ]);


        return response()->json([
            'status' => true,
            'message' => 'L\'utilisateur a été créé avec succès',
            'token' => $user->createToken("API TOKEN")->plainTextToken,
            'user' => $user,
        ], 200);
    }

    /**
     * update Password
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
     * respond With Token
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

    /**
     * Verification code reset password
     * 
     * @unauthenticated
     * 
     * @param Request $request
     * @return User
    */
    public function codeCheck(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'email' => 'required|email',
        ]);

        $otp2 = $this->validate_code_opt($request->email, $request->code);

        if(!$otp2->status){

            return response()->json([
                'status' => false,
                'code' => 401,
                'message' => 'Le code est expiré',
                'error'=>$otp2
            ]);
        }

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => 'Le code est valide',
        ]);
       
    }

  
    public function validate_code_opt(string $identifier, string $token): object
    {
        $otp = Model::where('identifier', $identifier)->where('token', $token)->first();

        if ($otp instanceof Model) {
            if ($otp->valid) {
                $now = now();
                $validity = $otp->created_at->addMinutes($otp->validity);

                $otp->update(['valid' => false]);

                if (strtotime($validity) < strtotime($now)) {
                    return (object)[
                        'status' => false,
                        'message' => 'OTP Expired'
                    ];
                }

                $otp->update(['valid' => false]);

                return (object)[
                    'status' => true,
                    'message' => 'OTP is valid'
                ];
            }

            $otp->update(['valid' => false]);

            return (object)[
                'status' => false,
                'message' => 'OTP is not valid'
            ];
        } else {
            return (object)[
                'status' => false,
                'message' => 'OTP does not exist'
            ];
        }
    }


    /**
     * Forgot Password
     * 
     * @unauthenticated
     * 
     * @param Request $request
     * @return User
    */
    public function forgotPassword(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users',
        ]);
        $input = $request->only('email');
        $user = User::where('email',$input)->first();
        if (!$user){
            return response()->json([
                'message' =>'Email invalide',
                'code' => 401,
                'status' => false
            ]);
        }
        // $user->notify(new ResetPasswordNotification());
        $otp = $this->otp->generate($user->email, 'numeric', 5, 15);
        
        return response()->json([
            'message' =>'Nous avons envoyé un code dans votre boite mail.',
            'code' => 200,
            'status' => true
        ]);
    }

    /**
     * Change the password (Setp 3)
     * 
     * @unauthenticated
     *
     * @param  mixed $request
     * @return void
     */
    public function resetPassword(Request $request)
    {

        $validateUser = Validator::make($request->all(), 
        [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);

        if($validateUser->fails()){

                return response()->json([
                    'status' => false,
                    'message' => $validateUser->errors()
                ]);
        }

        $user = User::firstWhere('email', $request->email);

        $user->update($request->only('password'));

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => 'Mot de passe mise à jour.', 
            'data' => $user
        ]);

    }
}
