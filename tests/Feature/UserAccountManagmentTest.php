<?php

/**
 *  * @author Eng.Asma Hawari
 */

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Str;

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
     *
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

    public function getProductSKU()
    {
        $product = Product::find(1)->first();
        if ($product) {
            return $product->sku;
        } else
            return false;
    }

    /**
     * test create product.
     *
     * @return void
     */
    public function test_get_purchased_item()
    {
        $sku = $this->getProductSKU();

        $this->assertIsString($sku);

        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', 'api/user/products', [
            'sku' => $sku,
        ]);

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    /**
     * test update product.
     *
     * @return void
     */
    public function test_add_purchased_item()
    {
        $sku = $this->getProductSKU();

        $this->assertIsString($sku);

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
     * test delete products.
     *
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


}
