import LeadershipCard from '@/components/about/LeadershipCard';

const leaders = [
    {
        name: "Dr. Ahmedin Mohammed",
        role: "Zone Main Administrator",
        bio: "Dr. Ahmedin has served as the Zone Administrator since 2022. He holds a PhD in Public Administration and has over 15 years of experience in regional governance and development planning.",
    },
    {
        name: "Ms. Fatuma Ali",
        role: "Deputy Administrator",
        bio: "Ms. Fatuma oversees the social affairs division. She is a champion for women's empowerment and education reform within the zone.",
    },
    {
        name: "Mr. Bekele Tadesse",
        role: "Head of Agriculture Bureau",
        bio: "With a background in Agronomy, Mr. Bekele leads the zone's initiatives in modernizing farming practices and ensuring food security.",
    },
    {
        name: "Mr. Oumer Hassan",
        role: "Head of Peace & Security",
        bio: "Mr. Oumer is dedicated to maintaining peace and stability in the region, fostering dialogue between communities.",
    }
];

export const metadata = {
    title: 'Leadership | Oromo Special Zone',
    description: 'Meet the administration officials of Oromo Special Zone',
};

export default function LeadershipPage() {
    return (
        <div className="bg-gray-50 min-h-screen pb-16">
            <div className="bg-white border-b py-12 mb-12">
                <div className="container mx-auto px-4 text-center">
                    <h1 className="text-3xl md:text-4xl font-bold text-gray-900 mb-3">Administration Leadership</h1>
                    <p className="text-gray-600 max-w-2xl mx-auto">
                        Meet the dedicated team working to serve the community and drive the development of the Oromo Special Zone.
                    </p>
                </div>
            </div>

            <div className="container mx-auto px-4">
                {/* Administrator Highlight */}
                <div className="flex justify-center mb-16">
                    <div className="max-w-md w-full transform md:scale-110">
                        <LeadershipCard {...leaders[0]} />
                    </div>
                </div>

                {/* Other Leaders Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    {leaders.slice(1).map((leader, index) => (
                        <LeadershipCard key={index} {...leader} />
                    ))}
                </div>
            </div>
        </div>
    );
}
