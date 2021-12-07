<?php

/**
 *  * @author Eng.Asma Hawari
 */

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Models\Product;
use App\Http\Resources\Product as ProductResource;

class ProductController extends BaseController
{

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return $this->sendResponse(ProductResource::collection($products), 'Products fetched.');
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'sku' => 'required',
            'name' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());
        }
        $product = Product::create($input);
        return $this->sendResponse(new ProductResource($product), 'Product created.');
    }


    /**
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        if (is_null($product)) {
            return $this->sendError('Product does not exist.');
        }
        return $this->sendResponse(new ProductResource($product), 'Product fetched.');
    }


    /**
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'sku' => 'required',
            'name' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());
        }

        $product->sku = $input['sku'];
        $product->name = $input['name'];
        $product->save();

        return $this->sendResponse(new ProductResource($product), 'Product updated.');
    }

    /**
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return $this->sendResponse([], 'Product deleted.');
    }
}