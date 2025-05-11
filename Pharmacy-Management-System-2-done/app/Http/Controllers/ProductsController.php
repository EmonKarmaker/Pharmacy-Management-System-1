<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Image;
use File;

class ProductsController extends Controller{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::orderBy('product_id', 'DESC')->get();
        return view('admin.product.all', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.product.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'product_name' => 'required|max:255|unique:products,product_name',
            //'product_wholesale_price' => 'numeric|required',
            'product_wholesale_price' => 'required',
            //'product_retail_price' => 'numeric|required',
            'product_retail_price' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ],[

        ]);

        $product_slug = $request->product_name . '-' . time();
        $created_by = Auth::user()->name;
        $id = Product::insertGetId([
            'product_name'=>$request->product_name,
            'product_slug'=>$product_slug,
            'product_details'=>$request->product_details,
            'product_wholesale_price'=>$request->product_wholesale_price,
            'product_retail_price'=>$request->product_retail_price,
            'created_by'=>$created_by,
            'created_at'=>Carbon::now()->toDateTimeString(),
        ]);

        if($request->hasFile('product_photo')) {
            $image=$request->file('product_photo');
            $imageName='product_'.$id.'_'.time().'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(200,200)->save('uploads/product/'.$imageName);

            Product::where('product_id', $id)->update([
              'product_photo'=>$imageName,
            ]);
        }

        return redirect()->route('product.index')->with('success', 'Product added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $Product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $Product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $Product
     * @return \Illuminate\Http\Response
     */
    
}