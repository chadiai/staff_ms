<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'upload_date' => Carbon::now(),
            'name' => $this->faker->name(),
            'url' => $this->faker->url(),
            'is_confidential' => $this->faker->boolean(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'category_id' => Category::factory(),
            'uploading_user_id' => User::factory(),
        ];
    }
}
