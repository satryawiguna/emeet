<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Page::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->text(25);
        $slug = str_replace(' ', '-', $title);

        return [
            'title' => $title,
            'slug' => $slug,
            'content' => $this->faker->realText
        ];
    }
}
