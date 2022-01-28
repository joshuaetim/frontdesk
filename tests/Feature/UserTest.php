<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use App\Mail\SendPasswordReset;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'first_name' => 'Josh',
            'last_name' => 'Josh',
            'email' => 'jj@gmail.com',
            'password' => 'password',
            'c_password' => 'password'
        ]);
        
        $response->assertStatus(201);
        
        $this->assertCount(1, User::all()); // ensure it's present in database
    }

    public function test_user_can_log_in()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200)
        ->assertJsonPath('data.user.id', $user->id);
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->postJson('/api/logout');
        
        $response->assertStatus(200);
    }

    public function test_user_can_get_recovery_email()
    {
        Mail::fake();

        $user = User::factory()->create();

        $response = $this->postJson('/api/recover-password', [
            'email' => $user->email,
        ]);

        Mail::assertQueued(SendPasswordReset::class);
    }
}
