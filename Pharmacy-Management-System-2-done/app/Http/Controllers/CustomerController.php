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

}





