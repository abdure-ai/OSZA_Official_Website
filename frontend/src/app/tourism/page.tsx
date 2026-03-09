import React from 'react';
import { fetchTourismSites, fetchTourismCategories } from '@/lib/api';
import TourismPageClient from '@/components/TourismPageClient';
import { Metadata } from 'next';

export const metadata: Metadata = {
    title: 'Visit Oromo Special Zone — Tourism & Heritage',
    description: 'Experience the breathtaking landscapes, ancient history, and vibrant culture of the heart of Ethiopia.',
};

export default async function TourismPage() {
    const [sites, categories] = await Promise.all([
        fetchTourismSites(),
        fetchTourismCategories()
    ]);

    return (
        <main className="min-h-screen bg-gray-50">
            {/* ═══════════════════════════════════════════ TOURISM HERO ══ */}
            <section className="relative bg-blue-900 text-white py-24 md:py-36 overflow-hidden">
                <div className="absolute inset-0 z-0">
                    <div className="absolute inset-0 bg-gradient-to-r from-blue-950/90 to-blue-900/40 z-10" />
                    <img
                        src="https://images.unsplash.com/photo-1543163521-1bf539c55dd2?q=80&w=1920&auto=format&fit=crop"
                        className="w-full h-full object-cover opacity-60"
                        alt="Tourism Hero"
                    />
                </div>

                <div className="max-w-[1440px] mx-auto px-4 relative z-10">
                    <div className="max-w-3xl">
                        <span className="inline-block px-4 py-1.5 bg-[#f5a623] text-blue-900 text-xs font-black uppercase tracking-widest rounded-full mb-6">
                            Discover Our Heritage
                        </span>
                        <h1 className="text-4xl md:text-7xl font-black mb-6 leading-tight antialiased">
                            Visit Oromo<br />
                            <span className="text-[#f5a623]">Special Zone</span>
                        </h1>
                        <p className="text-lg md:text-2xl text-gray-100 mb-10 leading-relaxed font-medium">
                            Experience the breathtaking landscapes, ancient history, and vibrant culture of the heart of Ethiopia. Your journey into the extraordinary begins here.
                        </p>
                        <div className="flex flex-wrap gap-4">
                            <a href="#destinations" className="bg-white text-blue-900 font-bold py-4 px-10 rounded-full hover:bg-[#f5a623] hover:text-white transition transform hover:scale-105 shadow-xl">
                                Explore Destinations
                            </a>
                            <a href="/contact" className="bg-transparent border-2 border-white/50 text-white font-bold py-4 px-10 rounded-full hover:bg-white/10 transition transform hover:scale-105 backdrop-blur-sm">
                                Plan Your Trip
                            </a>
                        </div>
                    </div>
                </div>

                <div className="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-gray-50 to-transparent" />
            </section>

            {/* Client Component for Filtering and Grid */}
            <TourismPageClient initialSites={sites} categories={categories} />

            {/* ═══════════════════════════════════════════ TRAVEL TIPS ══ */}
            <section className="py-20 bg-white border-t border-gray-100">
                <div className="max-w-[1440px] mx-auto px-4">
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-12">
                        <div className="flex items-start gap-5">
                            <div className="w-16 h-16 bg-[#f5a623]/10 rounded-2xl flex items-center justify-center flex-shrink-0 text-[#f5a623]">
                                <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h4 className="text-lg font-bold text-gray-900 mb-2">Best Time to Visit</h4>
                                <p className="text-gray-500 text-sm leading-relaxed">September to March offers the most pleasant climate for outdoor adventure and cultural festivals.</p>
                            </div>
                        </div>
                        <div className="flex items-start gap-5">
                            <div className="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center flex-shrink-0 text-[#1a56db]">
                                <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h4 className="text-lg font-bold text-gray-900 mb-2">Local Guides</h4>
                                <p className="text-gray-500 text-sm leading-relaxed">We recommend certified local guides to enrich your experience with deep historical context and hidden gems.</p>
                            </div>
                        </div>
                        <div className="flex items-start gap-5">
                            <div className="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center flex-shrink-0 text-green-600">
                                <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <h4 className="text-lg font-bold text-gray-900 mb-2">Travel Safety</h4>
                                <p className="text-gray-500 text-sm leading-relaxed">Oromo Special Zone is known for its hospitality and peace. We provide 24/7 travel support for all registered visitors.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    );
}
