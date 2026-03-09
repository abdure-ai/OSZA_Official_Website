<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeroSlide;
use App\Models\Post;
use App\Models\Woreda;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Hero Slides
        HeroSlide::updateOrCreate(
            ['title_en' => 'Welcome to Oromo Special Zone'],
            [
                'subtitle_en' => 'Official Portal for Government Services and Information',
                'title_am' => 'እንኳን ወደ ኦሮሞ ልዩ ዞን በደህና መጡ',
                'subtitle_am' => 'ለመንግስት አገልግሎቶች እና መረጃዎች ኦፊሴላዊ ፖርታል',
                'title_or' => 'Gara Godina Addaa Oromootti Baga Nagaan Dhuftan',
                'subtitle_or' => 'Wiirtuu Odeeffannoo fi Tajaajila Mootummaa',
                'cta_text' => 'Get Started',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        // Sample Woredas
        $woredas = ['Akaki', 'Berek', 'Gelano', 'Mulo', 'Sendafa', 'Sululta', 'Sebeta', 'Welmera'];
        foreach ($woredas as $w) {
            Woreda::updateOrCreate(
                ['slug' => strtolower($w)],
                [
                    'name_en' => $w,
                    'description_en' => "The $w Woreda is part of the Oromo Special Zone.",
                    'is_active' => true,
                ]
            );
        }

        // Sample News
        Post::updateOrCreate(
            ['title_en' => 'New Administration Portal Launched'],
            [
                'content_en' => 'We are proud to announce the launch of our new unified digital administration portal.',
                'category' => 'news',
                'status' => 'published',
                'published_at' => now(),
            ]
        );
    }
}
