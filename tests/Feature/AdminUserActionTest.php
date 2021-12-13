<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminUserActionTest extends TestCase
{
   use RefreshDatabase;

   public function test_can_get_all_users()
   {
       $user = User::factory()->create();

       $response = $this->getJson('/api/admin/users');

       $response->assertStatus(200);
   }

   public function test_can_get_single_user()
   {
       $user = User::factory()->create();

       $response = $this->getJson('/api/admin/users/'.$user->id);

       $response->assertStatus(200)
                ->assertJsonPath('data.id', $user->id);
   }

   public function test_can_update_user()
   {
        $user = User::factory()->create();

        $response = $this->putJson('/api/admin/users/'.$user->id, [
            'first_name' => 'Josh-Updated',
            'last_name' => 'Josh',
            'email' => 'jj@gmail.com',
        ]);

        $response->assertStatus(200)
                ->assertJsonPath('data.first_name', 'Josh-Updated');
   }

   public function test_can_change_user_status()
   {
        $user = User::factory()->create();

        $response = $this->postJson("/api/admin/users/{$user->id}/status", [
            'status' => 'inactive'
        ]);

        $response->assertStatus(200)
                ->assertJsonPath('data.status', 'inactive');
   }

   public function test_can_delete_user()
   {
       $user = User::factory()->create();
       
       $response = $this->deleteJson('/api/admin/users/'.$user->id);

       $response->assertStatus(200);
   }
}
