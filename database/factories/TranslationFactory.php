<?php

namespace Database\Factories;

use App\Models\Locale;
use App\Models\Translation;
use Illuminate\Database\Eloquent\Factories\Factory;

class TranslationFactory extends Factory
{
    protected $model = Translation::class;

    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->slug(2),
            'locale_id' => Locale::inRandomOrder()->value('id'),
            'value' => $this->faker->sentence(),
        ];
    }
}