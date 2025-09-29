<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UnitFactory extends Factory
{
    public function definition(): array
    {
        return [
            'unit_id' => strtoupper(Str::random(12)), // misal random ID
            'user_id' => 1,             // bikin user baru kalau belum ada
            'power' => $this->faker->randomFloat(2, 100, 5000), // 100 - 5000 watt
            'location' => $this->faker->city(),
        ];
    }
}
