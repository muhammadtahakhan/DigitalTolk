<?php
namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Seeder;

class TranslationSeeder extends Seeder
{
    public function run(): void
    {
        Translation::factory()
            ->count(100_000)
            ->create();
    }
}
