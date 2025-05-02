<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Image;
use File;

class SupplierController extends Controller{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('admin');
    }


    public function index(){
        $all = User::orderBy('id', 'DESC')->where('role_id','3')->get();
        return view('admin.supplier.all',compact('all'));
    }

    public function add(){
        return view('admin.supplier.add');
    }


    public function submit(Request $request){
        $this->validate($request,[
            'name'=>'required|max:255',
            'email'=>'required|max:255|unique:users,email',
            'phone'=>'required|max:255',
            'organization'=>'max:255',
            'designation'=>'max:255',
            'password'=>'max:255',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg',
            //'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=500,max_height=500', // (2048kb max size limit)
        ],[
            'name.required'=>'Your name is required.',
            'name.max'=>'Your name must not be greater than 255 characters.',
            'email.required'=>'Your email is required.',
            'email.max'=>'Email must not be greater than 255 characters.',
            'phone.required'=>'Phone is required.',
            'phone.max'=>'Phone must not be greater than 255 characters.',
            'organization.max'=>'Organization name must not be greater than 255 characters.',
            'designation.max'=>'Designation must not be greater than 255 characters.',
            'password.max'=>'Password must not be greater than 255 characters.',
        ]);
        $slug = 'SUPPLIER'.uniqid();
        $id = User::insertGetId([
            'name'=>$request->name,
            'role_id'=>$request->role_id,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'organization'=>$request->organization,
            'designation'=>$request->designation,
            'address'=>$request->address,
            'remarks'=>$request->remarks,
            'user_slug' => $slug,
            'password'=>Hash::make($request->password),
            'created_at'=>Carbon::now()->toDateTimeString(),
        ]);
        if($request->hasFile('photo')){
            $image=$request->file('photo');
            $imageName='supplier_'.$id.'_'.time().'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(200,200)->save('uploads/supplier/'.$imageName);

            User::where('id', $id)->update([
              'photo'=>$imageName,
            ]);
        }

        if($id){
            Session::flash('success','Supplier information added successfully!');
            return redirect()->route('all_supplier');
        }else{
            Session::flash('error','Supplier information addition process failed!');
            return redirect()->route('edit_supplier');
        }
    }

}
