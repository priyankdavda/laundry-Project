<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubCateoryController;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\OrderStatusController ;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard',[ProfileController::class,'dashboard'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | ORDERS → ADMIN + VENDOR
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin|vendor'])->group(function () {

        Route::resource('order', OrderController::class);

        Route::get('order/get-user/{user}', [OrderController::class, 'getUser'])
            ->name('order.get-user');

        Route::get('order/get-product/{product}', [OrderController::class, 'getProduct'])
            ->name('order.get-product');

        Route::post('orders/{order}/update-pickup', [OrderController::class, 'updatePickup'])
            ->name('order.updatePickup');

        Route::post('orders/assign-pickup', [OrderController::class, 'assignPickup'])
            ->name('order.assignPickup');

        Route::post('orders/{order}/change-delivery', [OrderController::class, 'changeDelivery'])
            ->name('order.changeDelivery');

        Route::post('order/assign-delivery', [OrderController::class, 'assignDelivery'])
            ->name('order.assignDelivery');

        Route::get('order/{order}/tags', [OrderController::class, 'createTag'])
            ->name('order.createTag');

        Route::post('orders/{order}/update-status', [OrderController::class, 'updateStatus'])
            ->name('orders.updateStatus');

        Route::get('orders/{order}/next-statuses', [OrderController::class, 'getNextStatuses'])
            ->name('orders.nextStatuses');

        Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])
            ->name('orders.cancel');

        Route::get('orders/{order}/status-history', [OrderController::class, 'statusHistory'])
            ->name('orders.statusHistory');

         Route::get('products/by-category/{category}', [ProductController::class,'byCategory'])
            ->name('admin.products.by-category');
        Route::resource('category',CategoryController::class);
        Route::resource('product',ProductController::class);
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::post('/reports', [ReportController::class, 'filter'])->name('reports.filter');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN ONLY
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->group(function () {

        Route::resource('user',UserController::class);
        Route::resource('role',RoleController::class);
        Route::resource('permission',PermissionController::class);


        Route::resource('subcategory',SubCateoryController::class);
        Route::resource('collection',CollectionController::class);


        Route::get('/get/subcategory',[ProductController::class,'getsubcategory'])->name('getsubcategory');
        Route::get('/remove-external-img/{id}',[ProductController::class,'removeImage'])->name('remove.image');

        Route::resource('orderstatus',OrderStatusController::class);




    });

    /*
    |--------------------------------------------------------------------------
    | DEBUG (TEMPORARY)
    |--------------------------------------------------------------------------
    */
    Route::get('/whoami', function () {
        return [
            'id'    => auth()->id(),
            'email' => auth()->user()->email,
            'roles' => auth()->user()->roles->pluck('name'),
        ];
    });
});


