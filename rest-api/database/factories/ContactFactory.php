<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'state' => $this->faker->country(),
            'region' => $this->faker->name(),
            'city' => $this->faker->city(),
            'address' => $this->faker->address(),
            'post_code' => $this->faker->postcode(),
            'phone_code' => $this->faker->randomNumber(2),
            'phone_number' => $this->faker->phoneNumber()
        ];
    }
}
