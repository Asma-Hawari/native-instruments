<?php

/**
 *  * @author Eng.Asma Hawari
 */

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;

class ProductTest extends TestCase
{

    /**
     * test get all products.
     *
     * @return void
     */
    public function test_get_all_product()
    {

        $response = $this->json(
            'GET', 'api/products');

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }


}