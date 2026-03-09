<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Woreda;
use App\Models\Post;
use App\Models\Document;
use App\Models\Leadership;
use App\Models\EmergencyAlert;
use App\Models\HeroSlide;
use App\Models\AdminMessage;
use App\Models\Project;
use App\Models\Tender;
use App\Models\GalleryItem;
use App\Models\Vacancy;
use App\Models\ContactMessage;
use App\Models\Investment;
use App\Models\DirectoryRecord;
use App\Models\OfficeSetting;
use Illuminate\Support\Facades\Hash;

class ComprehensiveSampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@osza.gov.et'],
            [
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role' => 'super_admin',
            ]
        );

        // 2. Woredas
        $woredasData = [
            ['Akaki', 'akaki'],
            ['Berek', 'berek'],
            ['Gelano', 'gelano'],
            ['Mulo', 'mulo'],
            ['Sendafa', 'sendafa'],
            ['Sululta', 'sululta'],
            ['Sebeta', 'sebeta'],
            ['Welmera', 'welmera'],
        ];

        $woredaIds = [];
        foreach ($woredasData as [$name, $slug]) {
            $w = Woreda::updateOrCreate(
                ['slug' => $slug],
                [
                    'name_en' => $name,
                    'name_am' => $name . ' (Am)',
                    'name_or' => $name . ' (Or)',
                    'description_en' => "The $name Woreda is a thriving community within the Oromo Special Zone Administration.",
                    'is_active' => true,
                ]
            );
            $woredaIds[] = $w->id;
        }

        // 3. Hero Slides
        HeroSlide::truncate();
        HeroSlide::create([
            'title_en' => 'Leading with Integrity & Vision',
            'subtitle_en' => 'Oromo Special Zone Administration: Building a better future for all citizens.',
            'title_am' => 'በታማኝነት እና ራዕይ መምራት',
            'subtitle_am' => 'የኦሮሞ ልዩ ዞን አስተዳደር፡ ለሁሉም ዜጎች የተሻለ መጪ ጊዜን መገንባት።',
            'title_or' => 'Amanaamummaa fi Mul\'ataan Hooggansuu',
            'subtitle_or' => 'Bulchiinsa Naannoo Adda Oromiyaa: Hawaasa hundaaf madda jireenyaa qopheessuu.',
            'cta_text' => 'Learn More',
            'cta_url' => '/about',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        HeroSlide::create([
            'title_en' => 'Investing in Infrastructure',
            'subtitle_en' => 'Major road and water projects are underway across all Woredas.',
            'title_am' => 'በመሰረተ ልማት ላይ ኢንቨስት ማድረግ',
            'subtitle_am' => 'በሁሉም ወረዳዎች ዋና ዋና የመንገድ እና የውሃ ፕሮጀክቶች በመካሄድ ላይ ናቸው።',
            'title_or' => 'Izaaraa fi Maashiniiwwan Misoomaa',
            'subtitle_or' => 'Pirojektoonni karaa fi bishaanii gurguddoon aanaalee hunda keessatti hojjetamaa jiru.',
            'cta_text' => 'View Projects',
            'cta_url' => '/investment',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // 4. Admin Message (Chief Administrator)
        AdminMessage::truncate();
        AdminMessage::create([
            'name' => 'Hon. Abdisa Girma',
            'title_position' => 'Chief Administrator, Oromo Special Zone',
            'message_en' => 'Our administration is committed to transparency, accountability, and rapid development. We work tirelessly to ensure that every citizen in our zone has access to quality services and economic opportunities.',
            'is_active' => true,
        ]);

        // 5. News (Posts)
        Post::truncate();
        Post::create([
            'title_en' => 'OSZA Launches New Digital Portal',
            'content_en' => 'The Oromo Special Zone Administration has officially launched its new unified digital portal to streamline government services and provide real-time updates to the public.',
            'category' => 'news',
            'status' => 'published',
            'published_at' => now(),
        ]);
        Post::create([
            'title_en' => 'Agricultural Support Program 2026',
            'content_en' => 'Over 50,000 farmers across the zone will receive improved seeds and fertilizers as part of the new seasonal support initiative.',
            'category' => 'update',
            'status' => 'published',
            'published_at' => now()->subDays(2),
        ]);

        // 6. Documents
        Document::truncate();
        Document::create([
            'title_en' => 'Zone Development Strategy 2025-2030',
            'file_url' => '#',
            'file_type' => 'PDF',
            'category' => 'Strategy',
            'author' => 'Planning Bureau',
            'language' => 'English',
        ]);
        Document::create([
            'title_en' => 'Annual Budget Report FY 2025',
            'file_url' => '#',
            'file_type' => 'PDF',
            'category' => 'Financial',
            'author' => 'Finance Bureau',
            'language' => 'Amharic',
        ]);

        // 7. Leadership
        Leadership::truncate();
        Leadership::create([
            'name_en' => 'Abdisa Girma',
            'position_en' => 'Chief Administrator',
            'bio_en' => 'A seasonal leader with over 20 years of experience in public administration.',
            'rank_order' => 1,
        ]);
        Leadership::create([
            'name_en' => 'Fatuma Mohammed',
            'position_en' => 'Deputy Administrator',
            'bio_en' => 'Specializing in economic development and community engagement.',
            'rank_order' => 2,
        ]);

        // 8. Emergency Alerts
        EmergencyAlert::truncate();
        EmergencyAlert::create([
            'message_en' => 'Scheduled Water Maintenance in Sululta Woreda this Sunday.',
            'severity' => 'info',
            'is_active' => true,
            'expires_at' => now()->addDays(3),
        ]);

        // 9. Projects
        Project::truncate();
        Project::create([
            'title_en' => 'Sululta-Sendafa Road Expansion',
            'description_en' => 'Widening of the main highway to improve trade and transport safety.',
            'location_en' => 'Sululta & Sendafa',
            'status' => 'Ongoing',
            'budget' => 500000000.00,
            'progress' => 45,
            'contractor' => 'Ethiopian Construction Corp',
        ]);

        // 10. Tenders
        Tender::truncate();
        Tender::create([
            'title_en' => 'Supply of Office Equipment for Zone HQ',
            'description_en' => 'Bidding for the supply and installation of modern office furniture and computers.',
            'ref_number' => 'OSZA/T/2026/001',
            'deadline' => now()->addWeeks(2),
            'status' => 'active',
        ]);

        // 11. Gallery Items
        GalleryItem::truncate();
        foreach ($woredaIds as $index => $id) {
            GalleryItem::create([
                'title' => 'Landscape of ' . $woredasData[$index][0],
                'image_url' => 'https://images.unsplash.com/photo-1542332213-31f87348057f?q=80&w=600',
                'category' => 'Nature',
                'woreda_id' => $id,
                'sort_order' => $index,
            ]);
        }

        // 12. Vacancies
        Vacancy::truncate();
        Vacancy::create([
            'title_en' => 'Urban Planner (Senior)',
            'description_en' => 'Designing and coordinating urban development projects.',
            'requirements_en' => 'Masters in Urban Planning, 5+ years experience.',
            'department' => 'Urban Development Bureau',
            'vacancy_type' => 'Full-time',
            'deadline' => now()->addMonth(),
        ]);

        // 13. Contact Messages
        ContactMessage::truncate();
        ContactMessage::create([
            'name' => 'Demo User',
            'email' => 'user@example.com',
            'subject' => 'Question about services',
            'message' => 'Hello, I would like to know about the business license process.',
        ]);

        // 14. Investments
        Investment::truncate();
        Investment::create([
            'title_en' => 'Manufacturing Industrial Park',
            'description_en' => 'Incentives for textile and electronics manufacturing companies.',
            'category' => 'Industry',
            'location' => 'Sebeta',
            'budget' => '100M - 500M ETB',
            'contact_name' => 'Investment Bureau',
        ]);

        // 15. Directory
        DirectoryRecord::truncate();
        DirectoryRecord::create([
            'name_en' => 'Dr. Bekele Tolosa',
            'position_en' => 'Head of Health Bureau',
            'department_en' => 'Health',
            'phone' => '+251 911 223344',
            'email' => 'health@osza.gov.et',
        ]);

        // 16. Office Settings
        OfficeSetting::truncate();
        OfficeSetting::create([
            'phone' => '+251 33 111 2222',
            'email' => 'info@oromospecialzone.gov.et',
            'address' => 'Kemise, Amhara Region, Ethiopia',
            'working_hours' => 'Mon - Fri: 8:30 AM - 5:30 PM',
            'facebook_url' => 'https://facebook.com/osza',
            'twitter_url' => 'https://twitter.com/osza',
        ]);
    }
}
