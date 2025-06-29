<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;
use Session;
use Image;
use File;

use Illuminate\Support\Facades\Mail;
use App\Mail\CustomerMail;

class CustomerController extends Controller{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('admin');
    }


    public function index(){

        $all = User::orderBy('id', 'DESC')->where('role_id','2')->get();

        return view('admin.user.all',compact('all'));


    }

    public function edit($slug){
        $allData = User::where('user_slug', $slug)->first();
        return view('admin.user.edit', compact('allData'));

    }


    public function update(Request $request){
        $id = $request->id;
        $this->validate($request,[
            'name'=>'required|max:255',
            'email'=>'required|max:255',
            'phone'=>'required|max:255',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg',
            //'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=500,max_height=500', // (2048kb max size limit)
        ],[
            'name.required'=>'Your name is required.',
            'name.max'=>'Your name must not be greater than 255 characters.',
            'email.required'=>'Your email is required.',
            'email.max'=>'Email must not be greater than 255 characters.',
            'phone.required'=>'Phone is required.',
            'phone.max'=>'Phone must not be greater than 255 characters.',
        ]);

        $update = User::where('id', $id)->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'updated_at'=>Carbon::now()->toDateTimeString(),
        ]);
        if($request->hasFile('photo')){
            $image=$request->file('photo');
            $imageName='customer_'.$id.'_'.time().'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(200,200)->save('uploads/customer/'.$imageName);

            User::where('id', $id)->update([
              'photo'=>$imageName,
            ]);
        }

        if($update){


            Session::flash('success','Customer information updated successfully!');

            return redirect()->route('all_customer');
        }else{

            Session::flash('error','Customer information edit process failed!');


            return redirect()->route('edit_customer');
        }
    }

    public function delete(Request $request){
        $customer = User::where('user_slug', $request->modal_id)->first();
        $path = 'uploads/customer/'.$customer['photo'];
        if(File::exists($path)){

            File::delete($path);
        }
        $delete = User::where('id', $customer->id)->delete();
        if($delete){
            Session::flash('success','Customer account deleted successfully!');
            return redirect()->route('all_customer');
        }else{
            Session::flash('error','Customer account delete process failed!');
            return redirect()->route('all_customer');

        }

    }


}





