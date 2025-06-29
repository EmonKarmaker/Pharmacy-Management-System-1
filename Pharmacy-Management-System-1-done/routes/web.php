<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    return redirect('login');

})->name('login');


Route::get('/dashboard', function () {
    //return view('dashboard');

    return view('admin.dashboard');

})->middleware(['auth'])->name('dashboard');

// Supplier

Route::get('dashboard/supplier', [SupplierController::class, 'index'])->name('all_supplier');
Route::get('dashboard/supplier/add/', [SupplierController::class, 'add'])->name('add_supplier');
Route::get('dashboard/supplier/edit/{slug}', [SupplierController::class, 'edit'])->name('edit_supplier');
Route::post('dashboard/supplier/submit', [SupplierController::class, 'submit'])->name('submit_supplier');
Route::post('dashboard/supplier/update', [SupplierController::class, 'update'])->name('update_supplier');
Route::post('dashboard/supplier/delete', [SupplierController::class, 'delete'])->name('delete_supplier');

// Products

Route::get('dashboard/product',[ProductsController::class,'index'])->name('product.index');
Route::get('dashboard/product-create',[ProductsController::class,'create'])->name('product.create');
Route::post('dashboard/product-create',[ProductsController::class,'store'])->name('product.store');
Route::get('dashboard/product/edit/{slug}',[ProductsController::class,'edit'])->name('product_edit');
Route::post('dashboard/product/update', [ProductsController::class, 'update'])->name('product.update');
Route::delete('dashboard/product/delete/{id}', [ProductsController::class, 'destroy'])->name('product_delete');

//user profile

Route::get('/dashboard/profile',[UserController::class ,'userprofile']);

require __DIR__.'/auth.php';
