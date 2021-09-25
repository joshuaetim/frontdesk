<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        User::factory()
        ->count(2)
        ->has(Staff::factory()
                ->count(10)
                ->state(new Sequence(
                    ['status' => 'active'],
                    ['status' => 'inactive'],
                )), 'staff')
        ->create();
    }
}
