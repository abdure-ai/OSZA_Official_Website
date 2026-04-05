<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AboutSection;

class AboutSectionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data to avoid duplicates
        AboutSection::truncate();

        $sections = [
            // Historical Background
            [
                'type' => 'history',
                'title_en' => 'Historical Background',
                'title_am' => 'የታሪክ ዳራ',
                'title_or' => 'Seenaa Duubee',
                'content_en' => 'The Oromo Special Zone was established as a unique administrative entity within the Amhara Regional State to safeguard the self-governance rights, cultural identity, and linguistic heritage of the Oromo people. Since its inception, the Zone has evolved from a nascent administrative unit into a vibrant corridor of economic activity and social progress.',
                'content_am' => 'የኦሮሞ ልዩ ዞን በአማራ ብሔራዊ ክልላዊ መንግሥት ውስጥ የኦሮሞን ሕዝብ የራስን እድል በራስ የመወሰን መብት፣ ባህላዊ ማንነት እና ቋንቋን ለመጠበቅ የተቋቋመ ልዩ አስተዳደራዊ መዋቅር ነው። ዞኑ ከተመሰረተበት ጊዜ ጀምሮ ከመጀመሪያ ደረጃ የአስተዳደር እርከን በመነሳት ወደ ንቁ የኢኮኖሚ እንቅስቃሴ እና ማህበራዊ እድገት መሸጋገሪያ መሆን ችሏል።',
                'content_or' => 'Godinni Addaa Oromiyaa Naannoo Amaaraa keessatti mirga ofiin of bulchuu, eenyummaa aadaa fi afaan ummata Oromoo eegsisuuf caasaa bulchiinsaa addaa ta\'ee hundeeffame. Erga hundeeffamee kaasee, godinni kun caasaa bulchiinsaa eegalamaa irraa gara giddu-gala diinagdee fi guddiina hawaasummaa utaalchoo ta\'etti guddachuu danda\'eera.',
                'icon' => 'history',
                'sort_order' => 0,
            ],

            // Our Mission
            [
                'type' => 'mission',
                'title_en' => 'Our Mission',
                'title_am' => 'ተልእኳችን',
                'title_or' => 'Ergaa Keenya',
                'content_en' => 'To ensure the provision of efficient, transparent, and equitable public services by fostering good governance, promoting inclusive economic growth, and upholding the rule of law to enhance the quality of life for all residents of the Zone.',
                'content_am' => 'መልካም አስተዳደርን በማስፈን፣ ሁሉን አቀፍ የኢኮኖሚ እድገትን በማሳደግ እና የህግ የበላይነትን በማክበር፣ ለዞኑ ነዋሪዎች ሁሉ ቀልጣፋ፣ ግልጽ እና ፍትሃዊ የህዝብ አገልግሎቶችን ማረጋገጥና የኑሮ ጥራታቸውን ማሻሻል ነው።',
                'content_or' => 'Bulchiinsa gaarii mirkaneessuu, guddina diinagdee hunda-galeessa ta\'e babal\'isuu fi heera mootummaa kabachiisuun, tajaajila mootummaa qulqullina qabu, iftoominaa fi haqa irratti hundaa\'e lammiilee hundaaf dhiyeessuun qulqullina jireenya jiraattota godinichaa fooyyessuu dha.',
                'icon' => 'mission',
                'sort_order' => 1,
            ],

            // Our Vision
            [
                'type' => 'vision',
                'title_en' => 'Our Vision',
                'title_am' => 'ራእያችን',
                'title_or' => 'Mul\'ata Keenya',
                'content_en' => 'To become a premier model of peace, prosperity, and sustainable development in Ethiopia, where cultural heritage and modern advancement thrive in harmony.',
                'content_am' => 'የባህል ቅርስ እና ዘመናዊ እድገት በተስማሚ ሁኔታ የሚለሙባት፣ በኢትዮጵያ የሰላም፣ የብልጽግና እና የዘላቂ ልማት ቀዳሚ ተምሳሌት መሆን።',
                'content_or' => 'Itiyoophiyaa keessatti hambaan aadaa fi guddinni ammayyaa wal-simatanii kan keessatti dagaagan, fakkeenya nagaa, badhaadhinaa fi misooma waaraa ta\'uu dha.',
                'icon' => 'vision',
                'sort_order' => 2,
            ],

            // Strategic Objectives
            [
                'type' => 'general',
                'title_en' => 'Strategic Objectives',
                'title_am' => 'ስትራቴጂካዊ ዓላማዎች',
                'title_or' => 'Galmawwan Toftaa',
                'content_en' => "• Accelerate socio-economic transformation through technology-driven agriculture.\n• Strengthen institutional capacity and digital governance.\n• Promote and preserve the Oromo language and heritage.\n• Ensure regional peace and security through community engagement.\n• Expand access to quality education and healthcare.",
                'content_am' => "• በቴክኖሎጂ የታገዘ ግብርና ማህበራዊና ኢኮኖሚያዊ ሽግግርን ማፋጠን።\n• ቀልጣፋ አገልግሎት ለመስጠት የተቋማትን አቅም እና ዲጂታል አሰራርን ማጠናከር።\n• የኦሮሞ ቋንቋን እና የታሪክ ቅርሶችን ማሳደግና መንከባከብ።\n• በማህበረሰብ ተሳትፎ ቀጣናዊ ሰላምና ጸጥታን ማረጋገጥ።\n• ጥራት ያለው ትምህርት እና ጤና ተደራሽነትን ማስፋፋት።",
                'content_or' => "• Qonna teeknooloojiin deeggaramee jijjiirama hawaas-diinagdee ariifachiisuu.\n• Tajaajila mootummaa ariifataa ta'eef dandeettii raawwachiisummaa cimsuu.\n• Afaan Oromoo fi hambaa seenaa guddisuu fi tiksuu.\n• Hirmaannaa hawaasaan nagaa fi tasgabbii naannichaa mirkaneessuu.\n• Tajaajila barnootaa fi fayyaa qaqqabummaa isaanii babal'isuu.",
                'icon' => 'general',
                'sort_order' => 3,
            ],
        ];

        foreach ($sections as $section) {
            AboutSection::create($section);
        }
    }
}
