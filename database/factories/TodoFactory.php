<?php

namespace Database\Factories;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TodoFactory extends Factory
{
    protected $model = Todo::class;

    public function definition(): array
    {
    	return [
    	    'content' => $this->faker->name(),
    	    'user_id' => User::inRandomOrder()->pluck('id')->first(),
    	    'completion_time' => null,
    	];
    }
}
