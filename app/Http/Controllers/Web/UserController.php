<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $ativits =  User::where('account_type', 'individual')
                ->where('role', 'ativist')->paginate(10);

        return view('dashboard.user.user', compact(['user', 'ativits']));
    }

    /**
     * Display a listing of the resource. ong
     */
    public function index_ong()
    {
        $user = Auth::user();
        $ongs =  User::where('account_type', 'ong')
                ->where('role', 'ativist')->paginate(10);

        return view('dashboard.user.ong', compact(['user', 'ongs']));
    }

    /**
     * Display a listing of the resource. admin
     */
    public function index_admin()
    {
        $user = Auth::user();
        $admins = User::where('role', 'admin')->paginate(10);
        return view('dashboard.user.admin', compact(['user', 'admins']));
    }

    /**
     * Display a listing of the resource. profile
     */
    public function profile(User $user)
    {
        $profile = $user;
        $user = Auth::user();
        return view('dashboard.user.profile', compact(['user', 'profile']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
