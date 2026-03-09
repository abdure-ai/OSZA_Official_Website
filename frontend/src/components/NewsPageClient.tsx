'use client';
import { useState, useMemo } from 'react';
import Link from 'next/link';
import { NewsItem, getFileUrl } from '@/lib/api';
import { useTranslation } from 'react-i18next';
import { FaSearch, FaCalendarAlt, FaTag } from 'react-icons/fa';

interface Props {
    initialNews: NewsItem[];
    categories: string[];
}

export default function NewsPageClient({ initialNews, categories }: Props) {
    const { t, i18n } = useTranslation();
    const currentLang = i18n.language;
    const [search, setSearch] = useState('');
    const [selectedCategory, setSelectedCategory] = useState('');

    const filteredNews = useMemo(() => {
        return initialNews.filter(item => {
            const matchesSearch = search === '' ||
                (item.title_en + (item.title_am || '') + (item.title_or || '')).toLowerCase().includes(search.toLowerCase());
            const matchesCategory = selectedCategory === '' || item.category === selectedCategory;
            return matchesSearch && matchesCategory;
        });
    }, [initialNews, search, selectedCategory]);

    return (
        <div className="max-w-[1440px] mx-auto">
            {/* Filters Overlapping Hero */}
            <div className="flex flex-wrap items-center gap-4 mb-16 -mt-24 relative z-30 p-6 md:p-8 bg-white rounded-[2.5rem] shadow-2xl border border-gray-100">
                <div className="flex-grow min-w-[280px] relative group">
                    <FaSearch className="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-hover:text-blue-600 transition-colors" />
                    <input
                        type="text"
                        value={search}
                        onChange={(e) => setSearch(e.target.value)}
                        placeholder={t('search_news', 'Search for updates...')}
                        className="w-full pl-12 pr-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all shadow-inner"
                    />
                </div>

                <select
                    value={selectedCategory}
                    onChange={(e) => setSelectedCategory(e.target.value)}
                    className="px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all min-w-[200px] shadow-inner appearance-none cursor-pointer"
                >
                    <option value="">{t('all_categories', 'All Categories')}</option>
                    {categories.map(cat => (
                        <option key={cat} value={cat}>{cat}</option>
                    ))}
                </select>

                <button
                    onClick={() => { setSearch(''); setSelectedCategory(''); }}
                    className="px-8 py-4 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-red-500 transition-colors"
                >
                    Clear Filters
                </button>
            </div>

            {/* News Grid */}
            {filteredNews.length > 0 ? (
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    {filteredNews.map((post) => (
                        <article
                            key={post.id}
                            className="bg-white rounded-[2rem] overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-gray-100 flex flex-col group"
                        >
                            <div className="h-56 bg-gray-100 relative overflow-hidden">
                                {post.thumbnail_url ? (
                                    <img
                                        src={getFileUrl(post.thumbnail_url)}
                                        alt={(post as any)[`title_${currentLang}`] || post.title_en}
                                        className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                    />
                                ) : (
                                    <div className="w-full h-full flex items-center justify-center text-gray-300">
                                        <FaImages className="text-5xl opacity-20" />
                                    </div>
                                )}
                                <div className="absolute top-4 left-4">
                                    <span className="bg-white/90 backdrop-blur-md text-blue-900 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg">
                                        {post.category}
                                    </span>
                                </div>
                            </div>

                            <div className="p-8 flex flex-col flex-grow">
                                <div className="flex items-center gap-4 text-[10px] font-black uppercase tracking-[0.1em] text-gray-400 mb-4">
                                    <span className="flex items-center gap-1.5">
                                        <FaCalendarAlt className="text-blue-600/50" />
                                        {new Date(post.published_at).toLocaleDateString(undefined, { month: 'long', day: 'numeric', year: 'numeric' })}
                                    </span>
                                </div>

                                <h2 className="text-xl font-black text-gray-900 mb-4 leading-tight group-hover:text-blue-600 transition-colors line-clamp-2 italic tracking-tight">
                                    <Link href={`/news/${post.id}`}>
                                        {(post as any)[`title_${currentLang}`] || post.title_en}
                                    </Link>
                                </h2>

                                <p className="text-gray-500 text-sm mb-6 line-clamp-3 leading-relaxed flex-grow">
                                    {((post as any)[`content_${currentLang}`] || post.content_en).replace(/<[^>]*>?/gm, '').substring(0, 160)}...
                                </p>

                                <Link
                                    href={`/news/${post.id}`}
                                    className="inline-flex items-center gap-2 text-blue-600 font-black text-[10px] uppercase tracking-widest group-hover:gap-3 transition-all"
                                >
                                    {t('read_full_story', 'Read Full Story')} <span>→</span>
                                </Link>
                            </div>
                        </article>
                    ))}
                </div>
            ) : (
                <div className="text-center py-32 bg-white rounded-[3rem] border-2 border-dashed border-gray-100">
                    <div className="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <FaSearch className="text-3xl text-gray-200" />
                    </div>
                    <h3 className="text-xl font-black text-gray-900 mb-2 italic tracking-tight">No Articles Found</h3>
                    <p className="text-gray-400 text-sm">Try adjusting your search or filters.</p>
                </div>
            )}
        </div>
    );
}

import { FaImages } from 'react-icons/fa';
