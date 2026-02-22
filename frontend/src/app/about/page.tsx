import Image from 'next/image';

export const metadata = {
    title: 'About Us | Oromo Special Zone',
    description: 'History, Mission, and Vision of Oromo Special Zone Administration',
};

export default function AboutPage() {
    return (
        <div className="bg-white min-h-screen pb-12">
            {/* Page Header */}
            <div className="bg-primary text-white py-16 text-center">
                <h1 className="text-4xl font-bold mb-2">About Oromo Special Zone</h1>
                <p className="text-blue-100">History, Identity, and Future Vision</p>
            </div>

            <div className="container mx-auto px-4 py-12">

                {/* Main Content Grid */}
                <div className="grid grid-cols-1 md:grid-cols-3 gap-12">

                    {/* Main Text Content */}
                    <div className="md:col-span-2 space-y-12">

                        {/* History Section */}
                        <section>
                            <h2 className="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Our History</h2>
                            <div className="prose lg:prose-xl text-gray-600">
                                <p>
                                    The Oromo Special Zone (Afaan Oromo: Godina Addaa Oromoo) is a zone in the Amhara Region of Ethiopia.
                                    It was established to ensure the self-administration rights of the Oromo people living in the region.
                                </p>
                                <p>
                                    Kemise is the administrative center of the Zone. The zone is bordered on the east by the Afar Region,
                                    on the south by North Shewa Zone, on the west by East Gojjam, and on the north by South Wollo.
                                </p>
                                <p>
                                    Historically, this area has been a hub of cultural exchange and agricultural productivity.
                                    The administration has worked tirelessly to promote peace, development, and social justice since its inception.
                                </p>
                            </div>
                        </section>

                        {/* Mission & Vision */}
                        <section className="grid md:grid-cols-2 gap-6">
                            <div className="bg-blue-50 p-6 rounded-xl border border-blue-100">
                                <h3 className="text-xl font-bold text-primary mb-3">Our Vision</h3>
                                <p className="text-gray-700">
                                    To see a prosperous, democratic, and peaceful Oromo Special Zone where the rights of citizens are respected and good governance prevails by 2030.
                                </p>
                            </div>
                            <div className="bg-green-50 p-6 rounded-xl border border-green-100">
                                <h3 className="text-xl font-bold text-green-700 mb-3">Our Mission</h3>
                                <p className="text-gray-700">
                                    To ensure equitable development through efficient service delivery, promoting investment, and ensuring the rule of law while preserving the cultural identity of the people.
                                </p>
                            </div>
                        </section>

                        {/* Demographics */}
                        <section>
                            <h2 className="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Demographics</h2>
                            <p className="text-gray-600 mb-4">
                                Based on the latest census projections, the Zone has a total population of approximately [Number],
                                of whom [Number] are men and [Number] are women. The majority of the inhabitants practice Islam and Orthodox Christianity.
                            </p>
                            <ul className="list-disc pl-5 text-gray-600 space-y-2">
                                <li><strong>Capital:</strong> Kemise</li>
                                <li><strong>Area:</strong> [Area Sq Km]</li>
                                <li><strong>Woredas:</strong> Dawa Chefa, Artuma Fursi, Jile Tumuga, etc.</li>
                            </ul>
                        </section>

                    </div>

                    {/* Sidebar / Quick Facts */}
                    <aside className="space-y-8">
                        <div className="bg-gray-50 p-6 rounded-lg shadow-sm sticky top-24">
                            <h3 className="font-bold text-lg mb-4 text-gray-900">Quick Facts</h3>
                            <ul className="space-y-3 text-sm text-gray-600">
                                <li className="flex justify-between border-b pb-2">
                                    <span>Region:</span>
                                    <span className="font-semibold">Amhara</span>
                                </li>
                                <li className="flex justify-between border-b pb-2">
                                    <span>Zone Status:</span>
                                    <span className="font-semibold">Special Zone</span>
                                </li>
                                <li className="flex justify-between border-b pb-2">
                                    <span>Established:</span>
                                    <span className="font-semibold">1994</span>
                                </li>
                                <li className="flex justify-between border-b pb-2">
                                    <span>Major Economic Activity:</span>
                                    <span className="font-semibold">Agriculture</span>
                                </li>
                            </ul>
                        </div>
                    </aside>

                </div>
            </div>
        </div>
    );
}
