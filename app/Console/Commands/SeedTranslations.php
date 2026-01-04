<?php

namespace App\Console\Commands;

use App\Models\Translation;
use Illuminate\Console\Command;

class SeedTranslations extends Command
{
    protected $signature = 'translations:seed {count=100000}';

    protected $description = 'Seed translations table with a large dataset';

    public function handle(): int
    {
        $count = (int) $this->argument('count');

        $this->info("Seeding {$count} translations...");

        Translation::factory()
            ->count($count)
            ->create();

        $this->info('Seeding completed successfully.');

        return self::SUCCESS;
    }
}
