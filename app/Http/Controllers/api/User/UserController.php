<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\CoverPicture;
use App\Models\Follow;
use App\Models\Media;
use App\Models\ProfilPicture;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }
    /**
     * get liste followers
     */
    public function getListFollowers()
    {
        $followers = Follow::where('followed_user_id', $this->user->id)
                    ->with('follower_user')
                    ->paginate(20);
        return response()->json([
            'status' => 'sucess',
            'message' => 'user followers',
            'code' => '200',
            'data' => $followers,
        ]);
    }

    /**
     * create follower.
     */
    public function createFollower(Request $request)
    {
        $validator = $request->validate([
            'followed_user_id' => ['required'],
        ]);
        $followed_user_id = $request->followed_user_id;
        $followed = User::where('id', $followed_user_id)->first();
        if(!$followed){
            return response()->json([
                'status' => 'failed',
                'message' => 'followed n\'exist pas',
                'code' => '404',
                'data' => null,
                ]);
        }
        $follow = Follow::create([
            'followed_user_id' => $followed->id,
            'follower_user_id' => $this->user->id,
            ]);

        return response()->json([
            'status' => 'sucess',
            'message' => 'post partage',
            'code' => '200',
            'data' => $follow,
        ]);
    }

    /**
     * change picture profile
     */
    public function pictureProfile(Request $request)
    {
        $validator = $request->validate([
            'image' => ['required|image'],
        ]);
        $user = User::where('id', $this->user->id)->first();

        $img = time() . $request->image->getClientOriginalName();
        $path = $request->image->move(public_path() . "\user", $img);
        $media = Media::create([
            'madia_url' => $path,
        ]);
        ProfilPicture::create([
            'user_id' => $user->id,
            'media_id' => $media->id,
        ]);
       
        $user->update([
            'url_profil' => $path,
            ]);

        $user->save();

        return response()->json([
            'status' => 'sucess',
            'message' => 'user mise à jour image du profile',
            'code' => '200',
            'data' => $user,
        ]);
    }

    /**
     * change couverture profile
     */
    public function coverProfil(Request $request)
    {
        $validator = $request->validate([
            'image' => ['required|image'],
        ]);
        $user = User::where('id', $this->user->id)->first();

        $img = time() . $request->image->getClientOriginalName();
        $path = $request->image->move(public_path() . "\user", $img);
        $media = Media::create([
            'madia_url' => $path,
        ]);
        CoverPicture::create([
            'user_id' => $user->id,
            'media_id' => $media->id,
        ]);
       
        $user->update([
            'url_cover' => $path,
            ]);
            
        $user->save();

        return response()->json([
            'status' => 'sucess',
            'message' => 'user mise à jour image du couverture',
            'code' => '200',
            'data' => $user,
        ]);
    }

    /**
     * create account profile
     */
    public function createAccount(Request $request)
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
