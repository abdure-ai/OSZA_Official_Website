import React from 'react';
import { fetchTourismSiteBySlug, getFileUrl } from '@/lib/api';
import { notFound } from 'next/navigation';
import { FaMapMarkerAlt, FaClock, FaCheckCircle, FaArrowLeft, FaCompass } from 'react-icons/fa';
import Link from 'next/link';

export async function generateMetadata({ params }: { params: { slug: string } }) {
    const data = await fetchTourismSiteBySlug(params.slug);
    if (!data) return { title: 'Site Not Found' };
    return {
        title: `${data.site.name_en} — Tourism & Heritage`,
        description: data.site.description_en.substring(0, 160),
    };
}

export default async function TouristSiteDetail({ params }: { params: { slug: string } }) {
    const data = await fetchTourismSiteBySlug(params.slug);

    if (!data) {
        notFound();
    }

    const { site, related } = data;

    return (
        <main className="min-h-screen bg-white">
            {/* ═══════════════════════════════════════════ DESTINATION HERO ══ */}
            <section className="relative h-[70vh] md:h-[85vh] overflow-hidden flex items-end pb-20">
                <div className="absolute inset-0 z-0">
                    <div className="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent z-10" />
                    {site.cover_image_url ? (
                        <img
                            src={getFileUrl(site.cover_image_url)}
                            className="w-full h-full object-cover scale-105"
                            alt={site.name_en}
                        />
                    ) : (
                        <div className="w-full h-full bg-blue-900" />
                    )}
                </div>

                <div className="max-w-[1440px] mx-auto px-4 relative z-20 w-full text-white">
                    <nav className="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-gray-300 mb-6">
                        <Link href="/tourism" className="hover:text-[#f5a623] transition">Tourism</Link>
                        <span>/</span>
                        <span className="text-[#f5a623]">{site.category || 'Explore'}</span>
                    </nav>
                    <h1 className="text-5xl md:text-8xl font-black mb-4 leading-none antialiased">
                        {site.name_en}
                    </h1>
                    <div className="flex flex-wrap items-center gap-6">
                        <div className="flex items-center gap-2 text-[#f5a623] font-bold">
                            <FaMapMarkerAlt className="w-5 h-5" />
                            <span>{site.woreda?.name_en || 'Oromo Special Zone'}</span>
                        </div>
                        <div className="w-px h-6 bg-white/20 hidden md:block" />
                        <div className="flex items-center gap-4">
                            <span className="text-sm font-medium border border-white/30 px-3 py-1 rounded-full backdrop-blur-sm flex items-center gap-2">
                                <FaClock className="text-xs" /> Open 24/7
                            </span>
                            <span className="text-sm font-medium border border-white/30 px-3 py-1 rounded-full backdrop-blur-sm flex items-center gap-2">
                                <FaCheckCircle className="text-xs" /> Entry: Free
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            {/* ═══════════════════════════════════════════ STORYTELLING SECTION ══ */}
            <section className="py-24 bg-white relative">
                {/* Floating Badge */}
                <div className="absolute -top-12 right-12 hidden lg:flex flex-col items-center justify-center w-24 h-24 bg-[#f5a623] rounded-full shadow-2xl animate-bounce z-30">
                    <span className="text-blue-900 text-[10px] font-black uppercase text-center leading-tight">Heritage<br />Site</span>
                </div>

                <div className="max-w-[1440px] mx-auto px-4">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-20 items-start">
                        <div className="space-y-8">
                            <div className="inline-block px-5 py-2 bg-blue-50 text-[#1a56db] text-xs font-black uppercase tracking-widest rounded-full">
                                Introduction
                            </div>
                            <h2 className="text-3xl md:text-5xl font-black text-gray-900 leading-tight">
                                Experience the Essence of {site.name_en}
                            </h2>
                            <div
                                className="prose prose-xl text-gray-600 font-medium leading-relaxed max-w-none antialiased"
                                dangerouslySetInnerHTML={{ __html: site.description_en.replace(/\n/g, '<br />') }}
                            />

                            <div className="bg-gray-50 rounded-3xl p-8 border border-gray-100 mt-12">
                                <h4 className="text-lg font-black text-gray-900 mb-4">Location Details</h4>
                                <ul className="space-y-4 text-sm text-gray-600 font-bold">
                                    <li className="flex items-center gap-3">
                                        <span className="w-8 h-8 rounded-full bg-white flex items-center justify-center text-[#f5a623] shadow-sm">📍</span>
                                        <span>{site.location_name_en || `Located in ${site.woreda?.name_en || 'Special Zone'}`}</span>
                                    </li>
                                    <li className="flex items-center gap-3">
                                        <span className="w-8 h-8 rounded-full bg-white flex items-center justify-center text-blue-600 shadow-sm">🗺️</span>
                                        <span>Coordinates: {site.latitude || 'N/A'}, {site.longitude || 'N/A'}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {/* Interactive Gallery Grid */}
                        <div className="grid grid-cols-2 gap-4 sticky top-24">
                            {site.gallery_urls && site.gallery_urls.length > 0 ? (
                                site.gallery_urls.slice(0, 4).map((url, idx) => (
                                    <div key={idx} className={`relative overflow-hidden group rounded-3xl ${idx === 0 ? 'col-span-2 h-96' : 'h-64'} shadow-lg`}>
                                        <img
                                            src={getFileUrl(url)}
                                            className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 cursor-zoom-in"
                                            alt={`${site.name_en} gallery ${idx + 1}`}
                                        />
                                        <div className="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-all pointer-events-none" />
                                    </div>
                                ))
                            ) : (
                                <div className="col-span-2 rounded-3xl h-[500px] bg-gray-100 flex flex-col items-center justify-center border-4 border-dashed border-gray-200">
                                    <FaCompass className="w-20 h-20 text-gray-300 mb-4" />
                                    <p className="text-gray-400 font-bold uppercase tracking-widest text-xs">Full Gallery Coming Soon</p>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </section>

            {/* ═══════════════════════════════════════════ THE FULL GALLERY ══ */}
            {site.gallery_urls && site.gallery_urls.length > 4 && (
                <section className="py-24 bg-gray-50 overflow-hidden">
                    <div className="max-w-[1440px] mx-auto px-4 mb-12 flex justify-between items-end">
                        <div>
                            <h3 className="text-3xl font-black text-gray-900 border-l-8 border-blue-600 pl-4 uppercase tracking-tighter italic">The Immersion</h3>
                            <p className="text-gray-500 font-bold ml-6 mt-2">Extended visual exploration of the site</p>
                        </div>
                    </div>
                    <div className="flex gap-4 overflow-x-auto pb-10 px-4 no-scrollbar -mx-4">
                        {site.gallery_urls.slice(4).map((url, idx) => (
                            <div key={idx} className="flex-shrink-0 w-[400px] h-[500px] rounded-[2.5rem] overflow-hidden shadow-2xl transition-transform hover:scale-[0.98]">
                                <img src={getFileUrl(url)} className="w-full h-full object-cover" alt="Gallery item" />
                            </div>
                        ))}
                    </div>
                </section>
            )}

            {/* ═══════════════════════════════════════════ RELATED DESTINATIONS ══ */}
            {related && related.length > 0 && (
                <section className="py-24 bg-white">
                    <div className="max-w-[1440px] mx-auto px-4">
                        <h3 className="text-3xl font-black text-gray-900 mb-12 flex items-center gap-4">
                            Explore Nearby
                            <div className="h-1 flex-grow bg-gray-100 rounded-full" />
                        </h3>
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                            {related.map(rel => (
                                <Link key={rel.id} href={`/tourism/${rel.slug}`} className="group block">
                                    <div className="h-72 rounded-[2rem] overflow-hidden mb-6 shadow-md shadow-blue-900/5">
                                        {rel.cover_image_url ? (
                                            <img
                                                src={getFileUrl(rel.cover_image_url)}
                                                className="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                                alt={rel.name_en}
                                            />
                                        ) : (
                                            <div className="w-full h-full bg-blue-50" />
                                        )}
                                    </div>
                                    <h4 className="text-xl font-black text-gray-900 group-hover:text-blue-600 transition tracking-tight">{rel.name_en}</h4>
                                    <p className="text-gray-400 text-xs font-bold uppercase tracking-widest mt-1">{rel.category || 'Explore'}</p>
                                </Link>
                            ))}
                        </div>
                    </div>
                </section>
            )}

            {/* Back button fixed */}
            <Link
                href="/tourism"
                className="fixed bottom-8 left-8 z-50 bg-blue-900 text-white w-14 h-14 rounded-full flex items-center justify-center shadow-2xl hover:bg-[#f5a623] transition-all transform hover:scale-110 group"
            >
                <FaArrowLeft className="group-hover:-translate-x-1 transition-transform" />
            </Link>
        </main>
    );
}
