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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        $followers = Follow::where('followed_user_id', Auth::user()->id)
                    ->with('follower_user')
                    ->paginate(20);
        return response()->json([
            'status' => 'sucess',
            'message' => 'user followers',
            'code' => 200,
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
            'follower_user_id' => Auth::user()->id,
            ]);

        return response()->json([
            'status' => 'sucess',
            'message' => 'post partage',
            'code' => 200,
            'data' => $follow,
        ]);
    }

    /**
     * change picture profile
     */
    public function pictureProfile(Request $request)
    {
        $validator = $request->validate([
            'image' => 'required|file|image',
        ]);
        
        $user = User::where('id', Auth::user()->id)->first();

        $img = time() . '-' . $request->image->getClientOriginalName();
        $path = $request->image->move(public_path('user'), $img);
        $path = "user/" . $img;

        $media = Media::create([
            'url_media' => $path,
        ]);
        ProfilPicture::create([
            'user_id' => $user->id,
            'media_id' => $media->id,
        ]);
       
        $user->url_profil = $path;

        $user->save();

        return response()->json([
            'status' => 'sucess',
            'message' => 'user mise à jour image du profile',
            'code' => 200,
            'data' => $user,
        ]);
    }

     /**
     * Update Personnal Info
     * 
     * @param Request $request
     */
    public function update_info(Request $request){
        try{

            $user = Auth::user();
            $validateUser = Validator::make($request->all(), 
            [
                'fullname' => ['required', 'string', 'max:255'],
                'phone_number' => ['required', 'string'],
                'country' => ['required', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:255'],
                'gender' => ['required', 'string', 'max:255']

            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'code' => 401,
                    'message' => $validateUser->errors(),
                ]);
            }
            $user = User::where('id', $user->id)->first();
            
            $user->update([
                'fullname' => $request->fullname,
                'phone_number' => $request->phone_number,
                'country' => $request->country,
                'city' => $request->city,
                'gender' => $request->gender,
            ]);

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'modification user',
                'data' => $user
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Update Password
     * 
     * @param Request $request
     */
    public function modifyPassword(Request $request){
        try {

            $validateUser = Validator::make($request->all(), 
            [
                'password' => 'required',
                'new_password' => 'required',

            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'code' => 401,
                    'errors' => $validateUser->errors()
                ]);
            }

            $user = Auth::user();
            $user = User::where('id', $user->id)->first();
            $verify = Hash::check($request->password, $user->password);
            if (!$verify) {
                return response()->json([
                    'status' => false,
                    'code' => 400,
                    'message' => 'Mot de passe incorrect',
                ]);
            }
            $user->password = Hash::make($request->new_password);
            $user->save();
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'mot de passe modifié',
                'data' => $user
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }

    /**
     * change couverture profile
     */
    public function coverProfil(Request $request)
    {
        $validator = $request->validate([
            'image' => ['required|image'],
        ]);
        $user = User::where('id', Auth::user()->id)->first();

        $img = time() . '-' . $request->image->getClientOriginalName();
        $path = $request->image->move(public_path('user'), $img);
        $path = "user/" . $img;
        $media = Media::create([
            'madia_url' => $path,
        ]);
        CoverPicture::create([
            'user_id' => $user->id,
            'media_id' => $media->id,
        ]);
       
        $user->url_cover = $path;
            
        $user->save();

        return response()->json([
            'status' => 'sucess',
            'message' => 'user mise à jour image du couverture',
            'code' => 200,
            'data' => $user,
        ]);
    }

       /**
     * Notification Listes user
     */
    public function notify_user(Request $request)
    {
        
        $user = User::where('id', Auth::user()->id)->first();

        $notifications = $user->notifications;
        // auth()->user()->unreadNotifications->markAsRead();

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'les notifications',
            'data' => $notifications
        ]);
    }

    /**
     * Notification masquer comme Lu
     */
    public function markAsRead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
        $notification = auth()->user()->unreadNotifications->find($request->id);
        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'la notification',
            'data' => $notification
        ]);
    }
}
