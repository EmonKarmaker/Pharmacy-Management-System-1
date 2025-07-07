<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Image;
class UserController extends Controller
{
    public function userprofile(){
        return view('layouts.website.user.profile');
    }
    public function editprofile($slug){
        $data = User::where('user_slug', $slug)->first();
        return view('layouts.website.user.edit_profile', compact('data'));
    }
    public function updateUserInfo(Request $request ){
        //return $request->all();
        $id = $request->id;
        $this->validate($request,[
            'name'=>'required|max:255',
            'phone'=>'required|max:255',
            'address'=>'required|max:255',
        ]);

        $update = User::where('id', $id)->update([
            'name'=>$request->name,
            'phone'=>$request->phone,
            'address'=>$request->address,
            'updated_at'=>Carbon::now()->toDateTimeString(),
        ]);
        if($update){
            return back()->with('success', 'Information Update Successfully');
        }else{
            return back()->with('error','Customer information edit process failed!');

        }
    }

}

