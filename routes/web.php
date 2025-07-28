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
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\DashboardController;

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
Route::get('admin/home', [App\Http\Controllers\DashboardController::class, 'index'])->name('admin.home');

// مجموعة Routes داخل لوحة التحكم
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {

    // الصفحة الرئيسية (Dashboard)
    // Route::get('/home', [DashboardController::class, 'index'])->name('home');

    // التصنيفات
    Route::resource('categories', CategoryController::class);

    // المنتجات
    Route::resource('products', ProductController::class);

    // العملاء 
    Route::resource('customers', CustomerController::class);

    // المبيعات 
    Route::resource('sales', SaleController::class);

    // الإعدادات
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // الصيانة
    // جلب المنتجات حسب التصنيف
    Route::get('/repairs/products-by-category/{id}', [RepairController::class, 'getProductsByCategory'])->name('repairs.products-by-category');

    // فواتير الصيانة
    Route::resource('repairs', RepairController::class);

    // سداد مستحقات الصيانة
    Route::get('repairs/{id}/payment', [RepairController::class, 'showPaymentForm'])->name('repairs.payments.create');
    Route::post('repairs/{id}/payment', [RepairController::class, 'storePayment'])->name('repairs.payments.store');

    // الموردين
    Route::resource('suppliers', SupplierController::class);

    // المشتريات
    Route::resource('purchases', PurchaseController::class);
    Route::get('purchases/{purchase}/show', [PurchaseController::class, 'show'])->name('purchases.show');

    // المصروفات
    Route::resource('expenses', ExpenseController::class);

    // لو في راوتات إضافية لاحقًا:
    // Route::resource('services', ServiceController::class);
});
