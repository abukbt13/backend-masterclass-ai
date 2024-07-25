<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function Register(Request $request)
    {

        $rules = [
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'name' => 'required',
        ];
        $data = request()->all();
        $valid = Validator::make($data, $rules);
        if (count($valid->errors())){
            return response([
                'status' => 'failed',
                'error' => $valid->errors()
            ]);
        }
        $user = new User();
        $user->email = $data['email'];
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($request->password);
        $user->save();
        if (Auth::attempt(['email' => $data['email'], 'password' => $data ['password']])) {
            $token = $user->createToken('token')->plainTextToken;
            return response([
                'status'=>'success',
                'token'=>$token,
                'user'=>$user
            ]);
        }
    }
    public function login(Request $request)
    {

        $data = request()->all();
        $rules = [
            'email' => 'required',
            'password' => 'required'
        ];
        $valid = Validator::make($data, $rules);
        if (count($valid->errors())) {
            return response([
                'status' => 'failed',
                'errors' => $valid->errors()
            ],422);
        }
        $email = request('email');
        $password = request('password');
        $user = User::where('email', $email)->get()->first();

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $token = $user->createToken('token')->plainTextToken;

            return response([
                'status' => 'success',
                'token' => $token,
                'user' => request()->user()
            ]);
        }
        else{
            return response([
                'status' => 'failed',
                'message' => 'Enter correct details',
            ]);
        }
    }
    public function auth(){
        if (Auth::check()) {
            return response()->json(['authenticated' => true]);
        } else {
            return response()->json(['authenticated' => false]);
        }
    }
    public function UpdateProfile(Request $request,$id){
        $data = $request->all();
        $user = User::find($id);
        $user -> name = $data['name'];
        $user -> phone = $data['phone'];

        if ($request->hasFile('profile')) {
            $profile= $request->file('profile');
            $PictureName = time() . '_' .  $profile->getClientOriginalName();
            $user->profile = $PictureName;
            $profile->move(public_path('Profile/picture'), $PictureName);
        }
        $user->update();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated Successfully',
            'user' => $user,
        ]);
    }
}
