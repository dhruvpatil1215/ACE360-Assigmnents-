<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'company_id' => Company::factory(),
            'manager_id' => null,
            'country' => fake()->country(),
            'state' => fake()->state(),
            'city' => fake()->city(),
            'address' => fake()->streetAddress(),
            'hire_date' => fake()->dateTimeBetween('-5 years', 'now'),
        ];
    }

    /**
     * Indicate that the employee has a manager.
     */
    public function withManager(Employee $manager): static
    {
        return $this->state(fn(array $attributes) => [
            'manager_id' => $manager->id,
            'company_id' => $manager->company_id,
        ]);
    }
}
