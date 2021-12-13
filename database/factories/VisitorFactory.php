<?php

namespace Database\Factories;

use App\Models\Visitor;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisitorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Visitor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'full_name' => $this->faker->name,
            'occupation' => 'student',
            'note' => 'meeting',
            'email' => $this->faker->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'staff_id' => 1,
        ];
    }
}
