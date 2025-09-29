<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Panel;

class PanelSeeder extends Seeder
{
    public function run(): void
    {
        // generate 10 panel lengkap dengan unit + user
        Panel::factory()->count(10)->create();
    }
}
