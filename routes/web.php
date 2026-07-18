<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\SalesReportController;



/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PROFILE (BREEZE REQUIRED)
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD (ADMIN)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->middleware(['auth'])
            ->name('dashboard');

        // USERS
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

        // PRODUCTS
        Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductsController::class, 'create'])->name('products.create');
        Route::post('/products/store', [ProductsController::class, 'store'])->name('products.store');
        Route::delete('/products/{id}', [ProductsController::class, 'destroy'])->name('products.destroy');

        Route::post('/products/import', [ProductsController::class, 'import'])->name('products.import');

        // SALES HISTORY
        //Route::get('/sales-history', [SalesController::class, 'history'])->name('sales.history');
    });

    // REFUND HISTORY
    Route::get('/refunds', [RefundController::class, 'index'])
        ->middleware('role:admin,supervisor')
        ->name('refunds.index');


    // CREATE REFUND
    Route::get('/refunds/{sale}', [RefundController::class, 'create'])
        ->middleware('role:admin,supervisor')
        ->name('refunds.create');


    Route::post('/refunds/{sale}', [RefundController::class, 'store'])
        ->middleware('role:admin,supervisor')
        ->name('refunds.store');


    // SALES HISTORY
    Route::get('/sales-history', [SalesController::class, 'history'])
        ->middleware('role:admin,supervisor')
        ->name('sales.history');




    // SALES REPORT
    Route::middleware(['auth'])->group(function () {
        Route::get('/sales-report', [SalesReportController::class, 'index'])
            ->name('sales.report');
        Route::get('/sales-report/pdf', [SalesReportController::class, 'exportPdf'])
            ->name('sales.report.pdf');
    });



    /*
    |--------------------------------------------------------------------------
    | SUPERVISOR
    |--------------------------------------------------------------------------
    */
   // Route::middleware(['role:supervisor'])->group(function () {

      //  Route::get('/sales-history', [SalesController::class, 'history'])->name('sales.history');
    //});

    /*
    |--------------------------------------------------------------------------
    | POS (ALL ROLES)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:cashier,supervisor,admin'])->group(function () {

        Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
        Route::post('/sales/sell', [SalesController::class, 'sell'])->name('sales.sell');
        Route::get('/sales/barcode/{barcode}', [SalesController::class, 'findByBarcode']);
    });

});