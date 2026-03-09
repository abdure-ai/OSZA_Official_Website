'use client';

import React, { useState } from 'react';
import { TouristSite, getFileUrl } from '@/lib/api';
import { FaMapMarkerAlt, FaSearch, FaArrowRight } from 'react-icons/fa';
import Link from 'next/link';

interface TourismPageClientProps {
    initialSites: TouristSite[];
    categories: string[];
}

export default function TourismPageClient({ initialSites, categories }: TourismPageClientProps) {
    const [activeCategory, setActiveCategory] = useState('All');
    const [searchQuery, setSearchQuery] = useState('');

    const filteredSites = initialSites.filter(site => {
        const matchesCategory = activeCategory === 'All' || site.category === activeCategory;
        const matchesSearch = site.name_en.toLowerCase().includes(searchQuery.toLowerCase());
        return matchesCategory && matchesSearch;
    });

    return (
        <section id="destinations" className="py-20 bg-gray-50">
            <div className="max-w-[1440px] mx-auto px-4">
                {/* Filter Bar */}
                <div className="flex flex-col md:flex-row justify-between items-center gap-8 mb-16">
                    <div>
                        <h2 className="text-3xl md:text-4xl font-black text-gray-900 mb-3">Major Destinations</h2>
                        <div className="h-1.5 w-24 bg-[#f5a623] rounded-full" />
                    </div>

                    <div className="flex flex-col md:flex-row items-center gap-6 w-full md:w-auto">
                        {/* Search */}
                        <div className="relative w-full md:w-80">
                            <FaSearch className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" />
                            <input
                                type="text"
                                placeholder="Search destinations..."
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                                className="w-full pl-12 pr-4 py-3 bg-white border border-gray-100 rounded-full focus:ring-2 focus:ring-blue-500 outline-none shadow-sm transition-all"
                            />
                        </div>

                        {/* Categories */}
                        <div className="flex flex-wrap justify-center gap-2">
                            <button
                                onClick={() => setActiveCategory('All')}
                                className={`px-6 py-2 rounded-full text-sm font-bold transition-all ${activeCategory === 'All'
                                        ? 'bg-blue-900 text-white shadow-lg shadow-blue-200'
                                        : 'bg-white text-gray-500 hover:bg-gray-100 border border-gray-100'
                                    }`}
                            >
                                All
                            </button>
                            {categories.map(cat => (
                                <button
                                    key={cat}
                                    onClick={() => setActiveCategory(cat)}
                                    className={`px-6 py-2 rounded-full text-sm font-bold transition-all ${activeCategory === cat
                                            ? 'bg-blue-900 text-white shadow-lg shadow-blue-200'
                                            : 'bg-white text-gray-500 hover:bg-gray-100 border border-gray-100'
                                        }`}
                                >
                                    {cat}
                                </button>
                            ))}
                        </div>
                    </div>
                </div>

                {/* Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    {filteredSites.map(site => (
                        <Link
                            key={site.id}
                            href={`/tourism/${site.slug}`}
                            className="group flex flex-col bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100"
                        >
                            <div className="h-80 relative overflow-hidden">
                                {site.cover_image_url ? (
                                    <img
                                        src={getFileUrl(site.cover_image_url)}
                                        className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                        alt={site.name_en}
                                    />
                                ) : (
                                    <div className="w-full h-full bg-blue-50 flex items-center justify-center text-blue-200">
                                        <svg className="w-20 h-20" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                )}
                                <div className="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent p-6 pt-20">
                                    <span className="bg-[#f5a623] text-blue-900 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest mb-2 inline-block">
                                        {site.category || 'Explore'}
                                    </span>
                                    <h3 className="text-white text-2xl font-black mb-1 leading-tight">{site.name_en}</h3>
                                    <div className="flex items-center gap-1.5 text-gray-300 text-xs">
                                        <FaMapMarkerAlt className="w-3.5 h-3.5" />
                                        {site.woreda?.name_en || 'Visit OSZA'}
                                    </div>
                                </div>
                            </div>
                            <div className="p-8 flex flex-col flex-grow">
                                <p className="text-gray-500 text-sm mb-6 line-clamp-3 leading-relaxed">
                                    {site.description_en.replace(/<[^>]*>/g, '').substring(0, 120)}...
                                </p>
                                <div className="mt-auto flex items-center justify-between">
                                    <span className="text-[#1a56db] font-black text-xs uppercase tracking-widest group-hover:translate-x-1 transition-transform flex items-center gap-2">
                                        Learn More <FaArrowRight />
                                    </span>
                                    <div className="flex -space-x-2">
                                        <div className="w-8 h-8 rounded-full border-2 border-white bg-blue-50 flex items-center justify-center text-[10px] font-black text-blue-900">12+</div>
                                        <div className="w-8 h-8 rounded-full border-2 border-white bg-green-50 flex items-center justify-center text-green-600">
                                            <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </Link>
                    ))}
                </div>

                {filteredSites.length === 0 && (
                    <div className="py-20 text-center">
                        <div className="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <FaSearch className="w-12 h-12 text-gray-300" />
                        </div>
                        <h3 className="text-xl font-bold text-gray-900 mb-2">No Destinations Found</h3>
                        <p className="text-gray-500">Try adjusting your search or category filters.</p>
                    </div>
                )}
            </div>
        </section>
    );
}
