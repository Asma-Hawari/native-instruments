<?php
/**
 *  * @author Eng.Asma Hawari
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\UserPurchasedController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('auth', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'signup'])->name('register');

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::resource('products', ProductController::class);

Route::middleware('auth:sanctum')->group( function () {

    // get user data
    Route::GET('user', [UserPurchasedController::class,'getUserData']);

    //get Purchased Item for a user
    Route::GET('user/products',[UserPurchasedController::class,'getUserProduct']);

    //Attach an Item to Purchase List for a user
    Route::POST('user/products', [UserPurchasedController::class,'addPurchasedItem']);

    //Deattach an Item to Purchase List for a user
    Route::DELETE('user/products/{sku}',[UserPurchasedController::class,'deletePurchasedItems']);
});
