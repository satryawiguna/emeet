<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Blog::class;

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
            'blog_category_id' => BlogCategory::all()->random()->id,
            'title' => $title,
            'slug' => $slug,
            'content' => $this->faker->realText
        ];
    }
}
