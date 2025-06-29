<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Models\PurchaseInfo;
use App\Models\SaleInfo;

class IncomeExpenseReportController extends Controller{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('admin');
    }

}
