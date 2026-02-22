'use client';
import { useEffect, useState } from 'react';
import Link from 'next/link';
import { fetchGalleryCategories, GalleryCategory } from '@/lib/api';
import { FaImages, FaArrowRight } from 'react-icons/fa';
import { useTranslation } from 'react-i18next';

const BACKEND_URL = process.env.NEXT_PUBLIC_BACKEND_URL || 'http://localhost:5000';

export default function HomeGallery() {
    const { t } = useTranslation();
    const [categories, setCategories] = useState<GalleryCategory[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        fetchGalleryCategories().then(data => {
            setCategories(data);
            setLoading(false);
        });
    }, []);

    if (loading) return null;
    const visible = categories.slice(0, 6);

    if (visible.length === 0) return null;

    return (
        <section className="py-16 bg-gray-50">
            <div className="container mx-auto px-4">
                {/* Section Header */}
                <div className="flex items-end justify-between mb-8">
                    <div>
                        <div className="flex items-center gap-2 text-green-700 text-sm font-semibold uppercase tracking-widest mb-2">
                            <FaImages />
                            <span>{t('photo_gallery')}</span>
                        </div>
                        <h2 className="text-3xl font-bold text-gray-900">
                            {t('visual_stories')}
                        </h2>
                        <p className="text-gray-500 mt-1">
                            {t('gallery_subtitle')}
                        </p>
                    </div>
                    <Link
                        href="/gallery"
                        className="hidden sm:flex items-center gap-2 text-sm font-semibold text-green-700 hover:text-green-800 border border-green-200 hover:border-green-400 px-4 py-2 rounded-lg transition"
                    >
                        {t('view_all_albums')} <FaArrowRight size={12} />
                    </Link>
                </div>

                {/* Album Cards Grid */}
                <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                    {visible.map((cat) => (
                        <Link
                            key={cat.category}
                            href={`/gallery`}
                            className="group relative rounded-2xl overflow-hidden aspect-square bg-gray-200 shadow-sm hover:shadow-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500"
                        >
                            {cat.cover_url ? (
                                // eslint-disable-next-line @next/next/no-img-element
                                <img
                                    src={`${BACKEND_URL}${cat.cover_url}`}
                                    alt={cat.category}
                                    className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                />
                            ) : (
                                <div className="w-full h-full bg-gradient-to-br from-green-700 to-green-500 flex items-center justify-center">
                                    <FaImages className="text-white/30 text-4xl" />
                                </div>
                            )}

                            {/* Overlay */}
                            <div className="absolute inset-0 bg-gradient-to-t from-black/75 via-black/20 to-transparent" />

                            {/* Labels */}
                            <div className="absolute bottom-0 left-0 right-0 p-3">
                                <p className="text-white font-bold text-sm leading-tight truncate">
                                    {cat.category}
                                </p>
                                <p className="text-white/60 text-xs mt-0.5">
                                    {cat.count} {Number(cat.count) === 1 ? t('photo') : t('photos')}
                                </p>
                            </div>
                        </Link>
                    ))}
                </div>

                {/* Mobile "View All" link */}
                <div className="text-center mt-6 sm:hidden">
                    <Link
                        href="/gallery"
                        className="inline-flex items-center gap-2 text-sm font-semibold text-green-700 hover:text-green-800 border border-green-200 px-5 py-2 rounded-lg"
                    >
                        {t('view_all_albums')} <FaArrowRight size={12} />
                    </Link>
                </div>
            </div>
        </section>
    );
}
