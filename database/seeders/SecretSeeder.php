<?php

namespace Database\Seeders;

use App\Models\Secret;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SecretSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Secret::factory()->count(20)->create();
    }
}
