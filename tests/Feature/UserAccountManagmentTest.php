<?php

/**
 *  * @author Eng.Asma Hawari
 */

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Mail\Transport\SesTransport;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserAccountManagmentTest extends TestCase
{
    /**
     * Authenticate user.
     *
     * @return string
     */
    protected function authenticate()
    {
        $password = Str::random(30);

        $user = User::create([
            'name' => 'test',
            'email' => time() . rand(12345, 678910) . 'test@gmail.com',
            'password' => bcrypt($password),
        ]);

        if (!auth()->attempt(['email' => $user->email, 'password' => $password])) {
            return response(['message' => 'Login credentials are invaild']);
        }

        return $accessToken = auth()->user()->createToken('authToken')->accessToken;
    }

    /**
     * test get all products.
     * Positive Test
     *
     * @return void
     */
    public function test_get_user_data()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', 'api/user');


        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);
        $response->assertStatus(200);
        $result = json_decode($response->getContent(), true);
        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('data', $result);
    }

    /**
     * test get all products.
     * Negative Test "Unauthenticated"
     *
     * @return void
     */
    public function test_get_user_data_Unauthenticated()
    {
        $token = Str::random(32);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', 'api/user');

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);
        $response->assertStatus(401);
    }

    /**
     * test get all products.
     *Positive Test
     * @return void
     */
    public function test_get_user_products()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', 'api/user/products');

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }
    /**
     * test get all products.
     *Negative Test
     * @return void
     */
    public function test_get_user_products_Unauthenticated()
    {
        $token = Str::random(32);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', 'api/user/products');

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(401);
    }

    public function getSkus()
    {
         return DB::table('products')
            ->select('sku')
            ->get()->toArray();
    }

    /**
     * @return bool
     */
    public function getProductSKU()
    {
        $product = Product::all()->random();
        if ($product) {
            return $product->sku;
        } else
            return false;
    }

    /**
     * test create product.
     * Positive Test
     * @return void
     */
    public function test_get_purchased_item()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', 'api/user/products');

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    /**
     * test create product.
     * Negative test
     * @return void
     */
    public function test_get_purchased_item_Unauthenticated()
    {
        $token = Str::random(32);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', 'api/user/products');

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(401);
    }

    /**
     * test update product.
     * Positive Test
     * @return void
     */
    public function test_add_purchased_item()
    {

        $sku = $this->getSkus()[0];

        $this->assertIsString($sku->sku);

        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', 'api/user/products', [
            'sku' => $sku,
        ]);

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }
    /**
     * test update product.
     * Negative Test - Unauthorized
     * @return void
     */
    public function test_add_purchased_item_unauthorized()
    {

        $sku = $this->getSkus()[0];

        $this->assertIsString($sku->sku);

        $token = STR::random(32);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', 'api/user/products', [
            'sku' => $sku,
        ]);

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(401);
    }

    /**
     * test update product.
     * Negative Test - Not Found
     * @return void
     */
    public function test_add_purchased_item_not_found()
    {

        $sku = STR::random(3);

        $this->assertIsString($sku);

        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', 'api/user/products', [
            'sku' => $sku,
        ]);

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(404);
    }

    /**
     * test delete products.
     * Positive Test
     * @return void
     */
    public function test_delete_purchased_item()
    {
        $sku = $this->getProductSKU();

        $this->assertIsString($sku);

        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('DELETE', 'api/user/products/' . $sku);

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }
    /**
     * test delete products.
     * Negative Test - unauthorized
     * @return void
     */
    public function test_delete_purchased_item_unauthorized()
    {
        $sku = $this->getProductSKU();

        $this->assertIsString($sku);

        $token = Str::random(32);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('DELETE', 'api/user/products/' . $sku);

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(401);
    }
    
    /**
     * test delete products.
     * Negative Test - Not Found
     * @return void
     */
    public function test_delete_purchased_item_not_found()
    {
        $sku = STR::random(4);

        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('DELETE', 'api/user/products/' . $sku);

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(404);
    }


}
