<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class InvoiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'number' => $this->faker->word(),
            'submission_date' => Carbon::now(),
            'due_date' => Carbon::now(),
            'amount' => $this->faker->randomFloat(),
            'url' => $this->faker->url(),
            'is_paid' => $this->faker->boolean(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'category_id' => Category::factory(),
            'submitting_user_id' => User::factory(),
        ];
    }
}
