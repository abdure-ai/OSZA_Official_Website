import { fetchWoredaBySlug } from '@/lib/api';
import { notFound } from 'next/navigation';
import { FaPhone, FaEnvelope, FaSeedling, FaHeartbeat, FaGraduationCap, FaGavel, FaMoneyBillWave, FaRoad } from 'react-icons/fa';

export async function generateMetadata({ params }: { params: { slug: string } }) {
    const woreda = await fetchWoredaBySlug(params.slug);
    return { title: `Services — ${woreda?.name_en || 'Woreda'} | Oromo Special Zone` };
}

const OFFICES = [
    {
        icon: FaMoneyBillWave,
        name: 'Finance & Economic Development Office',
        color: 'bg-blue-50 border-blue-100',
        iconColor: 'text-blue-600',
        description: 'Budget planning, revenue collection, and economic growth initiatives.',
        hours: 'Mon–Thu 8:30–5:30 · Fri 8:30–11:30',
    },
    {
        icon: FaSeedling,
        name: 'Agriculture & Natural Resources Office',
        color: 'bg-green-50 border-green-100',
        iconColor: 'text-green-600',
        description: 'Farm input distribution, irrigation, soil conservation, and livestock support.',
        hours: 'Mon–Thu 8:30–5:30 · Fri 8:30–11:30',
    },
    {
        icon: FaHeartbeat,
        name: 'Health Office',
        color: 'bg-red-50 border-red-100',
        iconColor: 'text-red-600',
        description: 'Primary health care, vaccination campaigns, and health centre coordination.',
        hours: 'Mon–Sun 24hrs (emergencies)',
    },
    {
        icon: FaGraduationCap,
        name: 'Education Office',
        color: 'bg-amber-50 border-amber-100',
        iconColor: 'text-amber-600',
        description: 'Primary and secondary school oversight, teacher training, and literacy programs.',
        hours: 'Mon–Thu 8:30–5:30 · Fri 8:30–11:30',
    },
    {
        icon: FaGavel,
        name: 'Justice & Security Office',
        color: 'bg-purple-50 border-purple-100',
        iconColor: 'text-purple-600',
        description: 'Rule of law, civil registration, court liaison, and community safety programs.',
        hours: 'Mon–Thu 8:30–5:30 · Fri 8:30–11:30',
    },
    {
        icon: FaRoad,
        name: 'Infrastructure & Works Office',
        color: 'bg-gray-50 border-gray-200',
        iconColor: 'text-gray-600',
        description: 'Road maintenance, construction oversight, and public utilities.',
        hours: 'Mon–Thu 8:30–5:30 · Fri 8:30–11:30',
    },
];

export default async function WoredaServices({ params }: { params: { slug: string } }) {
    const woreda = await fetchWoredaBySlug(params.slug);
    if (!woreda) notFound();

    return (
        <div className="container mx-auto px-4 py-10 max-w-5xl">
            <div className="border-b border-gray-200 pb-6 mb-8">
                <h2 className="text-3xl font-bold text-gray-900">Government Services</h2>
                <p className="text-gray-500 mt-2">Official offices and public services available in the woreda</p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
                {OFFICES.map(({ icon: Icon, name, color, iconColor, description, hours }) => (
                    <div key={name} className={`rounded-xl border ${color} bg-white p-5 hover:shadow-md transition`}>
                        <div className="flex items-start gap-4">
                            <div className={`w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0 ${color}`}>
                                <Icon className={`text-xl ${iconColor}`} />
                            </div>
                            <div className="flex-1">
                                <h3 className="font-bold text-gray-900 text-sm leading-tight">{name}</h3>
                                <p className="text-gray-500 text-sm mt-1 leading-relaxed">{description}</p>
                                <p className="text-xs text-gray-400 mt-2">🕐 {hours}</p>
                            </div>
                        </div>
                    </div>
                ))}
            </div>

            {/* Contact prompt */}
            <div className="mt-10 bg-green-50 border border-green-100 rounded-xl p-6 flex flex-col md:flex-row items-center justify-between gap-4">
                <div>
                    <h3 className="font-bold text-green-900">Need to contact a specific office?</h3>
                    <p className="text-green-700 text-sm mt-0.5">Visit our contact page for phone numbers and email addresses.</p>
                </div>
                <a
                    href={`/woreda/${params.slug}/contact`}
                    className="bg-green-700 text-white px-6 py-2.5 rounded-full font-medium hover:bg-green-800 transition whitespace-nowrap flex items-center gap-2"
                >
                    <FaPhone size={13} /> Contact Us
                </a>
            </div>
        </div>
    );
}
