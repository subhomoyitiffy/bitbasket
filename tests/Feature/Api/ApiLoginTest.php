<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use Tests\TestCase;

class ApiLoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * API Login/Authentication test
     * @test
    */
    public function function_should_resurn_a_token_if_login_is_successfull(){
        // Create a user
        $user = User::factory()->create([
            'role_id'=> env('MEMBER_ROLE_ID'),
            'password' => Hash::make('password123'),
        ]);

        // Simulate a successful login
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
            'role_id'=> env('MEMBER_ROLE_ID')
        ]);

        // Assert the response status and check for a token
        $response->assertStatus(200)
                ->assertJson([
                    'success'=>true,
                    'message'=>'Login has successfully done.',
                    'data'=>[
                        'token'=>true,
                        'token_type'=>'bearer',
                        'user'=>[]
                    ]
                ]);

    }

}
