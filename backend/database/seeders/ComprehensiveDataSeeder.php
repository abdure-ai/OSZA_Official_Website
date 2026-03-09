<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tender;
use App\Models\Vacancy;
use App\Models\Document;
use App\Models\Project;
use App\Models\Investment;
use App\Models\GalleryItem;
use App\Models\EmergencyAlert;
use App\Models\Post;
use Carbon\Carbon;

class ComprehensiveDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. TENDERS
        $tenders = [
            [
                'title_en' => 'Construction of Zonal General Hospital',
                'ref_number' => 'OSZ-TEN-2026-001',
                'description_en' => 'Seeking qualified contractors for the construction of a new 500-bed general hospital in the capital.',
                'status' => 'active',
                'deadline' => Carbon::now()->addDays(30),
            ],
            [
                'title_en' => 'Supply and Installation of Solar Street Lights',
                'ref_number' => 'OSZ-TEN-2026-002',
                'description_en' => 'Procurement of 2,000 solar-powered street lighting units for urban Woredas.',
                'status' => 'active',
                'deadline' => Carbon::now()->addDays(15),
            ],
            [
                'title_en' => 'IT Infrastructure Upgrade for Admin Headquarters',
                'ref_number' => 'OSZ-TEN-2025-089',
                'description_en' => 'Complete overhaul of data center and networking infrastructure.',
                'status' => 'closed',
                'deadline' => Carbon::now()->subDays(10),
            ],
        ];
        foreach ($tenders as $tc) {
            Tender::updateOrCreate(['ref_number' => $tc['ref_number']], $tc);
        }

        // 2. VACANCIES
        $vacancies = [
            [
                'title_en' => 'Senior Urban Planner',
                'department' => 'Urban Development Bureau',
                'description_en' => 'Responsible for designing sustainable Woreda expansion plans and zoning regulations.',
                'is_active' => true,
                'deadline' => Carbon::now()->addDays(45),
            ],
            [
                'title_en' => 'Chief Medical Officer',
                'department' => 'Health Bureau',
                'description_en' => 'Oversees all clinical operations across the Zonal health centers and hospitals.',
                'is_active' => true,
                'deadline' => Carbon::now()->addDays(20),
            ],
            [
                'title_en' => 'Agricultural Extension Expert',
                'department' => 'Agriculture Bureau',
                'description_en' => 'Provides modern farming techniques and support directly to local farming communities.',
                'is_active' => true,
                'deadline' => Carbon::now()->addDays(30),
            ],
            [
                'title_en' => 'Database Administrator',
                'department' => 'Information Technology',
                'description_en' => 'Manages the central citizen and administrative databases for the Zone.',
                'is_active' => false,
                'deadline' => Carbon::now()->subDays(5),
            ],
        ];
        foreach ($vacancies as $vc) {
            Vacancy::updateOrCreate(['title_en' => $vc['title_en']], $vc);
        }

        // 3. DOCUMENTS (Library)
        $documents = [
            [
                'title_en' => '5-Year Strategic Growth Plan (2025-2030)',
                'author' => 'Planning Commission',
                'category' => 'Policy',
                'file_url' => '',
                'cover_image_url' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=400&h=600&fit=crop',
            ],
            [
                'title_en' => 'Annual Budget Report FY2025',
                'author' => 'Finance Bureau',
                'category' => 'Report',
                'file_url' => '',
                'cover_image_url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=400&h=600&fit=crop',
            ],
            [
                'title_en' => 'Revised Investment Directives',
                'author' => 'Investment Commission',
                'category' => 'Regulation',
                'file_url' => '',
                'cover_image_url' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=400&h=600&fit=crop',
            ],
            [
                'title_en' => 'Zonal Health Infrastructure Guidelines',
                'author' => 'Health Bureau',
                'category' => 'Manual',
                'file_url' => '',
                'cover_image_url' => 'https://images.unsplash.com/photo-1505751172876-fa1923c5c528?w=400&h=600&fit=crop',
            ],
        ];
        foreach ($documents as $doc) {
            Document::updateOrCreate(['title_en' => $doc['title_en']], $doc);
        }

        // 4. PROJECTS
        $projects = [
            [
                'title_en' => 'Gefersa Water Supply Expansion',
                'location_en' => 'Sebeta / Gefersa',
                'status' => 'Ongoing',
                'budget' => 1500000000,
                'description_en' => 'Expanding the water treatment capacity to serve additional Woredas.',
                'start_date' => '2025-01-15',
                'cover_image_url' => 'https://images.unsplash.com/photo-1541888014521-1b0553759eb5?w=800&h=450&fit=crop',
            ],
            [
                'title_en' => 'Oromia Special Zone Industrial Park',
                'location_en' => 'Dukem / Akaki',
                'status' => 'Planning',
                'budget' => 4200000000,
                'description_en' => 'A state-of-the-art industrial park focusing on agro-processing and textile manufacturing.',
                'start_date' => '2026-09-01',
                'cover_image_url' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=800&h=450&fit=crop',
            ],
            [
                'title_en' => 'Rural Electrification Phase III',
                'location_en' => 'Zone-wide',
                'status' => 'Completed',
                'budget' => 850000000,
                'description_en' => 'Connecting 150 rural Kelebes to the national grid.',
                'start_date' => '2023-05-10',
                'end_date' => '2025-11-20',
                'cover_image_url' => 'https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?w=800&h=450&fit=crop',
            ]
        ];
        foreach ($projects as $proj) {
            Project::updateOrCreate(['title_en' => $proj['title_en']], $proj);
        }

        // 5. INVESTMENTS
        $investments = [
            [
                'title_en' => 'Agro-Processing Zone Development',
                'category' => 'Priority Area',
                'description_en' => 'Lucrative opportunities for large-scale agricultural processing, benefiting from tax holidays and ready infrastructure.',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1592982537447-6f2a6a0c5c16?w=800&h=450&fit=crop',
            ],
            [
                'title_en' => 'Eco-Tourism Resorts',
                'category' => 'Emerging Sector',
                'description_en' => 'Invest in premium eco-resorts around the Suba Forest and Gefersa reservoir, tapping into the growing domestic and international tourism market.',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?w=800&h=450&fit=crop',
            ],
            [
                'title_en' => 'Renewable Energy Assembly Plant',
                'category' => 'Priority Area',
                'description_en' => 'Establishment of localized assembly points for solar panels and wind turbines.',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1509391366360-2e959784a276?w=800&h=450&fit=crop',
            ]
        ];
        foreach ($investments as $inv) {
            Investment::updateOrCreate(['title_en' => $inv['title_en']], $inv);
        }

        // 6. GALLERY ITEMS
        $gallery = [
            [
                'title' => 'Annual Cultural Festival',
                'category' => 'Culture',
                'image_url' => 'https://images.unsplash.com/photo-1533560904424-a0c61dc306fc?w=600&h=600&fit=crop',
                'is_active' => true,
            ],
            [
                'title' => 'New Hospital Inauguration',
                'category' => 'Development',
                'image_url' => 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=600&h=600&fit=crop',
                'is_active' => true,
            ],
            [
                'title' => 'Agriculture Exhibition 2025',
                'category' => 'Agriculture',
                'image_url' => 'https://images.unsplash.com/photo-1595841696650-6e4f323c2bd6?w=600&h=600&fit=crop',
                'is_active' => true,
            ],
            [
                'title' => 'Zonal Administration Meeting',
                'category' => 'Governance',
                'image_url' => 'https://images.unsplash.com/photo-1577415124269-fc1140a69e91?w=600&h=600&fit=crop',
                'is_active' => true,
            ]
        ];
        foreach ($gallery as $gal) {
            GalleryItem::updateOrCreate(['title' => $gal['title']], $gal);
        }

        // 7. ALERTS
        $alerts = [
            [
                'message_en' => 'There will be a scheduled power outage in Sebeta Woreda on Sunday, from 8:00 AM to 4:00 PM.',
                'severity' => 'warning',
                'is_active' => true,
                'expires_at' => Carbon::now()->addDays(2),
            ]
        ];
        foreach ($alerts as $al) {
            EmergencyAlert::updateOrCreate(['message_en' => $al['message_en']], $al);
        }

        // 8. NEWS (Posts)
        $posts = [
            [
                'title_en' => 'Zone Achieves 95% Rural Electrification Target',
                'content_en' => 'The Oromo Special Area zone has successfully reached its milestone for rural electrification ahead of schedule.',
                'category' => 'news',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(2),
                'thumbnail_url' => 'https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?w=800&h=450&fit=crop',
            ],
            [
                'title_en' => 'New Urban Transportation Grid Announced',
                'content_en' => 'A comprehensive plan to connect all major woredas with a new rapid bus transit system has been unveiled today.',
                'category' => 'news',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(5),
                'thumbnail_url' => 'https://images.unsplash.com/photo-1580674251786-bb2120e24ec1?w=800&h=450&fit=crop',
            ],
            [
                'title_en' => 'Agricultural Output Increases by 20%',
                'content_en' => 'Thanks to the introduction of modern farming techniques, the zone has seen a significant boost in crop yields this season.',
                'category' => 'news',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(12),
                'thumbnail_url' => 'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=800&h=450&fit=crop',
            ],
        ];
        foreach ($posts as $pst) {
            Post::updateOrCreate(['title_en' => $pst['title_en']], $pst);
        }
    }
}
