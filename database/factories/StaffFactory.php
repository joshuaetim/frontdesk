<?php

namespace Database\Factories;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;

class StaffFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Staff::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // $faker = \Faker\Factory::create('en_NG');

        return [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'position' => $this->faker->jobTitle,
            'salary' => $this->faker->randomFloat(2, 1000, 500000),
            'start_date' => $this->faker->date(),
            'status' => 'active',
            // 'user_id' => 1
        ];
    }
}
