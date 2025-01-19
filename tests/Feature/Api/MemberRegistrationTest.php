<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

use Tests\TestCase;
use App\Models\User;
use App\Mail\SignupOtp;
use App\Mail\RegistrationSuccess;

class MemberRegistrationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * API Registration test
     * @test
    */
    public function function_should_resurn_a_success_if_registration_process_is_successfull_done()
    {
        // Fake sending emails (no actual emails will be sent)
        Mail::fake();

        $data = [
            'country'=> 'usa',
            'first_name'=> 'Test',
            'last_name'=> 'User',
            'email'=> 'test_user@gmail.com',
            'country_code' => '+1',
            'phone'=> '0000 0000 00',
            'password'=> 'password',
            'c_password'=> 'password',
            'company_type' => 'Test',
            'employer_identification_no' => 'Test#123'
        ];

        // Send POST request to the registration endpoint
        $response = $this->postJson('/api/sign-up', $data);

        // Assert that the user was created
        $this->assertDatabaseHas('users', [
            'email' => 'test_user@gmail.com',
        ]);

        Mail::assertSent(
            SignupOtp::class
        );

        // Assert the response is successful
        $response->assertStatus(200)
                    ->assertJson([
                        'success'=>true,
                        'message'=>'Registration step 1 has done. Please verify OTP already send in your registered email.',
                        'data'=>[]
                    ]);
    }

    /**
     * API Registration OTP verification Test and Return success if Verification is successfully done.
     * @test
    */
    public function user_can_verify_otp_and_resurn_a_success_if_verification_is_successfull_done()
    {
        // Fake sending emails (no actual emails will be sent)
        Mail::fake();

        // Create a user with a mock OTP and expiry time
        $user = User::factory()->create([
            'role_id'=> env('MEMBER_ROLE_ID'),
            'name'=> 'Test User',
            'email'=> 'test_user@gmail.com',
            'country_code' => '+1',
            'phone'=> '0000 0000 00',
            'password'=> 'password',
            'status'=> 0,
            'remember_token' => base64_encode('1234'),
            'email_verified_at' => now()->addMinutes(10), // OTP expires in 5 minutes
        ]);

        // Prepare the OTP verification data
        $data = [
            'email' => $user->email,
            'otp' => '1234', // Correct OTP
        ];
        // Send POST request to verify the OTP
        $response = $this->postJson('/api/sign-up/otp-verification', $data);

        Mail::assertSent(
            RegistrationSuccess::class
        );

        // Assert the OTP is verified successfully
        $response->assertStatus(200)
                    ->assertJson([
                        'success'=>true,
                        'message'=>'Your account verification has successfully done.',
                        'data'=>[]
                    ]);

    }

    /**
     * API Registration OTP verification Test and Return error if Verification is not done.
     * @test
    */
    public function user_cannot_verify_otp_and_return_an_error()
    {
        // Fake sending emails (no actual emails will be sent)
        Mail::fake();

        // Create a user with a mock OTP and expiry time
        $user = User::factory()->create([
            'role_id'=> env('MEMBER_ROLE_ID'),
            'name'=> 'Test User',
            'email'=> 'test_user@gmail.com',
            'country_code' => '+1',
            'phone'=> '0000 0000 00',
            'password'=> 'password',
            'status'=> 0,
            'remember_token' => base64_encode('1234'),
            'email_verified_at' => now()->addMinutes(10), // OTP expires in 5 minutes
        ]);

        // Prepare the OTP verification data
        $data = [
            'email' => $user->email,
            'otp' => 'xxxx', // Correct OTP
        ];
        // Send POST request to verify the OTP
        $response = $this->postJson('/api/sign-up/otp-verification', $data);

        // Assert the OTP is verified successfully
        $response->assertStatus(401)
                    ->assertJson([
                        'success'=>false,
                        'error'=>'Request email is not found Or OTP not matched.'
                    ]);

    }


}
