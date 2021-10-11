<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Staff;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StaffTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_staff()
    {
        $user = User::factory()->create();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->getJson('/api/staff');
        
        $response->assertStatus(200);
    }

    public function test_guest_cannot_see_staff()
    {
        $response = $this->getJson('/api/staff');
        
        $response->assertStatus(401);
    }

    public function test_user_can_create_staff()
    {
        $user = User::factory()->create();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->postJson('/api/staff', [
            'name' => 'Staff',
            'position' => 'Manager',
            'salary' => 45030.67,
            'status' => 'active',
            'email' => 'email@example.com',
            'phone' => '+234838382882',
            'address' => '45, fff street',
            'start_date' => '2019-12-12'
        ]);

        // $response->dump();

        $response->assertStatus(201);
        $this->assertCount(1, Staff::all());
    }

    public function test_user_can_see_single_staff()
    {
        $user = User::factory()->create();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        // create staff
        $response = $this->postJson('/api/staff', [
            'name' => 'Staff',
            'position' => 'Manager',
            'salary' => 45030.67,
            'status' => 'active',
            'email' => 'email@example.com',
            'phone' => '+234838382882',
            'address' => '45, fff street',
            'start_date' => '2019-12-12'
        ]);

        // $response->dump();

        $id = $response['data']['id'];

        $response->assertStatus(201);
        $this->assertCount(1, Staff::all());


        // view single staff
        $response = $this->getJson('/api/staff/'.$id);

        $response->assertStatus(200)
                ->assertJsonPath('data.id', $id);
    }

    public function test_user_can_update_staff()
    {
        $user = User::factory()->create();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        // create staff
        $staff = Staff::factory()->state([
            'user_id' => $user->id
        ])->create();
        
        // update staff

        $response = $this->putJson('/api/staff/'.$staff->id, [
            'name' => 'Staff-Updated',
            'position' => 'Manager',
            'salary' => 45030.67,
            'status' => 'active',
            'email' => 'email@example.com',
            'phone' => '+234838382882',
            'address' => '45, fff street',
            'start_date' => '2019-12-12'
        ]);

        // $response->dump();
        // $newName = $response['data']['name'];

        $response->assertStatus(200)
                ->assertJsonPath('data.name', 'Staff-Updated');
    }

    public function test_user_can_delete_staff()
    {
        $user = User::factory()->create();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        // create staff
        $staff = Staff::factory()->state([
            'user_id' => $user->id
        ])->create();

        // delete staff
        $response = $this->deleteJson('/api/staff/'.$staff->id);

        $response->assertStatus(200);
        $this->assertCount(0, Staff::all());
    }
}
