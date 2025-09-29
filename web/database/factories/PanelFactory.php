<?php

namespace Database\Factories;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PanelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'panel_id' => strtoupper(Str::random(12)),
            'unit_id' => Unit::factory(), // relasi otomatis ke Unit
            'dust' => $this->faker->randomFloat(2, 0, 100),
            'current' => $this->faker->randomFloat(2, 0, 20),
            'voltage' => $this->faker->randomFloat(2, 100, 300),
            'power' => $this->faker->randomFloat(2, 100, 5000),
            'pump_status' => $this->faker->boolean(),
            'wiper_status' => $this->faker->boolean(),
            'installed_at' => now(),
            'updated_at' => now()
        ];
    }
}
