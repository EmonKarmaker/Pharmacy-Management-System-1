<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SaleInfo;
use App\Models\User;
use App\Models\Stock;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Image;
use File;
use PDF;

class SaleInfoController extends Controller{
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
        $SaleInfo = SaleInfo::orderBy('sale_info_id', 'DESC')->get();
        return view('admin.sale.all', compact('SaleInfo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.sale.add');
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
            //'product_name' => 'required|max:255|unique:products,product_name',
            'product_quantity' => 'numeric|required',
            'product_discount_in_percentage' => 'numeric|required',
            'sale_info_photo' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ],[

        ]);

        $StockProductQuantity= Stock::where('product_id', $request->product_id)->value('product_quantity');

        if($StockProductQuantity==''){
            return redirect()->route('sale-info.create')->with('error', 'This product is not available in the stock');
        }elseif($request->product_quantity>$StockProductQuantity){
            return redirect()->route('sale-info.create')->with('error', 'You have requested more quantity than available in the stock');
        }


        $sale_info_slug = 'SALE'.uniqid().'-'.time();
        $invoice_number = $request->sale_date.'-'.uniqid();
        $created_by = Auth::user()->name;

        if($request->sale_type=='retail'){
            $product_price_per_unit = Product::where('product_id', $request->product_id)->value('product_retail_price');            
        }elseif($request->sale_type=='wholesale'){
            $product_price_per_unit = Product::where('product_id', $request->product_id)->value('product_wholesale_price');            
        }

        $product_total_price = $request->product_quantity*$product_price_per_unit;
        if($request->product_discount_in_percentage!=''){
            $discount_price_by_percentage = ($product_total_price*$request->product_discount_in_percentage)/100;
        }else{
            $discount_price_by_percentage=0;
        }
        $product_total_price_after_discount = $product_total_price - $discount_price_by_percentage;

        $id = SaleInfo::insertGetId([
            'invoice_number'=>$invoice_number,
            'sale_date'=>$request->sale_date,
            'sale_type'=>$request->sale_type,
            'product_id'=>$request->product_id,
            'product_quantity'=>$request->product_quantity,
            'product_price_per_unit'=>$product_price_per_unit,
            'product_discount_in_percentage'=>$request->product_discount_in_percentage,
            'product_total_price'=>$product_total_price,
            'payment_status'=>$request->payment_status,
            'product_total_price'=>$product_total_price,
            'product_total_price_after_discount'=>$product_total_price_after_discount,
            'sale_remarks'=>$request->sale_remarks,
            'sale_info_slug'=>$sale_info_slug,
            'created_by'=>$created_by,
            'created_at'=>Carbon::now()->toDateTimeString(),
        ]);

        if($request->hasFile('sale_info_photo')) {
            $image=$request->file('sale_info_photo');
            $imageName='sale_info_'.$id.'_'.time().'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(200,200)->save('uploads/sale-info/'.$imageName);

            SaleInfo::where('sale_info_id', $id)->update([
              'sale_info_photo'=>$imageName,
            ]);
        }

        // $StockProductQuantity= Stock::where('product_id', $request->product_id)->value('product_quantity');
        // Stock::where('product_id', $request->product_id)->update([
        //     'product_quantity'=>$StockProductQuantity-$request->product_quantity,
        //     'updated_at'=>Carbon::now()->toDateTimeString(),
        // ]);

        $StockProductQuantity= Stock::where('product_id', $request->product_id)->value('product_quantity');

            Stock::where('product_id', $request->product_id)->update([
                'product_quantity'=>$StockProductQuantity-$request->product_quantity,
                'updated_at'=>Carbon::now()->toDateTimeString(),
            ]);
        

        return redirect()->route('sale-info.index')->with('success', 'sale info added successfully');
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
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $Product
     * @return \Illuminate\Http\Response
     */

}