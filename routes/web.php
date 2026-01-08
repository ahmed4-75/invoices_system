<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoicesListController;
use App\Http\Controllers\ReceiptsListController;
use App\Http\Controllers\ExpenseListController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\ProductsController;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\GoodsArchiveController;
use App\Http\Controllers\InvoicesArchiveController;
use App\Http\Controllers\ExpensesArchiveController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () { return view('auth.login'); })->name('login');
Auth::routes();
Route::get('home', [HomeController::class, 'index'])->middleware('auth')->name('home');

Route::middleware('role:Admin|Accountant')->group(function () {
    Route::resource('user/invoices_list',InvoicesListController::class);
    Route::get('get_products/{id}',[InvoicesListController::class,'get_products']);
    Route::get('invoice/attachments/{customerName}/{fileName}',[InvoicesListController::class,'attachments'])->name('invoiceAttachments');
    
    Route::resource('user/receipts_list',ReceiptsListController::class);
    Route::get('search_invoice',[ReceiptsListController::class,'search_invoice'])->name('search_invoice');
    Route::get('receipt/attachments/{customerName}/{fileName}',[ReceiptsListController::class,'attachments'])->name('receiptAttachments');
    
    Route::resource('user/expense_list',ExpenseListController::class);
    Route::get('expense/attachments/{creditorName}/{fileName}',[ExpenseListController::class,'attachments'])->name('expenseAttachments');
});
    
Route::middleware('role:Admin|Goods Receiver')->group(function () {
    Route::resource('user/sections',SectionsController::class);
    
    Route::resource('user/products',ProductsController::class);
});

Route::middleware(['auth','role:Admin'])->group(function () {
    Route::get('user/users',function(){
        $users = User::select('id','name','email','created_at','updated_at')->get();
        $users = UserResource::collection($users)->resolve();
        return view('users_permissions.users',compact('users')); 
    })->name('users.index');
    Route::delete('user/users/{user}',function(User $user){
        $user->forceDelete();
        session()->flash('delete', 'User has been Deleted Successfully ✅');
        return to_route('users.index'); 
    })->name('users.delete');
    
    Route::resource('user/rolesAndPermissions',PermissionsController::class)->middleware('auth');
    Route::get('get-permissions', [PermissionsController::class, 'get_permissions'])->name('get_permissions');
    
    Route::controller(GoodsArchiveController::class)->group(function () {
        Route::get('user/goods_archive','index')->name('goods_archive.index');
        Route::get('user/goods_archive/product/{id}/restore','product_restore')->name('product_restore');
        Route::delete('user/goods_archive/product/{id}/destroy','product_destroy')->name('product_destroy');
        Route::get('user/goods_archive/section/{id}/restore','section_restore')->name('section_restore');
        Route::delete('user/goods_archive/section/{id}/destroy','section_destroy')->name('section_destroy');
    });
    
    Route::controller(InvoicesArchiveController::class)->group(function () {
        Route::get('user/invoices_archive','index')->name('invoices_archive.index');
        Route::get('user/invoices_archive/{id}/restore','restore')->name('invoices_archive.restore');
        Route::delete('user/invoices_archive/{id}/destroy','destroy')->name('invoices_archive.destroy');
    });
    
    Route::controller(ExpensesArchiveController::class)->group(function () {
        Route::get('user/expenses_archive','index')->name('expenses_archive.index');
        Route::get('user/expenses_archive/{id}/restore','restore')->name('expenses_archive.restore');
        Route::delete('user/expenses_archive/{id}/destroy','destroy')->name('expenses_archive.destroy');
    });
});
// Route::get('/test-role', function () {
//     return 'OK';
// })->middleware(['auth','role:Admin']);
