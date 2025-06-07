<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FoodItem;
use App\Models\OrderNumber;
use App\Models\OrderDetails;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;
use Session;
use Image;
use File;
use Auth;

class ProductController extends Controller{
    public function cart()
    {
        return view('cart');
    }
  
    

    public function customer_profile($id){
        
        $data = User::where('id', $id)->first();
        
        if($data){
            return view('admin.order.view_customer_profile', compact('data'));
    
        } else {            
            return back()->with('error', 'Something went Wrong');
        
        }
    }




}



