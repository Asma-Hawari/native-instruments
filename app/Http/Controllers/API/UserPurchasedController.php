<?php
/**
 *  * @author Eng.Asma Hawari
 */

namespace App\Http\Controllers\API;


use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Models\Product;
use App\Http\Resources\Product as ProductResource;
use Illuminate\Support\Facades\DB;

class UserPurchasedController extends BaseController
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function getUserData()
    {
        $userName = auth()->user()->name;
        return $this->sendResponse($userName, 'Get User Data.');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function getUserProduct()
    {
        $products = auth()->user()->products;
        return $this->sendResponse(ProductResource::collection($products), 'Get Purchased  Products from the User.');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function addPurchasedItem(Request $request)
    {
        $userAuth = auth()->user();
        $user = User::find($userAuth->id);
        $input = $request->all();

        $validator = Validator::make($input, [
            'sku' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $product = DB::table('products')->where('sku', $input)->first();
        if (!$product) {
            return $this->sendError('Product not Found');
        }
        // TO Avoid Duplication in the Pivot Table
        $user->products()->syncWithoutDetaching($product->id);
        return $this->sendResponse(new ProductResource($product), 'Product attached.');
    }

    /**
     * @param $sku
     * @return \Illuminate\Http\Response
     */
    public function deletePurchasedItems($sku)
    {
        $userAuth = auth()->user();
        $user = User::find($userAuth->id);
        $product = DB::table('products')->where('sku', $sku)->first();
        if (!$product) {
            return $this->sendError('Product not Found');
        }
        $user->products()->detach($product->id);
        return $this->sendResponse([], 'Product removed from user account.');
    }
}