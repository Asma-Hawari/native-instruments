<?php

/**
 *  * @author Eng.Asma Hawari
 */

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{

    /**
     * test Register.
     *
     * @return void
     */
    public function testRegister()
    {
        $password = Str::random(30);
        $response = $this->json('POST', route('register'), [
            'name' => $name = 'Test',
            'email' => $email = time() . rand(12345, 678910) . 'test@example.com',
            'password' => bcrypt($password)
        ]);

        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);

        $result = json_decode($response->content(), true);

        // Receive our token
        $this->assertArrayHasKey('token', $result['data']);

    }

    /**
     * test Login.
     *
     * @return void
     */
    public function testLogin()
    {
        $password = Str::random(30);

        // Creating Users
        $user = User::create([
            'name' => 'Test',
            'email' => $email = time() . rand(12345, 678910) . 'test@example.com',
            'password' => bcrypt($password)
        ]);

        // Simulated landing
        $response = $this->json('POST', route('login'), [
            'email' => $email,
            'password' => $password,
        ]);

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        // Determine whether the login is successful and receive token
        $response->assertStatus(200);

        $result = json_decode($response->content(), true);

        // Receive our token
        $this->assertArrayHasKey('token', $result['data']);

        // Delete users
        User::where('email', 'test@gmail.com')->delete();
    }
}