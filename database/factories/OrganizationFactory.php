<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organization>
 */
class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'org_name' => $this->faker->company,
            'org_country' => $this->faker->country,
            'currency_code' => 'USD',
            'incorporation_date' => $this->faker->date,
            'business_reg_no' => $this->faker->unique()->randomNumber(6),
            'manager_name' => $this->faker->name,
            'manager_contact' => $this->faker->phoneNumber,
            'org_logo' => null, // Set as null by default, you can change this
        ];
    }
}
