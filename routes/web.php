<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WaiterController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        
        if ($role === 'admin') {
            return redirect()->route('admin.index');
        } elseif ($role === 'cook') {
            return redirect()->route('kitchen.index');
        }

        return redirect()->route('waiter.index');
    }

    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //waiter
    Route::get('/waiter', [WaiterController::class, 'index'])->name('waiter.index');
    Route::get('/waiter/table/{table}/order', [WaiterController::class, 'createOrder'])->name('waiter.createOrder');
    Route::post('/waiter/table/{table}/order', [WaiterController::class, 'storeOrder'])->name('waiter.storeOrder');
    Route::get('/waiter/table/{table}/edit', [WaiterController::class, 'editOrder'])->name('waiter.editOrder');
    Route::post('/waiter/table/{table}/update', [WaiterController::class, 'updateOrder'])->name('waiter.updateOrder');
    Route::delete('/waiter/item/{orderItem}', [WaiterController::class, 'removeItem'])->name('waiter.removeItem');

    //cook
    Route::get('/kitchen', [KitchenController::class, 'index'])->name('kitchen.index');
    Route::post('/kitchen/item/{orderItem}/status', [KitchenController::class, 'changeStatus'])->name('kitchen.changeStatus');

    //payments
    Route::get('/payment/order/{order}', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/payment/order/{order}', [PaymentController::class, 'store'])->name('payment.store');

    //admin
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/admin/category', [AdminController::class, 'storeCategory'])->name('admin.storeCategory');
    Route::patch('/admin/category/{category}/toggle', [\App\Http\Controllers\AdminController::class, 'toggleCategoryStatus'])->name('admin.toggleCategory');    
    
    Route::post('/admin/item', [AdminController::class, 'storeItem'])->name('admin.storeItem');
    Route::get('/admin/item/create', [AdminController::class, 'createItem'])->name('admin.createItem');
    Route::patch('/admin/item/{menuItem}/toggle', [AdminController::class, 'toggleItemStatus'])->name('admin.toggleItem');

    Route::get('/admin/tables', [AdminController::class, 'tables'])->name('admin.tables');
    Route::get('/admin/table/create', [AdminController::class, 'createTable'])->name('admin.createTable');
    Route::post('/admin/table', [AdminController::class, 'storeTable'])->name('admin.storeTable');
    Route::get('/admin/table/{table}/edit', [AdminController::class, 'editTable'])->name('admin.editTable');
    Route::put('/admin/table/{table}', [AdminController::class, 'updateTable'])->name('admin.updateTable');
});

require __DIR__.'/auth.php';