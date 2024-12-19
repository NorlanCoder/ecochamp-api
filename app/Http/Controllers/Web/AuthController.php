<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //LOGIN
    public function login(){

        return view('dashboard.login');
    }

    public function check(Request $request){
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validation->fails()){
            $validation->validate($request, [
                'email' => 'required',
                'password' => 'required',
            ]) ;
        }

        $credentials = $request->only('email', 'password');
        // $credentials['role'] = 'admin';

        $exist = User::where('email', $request->email)->first();
        if(!$exist){
            flash()->error('Mauvais Email');
            return back();
        }
        if($exist){
            $valid_password = Hash::check($request->password, $exist->password);
            if(!$valid_password){
                flash()->error('Mauvais mot de passe');
                return back();
            }
        }
        if($exist->is_blocked){
            flash()->error('Votre compte a été bloqué');
            return back();
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            Auth::login($user);
            return redirect()->route('web.dashboard');
        }

        flash()->error('Mauvais Email ou mot de passe');
        return back();
    }

    public function logout(){

        Auth::logout();

        return redirect()->route('web.login');
    }


    public function dashboard(){
        $user = Auth::user();
        return view('dashboard.home', compact(['user']));
    }


}
