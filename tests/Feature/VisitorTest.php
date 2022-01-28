<?php

namespace Tests\Feature;

use App\Models\Staff;
use App\Models\User;
use App\Models\Visitor;
use App\Providers\SendVisitorNotification;
use App\Providers\VisitorLogged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class VisitorTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_can_see_visitors()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson('/api/visitors');

        $response->assertStatus(200);
    }

    public function test_guest_cannot_see_visitor()
    {
        $response = $this->getJson('/api/visitors');

        $response->assertStatus(401);
    }

    public function test_user_can_create_visitor()
    {
        Event::fake();

        $user = User::factory()
                    ->has(Staff::factory()->count(1))
                    ->create();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $staffId = $user->staff()->first()->id;

        $response = $this->postJson('/api/visitors', [
            'full_name' => 'Pete Test',
            'occupation' => 'student',
            'email' => 'email@example.com',
            'phone' => '+234838382882',
            'address' => '45, fff street',
            'note' => 'Note',
            'staff_id' => $staffId,
        ]);

        // $response->dump();

        $response->assertStatus(201);
        $this->assertCount(1, Visitor::all());

        Event::assertDispatched(VisitorLogged::class);

        Event::assertListening(
            VisitorLogged::class,
            SendVisitorNotification::class
        );
    }

    public function test_user_can_see_single_visitor()
    {
        $user = User::factory()
                    ->has(Staff::factory()->count(1))
                    ->has(Visitor::factory()->count(1))
                    ->create();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $visitorId = $user->visitors()->first()->id;

        $response = $this->getJson('/api/visitors/'.$visitorId);

        $response->assertStatus(200)
                ->assertJsonPath('data.id', $visitorId);
    }

    public function test_user_can_update_visitor()
    {
        $user = User::factory()
                    ->has(Staff::factory()->count(1))
                    ->has(Visitor::factory()->count(1))
                    ->create();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $visitorId = $user->visitors()->first()->id;

        $response = $this->putJson('/api/visitors/'.$visitorId, [
            'full_name' => 'Pete Test',
        ]);

        $response->assertStatus(200)
                ->assertJsonPath('data.full_name', 'Pete Test');
    }

    public function test_user_can_delete_visitor()
    {
        $user = User::factory()
                    ->has(Staff::factory()->count(1))
                    ->has(Visitor::factory()->count(1))
                    ->create();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $visitorId = $user->visitors()->first()->id;

        // delete visitor
        $response = $this->deleteJson('/api/visitors/'.$visitorId);

        $response->assertStatus(200);
        $this->assertCount(0, Visitor::all());
    }
}
