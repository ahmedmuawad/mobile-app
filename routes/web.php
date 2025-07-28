<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// الصفحة الرئيسية (مثلاً صفحة ترحيبية)
Route::get('/', function () {
    return view('welcome');
});

// مسار تسجيل الدخول/التسجيل
Auth::routes();

// صفحة الهوم (لوحة التحكم الرئيسية)
Route::get('/home', [HomeController::class, 'index'])->name('home');

// مجموعة Routes داخل لوحة التحكم
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {

    // التصنيفات
    Route::resource('categories', CategoryController::class);
    
    // المنتجات
    Route::resource('products', ProductController::class);
    // العملاء 
    Route::resource('customers', CustomerController::class);
   
    // المبيعات 
    Route::resource('sales', SaleController::class);
    // الاعدادات
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // الصيانه
    // راوت جلب المنتجات حسب التصنيف
    Route::get('/repairs/products-by-category/{id}', [RepairController::class, 'getProductsByCategory'])->name('repairs.products-by-category');

    // راوت رئيسي لفواتير الصيانة باستخدام resource
    Route::resource('/repairs', RepairController::class)->names('repairs');


    // الموردين
    Route::resource('suppliers', SupplierController::class)->names('suppliers');

    // المشتريات
    Route::resource('purchases', PurchaseController::class)->names('purchases');


    // Route::resource('services', ServiceController::class);
});
