import { fetchWoredaBySlug, fetchGallery } from '@/lib/api';
import { notFound } from 'next/navigation';
import Link from 'next/link';
import {
    FaUsers, FaRulerCombined, FaCalendarAlt, FaMapMarkerAlt,
    FaPhone, FaEnvelope, FaInfoCircle, FaConciergeBell, FaUserTie, FaImages,
} from 'react-icons/fa';

const BACKEND_URL = process.env.NEXT_PUBLIC_BACKEND_URL || 'http://localhost:5000';

export async function generateMetadata({ params }: { params: { slug: string } }) {
    const woreda = await fetchWoredaBySlug(params.slug);
    if (!woreda) return {};
    return {
        title: `${woreda.name_en} Woreda | Oromo Special Zone`,
        description: woreda.description_en || `Official website of ${woreda.name_en} Woreda Administration`,
    };
}

export default async function WoredaHome({ params }: { params: { slug: string } }) {
    const woreda = await fetchWoredaBySlug(params.slug);
    if (!woreda) notFound();

    // Fetch up to 6 recent gallery photos for this woreda
    const galleryItems = woreda.id ? await fetchGallery(woreda.id) : [];
    const recentPhotos = galleryItems.slice(0, 6);

    const stats = [
        { icon: FaUsers, label: 'Population', value: woreda.population || '—' },
        { icon: FaRulerCombined, label: 'Area (km²)', value: woreda.area_km2 || '—' },
        { icon: FaCalendarAlt, label: 'Est.', value: woreda.established_year || '—' },
        { icon: FaMapMarkerAlt, label: 'Capital', value: woreda.capital_en || '—' },
    ];

    const quickLinks = [
        { href: `/woreda/${params.slug}/about`, icon: FaInfoCircle, label: 'About the Woreda', color: 'bg-blue-50 text-blue-700 border-blue-100' },
        { href: `/woreda/${params.slug}/services`, icon: FaConciergeBell, label: 'Services & Offices', color: 'bg-green-50 text-green-700 border-green-100' },
        { href: `/woreda/${params.slug}/contact`, icon: FaPhone, label: 'Contact & Directory', color: 'bg-amber-50 text-amber-700 border-amber-100' },
    ];

    return (
        <div className="container mx-auto px-4 py-10 space-y-10">
            {/* Welcome msg if Home */}
            <div className="mb-2">
                <h2 className="text-2xl font-bold text-gray-800">Welcome to the Official Portal</h2>
                <p className="text-gray-500">Access information and services provided by {woreda.name_en} Woreda.</p>
            </div>

            {/* Key Stats */}
            <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                {stats.map(({ icon: Icon, label, value }) => (
                    <div key={label} className="bg-white rounded-xl border border-gray-100 shadow-sm p-5 text-center">
                        <Icon className="text-green-600 text-2xl mx-auto mb-2" />
                        <p className="text-xl font-bold text-gray-900">{value}</p>
                        <p className="text-xs text-gray-500 mt-0.5">{label}</p>
                    </div>
                ))}
            </div>

            <div className="grid md:grid-cols-3 gap-8">
                {/* Description */}
                <div className="md:col-span-2 space-y-8">
                    {woreda.description_en && (
                        <section className="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                            <h3 className="text-lg font-bold text-gray-800 mb-3">About {woreda.name_en}</h3>
                            <p className="text-gray-600 leading-relaxed">{woreda.description_en}</p>
                            <Link
                                href={`/woreda/${params.slug}/about`}
                                className="inline-block mt-4 text-green-700 font-medium hover:underline text-sm"
                            >
                                Read More →
                            </Link>
                        </section>
                    )}

                    {/* Quick Links */}
                    <section>
                        <h3 className="text-lg font-bold text-gray-800 mb-4">Quick Access</h3>
                        <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            {quickLinks.map(({ href, icon: Icon, label, color }) => (
                                <Link
                                    key={href}
                                    href={href}
                                    className={`flex flex-col items-center text-center p-5 rounded-xl border ${color} hover:shadow-md transition`}
                                >
                                    <Icon className="text-3xl mb-2" />
                                    <span className="font-semibold text-sm">{label}</span>
                                </Link>
                            ))}
                        </div>
                    </section>
                </div>

                {/* Administrator Card */}
                <div className="space-y-4">
                    {woreda.administrator_name && (
                        <div className="bg-white rounded-3xl border border-gray-100 shadow-xl p-8 text-center group">
                            <div className="flex flex-col items-center">
                                {woreda.administrator_photo_url ? (
                                    // eslint-disable-next-line @next/next/no-img-element
                                    <img
                                        src={`${BACKEND_URL}${woreda.administrator_photo_url}`}
                                        alt={woreda.administrator_name}
                                        className="w-32 h-40 md:w-40 md:h-52 rounded-2xl object-cover border-4 border-white shadow-xl mb-6 group-hover:scale-105 transition duration-300"
                                    />
                                ) : (
                                    <div className="w-32 h-40 md:w-40 md:h-52 rounded-2xl bg-green-50 flex items-center justify-center mb-6 border-4 border-dashed border-green-200">
                                        <FaUserTie className="text-green-600 text-5xl" />
                                    </div>
                                )}

                                <h4 className="text-xl font-extrabold text-gray-900 mb-1">{woreda.administrator_name}</h4>
                                <p className="text-sm font-bold text-green-700 uppercase tracking-widest mb-6">{woreda.administrator_title || 'Woreda Administrator'}</p>

                                <div className="relative">
                                    <span className="absolute -top-4 -left-2 text-4xl text-green-100 font-serif">&ldquo;</span>
                                    <p className="text-gray-600 italic leading-relaxed text-sm px-4">
                                        Committed to serving the people of {woreda.name_en} with transparency, integrity, and dedication to our community&apos;s progress.
                                    </p>
                                    <span className="absolute -bottom-6 -right-2 text-4xl text-green-100 font-serif">&rdquo;</span>
                                </div>
                            </div>
                        </div>
                    )}

                    {/* Contact Sidebar */}
                    <div className="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-3">
                        <h4 className="font-bold text-gray-800 text-sm">Contact</h4>
                        {woreda.contact_phone && (
                            <div className="flex items-center gap-2 text-sm text-gray-700">
                                <FaPhone className="text-green-600 flex-shrink-0" />
                                <a href={`tel:${woreda.contact_phone}`} className="hover:underline">{woreda.contact_phone}</a>
                            </div>
                        )}
                        {woreda.contact_email && (
                            <div className="flex items-center gap-2 text-sm text-gray-700">
                                <FaEnvelope className="text-green-600 flex-shrink-0" />
                                <a href={`mailto:${woreda.contact_email}`} className="hover:underline truncate">{woreda.contact_email}</a>
                            </div>
                        )}
                        {woreda.address_en && (
                            <div className="flex items-start gap-2 text-sm text-gray-700">
                                <FaMapMarkerAlt className="text-green-600 flex-shrink-0 mt-0.5" />
                                <span>{woreda.address_en}</span>
                            </div>
                        )}
                    </div>
                </div>
            </div>

            {/* Photo Gallery Section */}
            {recentPhotos.length > 0 && (
                <section>
                    <div className="flex items-center justify-between mb-4">
                        <div className="flex items-center gap-2">
                            <FaImages className="text-green-600" />
                            <h3 className="text-lg font-bold text-gray-800">Photo Gallery</h3>
                        </div>
                        <Link
                            href={`/woreda/${params.slug}/gallery`}
                            className="text-sm text-green-700 font-medium hover:underline"
                        >
                            View All →
                        </Link>
                    </div>
                    <div className="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        {recentPhotos.map(photo => (
                            <div key={photo.id} className="relative h-36 rounded-xl overflow-hidden group">
                                {/* eslint-disable-next-line @next/next/no-img-element */}
                                <img
                                    src={`${BACKEND_URL}${photo.image_url}`}
                                    alt={photo.title}
                                    className="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                />
                                <div className="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 to-transparent p-2">
                                    <p className="text-white text-xs font-medium truncate">{photo.title}</p>
                                </div>
                            </div>
                        ))}
                    </div>
                </section>
            )}
        </div>
    );
}
