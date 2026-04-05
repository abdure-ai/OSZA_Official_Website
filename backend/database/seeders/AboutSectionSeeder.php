<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AboutSection;

class AboutSectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'type' => 'mission',
                'title_en' => 'Our Mission',
                'title_am' => 'ተልዕኳችን',
                'title_or' => 'Ergaa Keenya',
                'content_en' => 'To deliver transparent, accountable, and efficient public administration that improves the quality of life for all citizens of the Oromo Special Zone through inclusive development, good governance, and community engagement.',
                'icon' => 'mission',
                'sort_order' => 1,
            ],
            [
                'type' => 'vision',
                'title_en' => 'Our Vision',
                'title_am' => 'ራዕያችን',
                'title_or' => 'Mul’ata Keenya',
                'content_en' => 'A prosperous, peaceful and sustainable Oromo Special Zone where every citizen enjoys equal rights and opportunities for social, economic and cultural development.',
                'icon' => 'vision',
                'sort_order' => 2,
            ],
            [
                'type' => 'history',
                'title_en' => 'Our History',
                'title_am' => 'ታሪካችን',
                'title_or' => 'Seenaa Keenya',
                'content_en' => 'The Oromo Special Zone was established with a focus on administrative efficiency and preserving the cultural heritage of the Oromo people within the region. Over the years, it has evolved into a hub of development and innovation.',
                'icon' => 'history',
                'sort_order' => 0,
            ],
        ];

        foreach ($sections as $section) {
            AboutSection::create($section);
        }
    }
}
