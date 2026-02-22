import { notFound } from 'next/navigation';
import Link from 'next/link';
import { fetchWoredaBySlug, WoredaItem } from '@/lib/api';
import { FaHome, FaInfoCircle, FaConciergeBell, FaPhone, FaMapMarkerAlt, FaImages } from 'react-icons/fa';

const BACKEND_URL = process.env.NEXT_PUBLIC_BACKEND_URL || 'http://localhost:5000';

const NAV_LINKS = (slug: string) => [
    { href: `/woreda/${slug}`, label: 'Home', icon: FaHome },
    { href: `/woreda/${slug}/about`, label: 'About', icon: FaInfoCircle },
    { href: `/woreda/${slug}/services`, label: 'Services', icon: FaConciergeBell },
    { href: `/woreda/${slug}/gallery`, label: 'Gallery', icon: FaImages },
    { href: `/woreda/${slug}/contact`, label: 'Contact', icon: FaPhone },
];

export default async function WoredaLayout({
    children,
    params,
}: {
    children: React.ReactNode;
    params: { slug: string };
}) {
    const woreda: WoredaItem | null = await fetchWoredaBySlug(params.slug);
    if (!woreda) notFound();

    return (
        <div className="min-h-screen bg-gray-50 flex flex-col">
            {/* Woreda Header */}
            <header className="bg-gradient-to-r from-green-800 to-green-600 text-white shadow-md">
                {/* Zone breadcrumb */}
                <div className="bg-green-900/60">
                    <div className="container mx-auto px-4 py-1.5 text-xs text-green-200 flex items-center gap-2">
                        <Link href="/" className="hover:text-white transition">Oromo Special Zone</Link>
                        <span>›</span>
                        <span>{woreda.name_en} Woreda</span>
                    </div>
                </div>

                {/* Identity bar */}
                <div className="container mx-auto px-4 py-5 flex items-center gap-5">
                    {woreda.logo_url ? (
                        // eslint-disable-next-line @next/next/no-img-element
                        <img
                            src={`${BACKEND_URL}${woreda.logo_url}`}
                            alt={`${woreda.name_en} logo`}
                            className="w-14 h-14 rounded-full object-cover border-2 border-white/30"
                        />
                    ) : (
                        <div className="w-14 h-14 rounded-full bg-white/20 flex items-center justify-center text-2xl font-bold border-2 border-white/30">
                            {woreda.name_en.charAt(0)}
                        </div>
                    )}
                    <div>
                        <h1 className="text-2xl font-bold leading-tight">{woreda.name_en} Woreda</h1>
                        <div className="flex items-center gap-1 text-green-200 text-sm mt-0.5">
                            <FaMapMarkerAlt size={11} />
                            <span>
                                {woreda.capital_en && `${woreda.capital_en} · `}
                                Oromo Special Zone Administration
                            </span>
                        </div>
                    </div>
                </div>

                {/* Navigation */}
                <nav className="border-t border-green-700/50">
                    <div className="container mx-auto px-4 flex gap-0">
                        {NAV_LINKS(params.slug).map(({ href, label, icon: Icon }) => (
                            <Link
                                key={href}
                                href={href}
                                className="flex items-center gap-2 px-5 py-3 text-sm font-medium text-green-100 hover:bg-green-700/50 hover:text-white transition border-b-2 border-transparent hover:border-white"
                            >
                                <Icon size={13} />
                                {label}
                            </Link>
                        ))}
                    </div>
                </nav>
            </header>

            {/* Hero / Banner Area */}
            <div className="relative h-64 md:h-80 overflow-hidden">
                {woreda.banner_url ? (
                    // eslint-disable-next-line @next/next/no-img-element
                    <img
                        src={`${BACKEND_URL}${woreda.banner_url}`}
                        alt={`${woreda.name_en} banner`}
                        className="w-full h-full object-cover"
                    />
                ) : (
                    <div className="w-full h-full bg-gradient-to-br from-green-800 to-green-500" />
                )}
                <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent" />
                <div className="absolute bottom-6 left-0 container mx-auto px-4 z-10">
                    <h2 className="text-3xl md:text-4xl font-bold text-white drop-shadow-lg">
                        {woreda.name_en}
                    </h2>
                    {woreda.capital_en && (
                        <p className="text-green-200 mt-1 flex items-center gap-1">
                            <FaMapMarkerAlt size={12} />
                            {woreda.capital_en}
                        </p>
                    )}
                </div>
            </div>

            {/* Page Content */}
            <main className="flex-1">
                {children}
            </main>

            {/* Woreda Footer */}
            <footer className="bg-green-900 text-green-200 py-8 mt-auto">
                <div className="container mx-auto px-4 text-center text-sm space-y-1">
                    <p className="font-semibold text-white">{woreda.name_en} Woreda Administration</p>
                    <p>Part of the <Link href="/" className="underline hover:text-white">Oromo Special Zone Administration</Link></p>
                    {woreda.contact_phone && <p>📞 {woreda.contact_phone}</p>}
                </div>
            </footer>
        </div>
    );
}
