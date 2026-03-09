<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TouristSite;
use App\Models\Woreda;

class TourismSeeder extends Seeder
{
    public function run(): void
    {
        $welmera = Woreda::where('slug', 'welmera')->first();
        $sebeta = Woreda::where('slug', 'sebeta')->first();
        $sululta = Woreda::where('slug', 'sululta')->first();
        $akaki = Woreda::where('slug', 'akaki')->first();

        $sites = [
            [
                'name_en' => 'Suba Forest Park',
                'name_am' => 'የሱባ ደን ፓርክ',
                'slug' => 'suba-forest-park',
                'description_en' => 'One of the oldest conservation areas in Africa, Suba Forest offers magnificent trees, endemic wildlife, and breathtaking hiking trails.',
                'category' => 'Nature',
                'woreda_id' => $welmera?->id,
                'location_name_en' => 'Menagesha Suba',
                'cover_image_url' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?q=80&w=1200&auto=format&fit=crop',
                'gallery_urls' => [
                    'https://images.unsplash.com/photo-1473448912268-2022ce9509d8?q=80&w=800&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1511497584788-876760111969?q=80&w=800&auto=format&fit=crop'
                ],
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name_en' => 'Gefersa Reservoir',
                'name_am' => 'ገፈርሳ ማጠራቀሚያ',
                'slug' => 'gefersa-reservoir',
                'description_en' => 'A serene water body surrounded by lush greenery, perfect for bird watching and peaceful afternoon retreats.',
                'category' => 'Nature',
                'woreda_id' => $sebeta?->id,
                'location_name_en' => 'Near Sebeta',
                'cover_image_url' => 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?q=80&w=1200&auto=format&fit=crop',
                'gallery_urls' => [
                    'https://images.unsplash.com/photo-1470770841072-f978cf4d019e?q=80&w=800&auto=format&fit=crop'
                ],
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name_en' => 'Menagesha Amba',
                'name_am' => 'መናገሻ አምባ',
                'slug' => 'menagesha-amba',
                'description_en' => 'A historical mountain with significant cultural value and panoramic views of the entire Special Zone.',
                'category' => 'History',
                'woreda_id' => $welmera?->id,
                'location_name_en' => 'Menagesha',
                'cover_image_url' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=1200&auto=format&fit=crop',
                'gallery_urls' => [],
                'is_active' => true,
                'sort_order' => 3
            ]
        ];

        foreach ($sites as $site) {
            TouristSite::updateOrCreate(['slug' => $site['slug']], $site);
        }
    }
}
