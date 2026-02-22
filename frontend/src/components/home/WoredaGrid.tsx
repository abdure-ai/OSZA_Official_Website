'use client';
import { useEffect, useState } from 'react';
import Link from 'next/link';
import { useTranslation } from 'react-i18next';
import { fetchWoredas, WoredaItem } from '@/lib/api';
import { FaMapMarkerAlt, FaUsers, FaArrowRight } from 'react-icons/fa';

export default function WoredaGrid() {
    const { t, i18n } = useTranslation();
    const currentLang = i18n.language;
    const [woredas, setWoredas] = useState<WoredaItem[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        fetchWoredas().then(data => {
            setWoredas(data);
            setLoading(false);
        });
    }, []);

    if (loading) return <div className="py-20 text-center">{t('loading', 'Loading...')}</div>;
    if (woredas.length === 0) return null;

    return (
        <section className="py-16 bg-gray-50 border-t border-gray-100">
            <div className="container mx-auto px-4">
                {/* Section Header */}
                <div className="text-center mb-10">
                    <span className="inline-block bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wide mb-3">
                        {t('our_woredas', 'Our Woredas')}
                    </span>
                    <h2 className="text-3xl font-bold text-gray-900">{t('woreda_profiles')}</h2>
                    <p className="text-gray-500 mt-2 max-w-xl mx-auto">
                        {t('woreda_profiles_subtitle', 'Each woreda in the Oromo Special Zone has its own dedicated administration page with local services and information.')}
                    </p>
                </div>

                {/* Woreda Cards */}
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    {woredas.map(w => (
                        <Link
                            key={w.id}
                            href={`/woreda/${w.slug}`}
                            className="group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-lg transition p-6 flex flex-col gap-3"
                        >
                            {/* Icon + Name */}
                            <div className="flex items-center gap-3">
                                <div className="w-11 h-11 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 group-hover:bg-green-200 transition">
                                    <FaMapMarkerAlt className="text-green-700 text-lg" />
                                </div>
                                <div>
                                    <h3 className="font-bold text-gray-900 text-lg leading-tight group-hover:text-green-700 transition">
                                        {(w as any)[`name_${currentLang}`] || w.name_en}
                                    </h3>
                                    {((w as any)[`capital_${currentLang}`] || w.capital_en) && (
                                        <p className="text-xs text-gray-400 mt-0.5">{(w as any)[`capital_${currentLang}`] || w.capital_en}</p>
                                    )}
                                </div>
                            </div>

                            {/* Description */}
                            {((w as any)[`description_${currentLang}`] || w.description_en) && (
                                <p className="text-sm text-gray-500 leading-relaxed line-clamp-2">
                                    {(w as any)[`description_${currentLang}`] || w.description_en}
                                </p>
                            )}

                            {/* Stats row */}
                            <div className="flex gap-4 text-xs text-gray-400 border-t border-gray-50 pt-3 mt-auto">
                                {w.population && (
                                    <span className="flex items-center gap-1">
                                        <FaUsers className="text-gray-300" />
                                        {w.population}
                                    </span>
                                )}
                                {w.area_km2 && (
                                    <span>📐 {w.area_km2} km²</span>
                                )}
                            </div>

                            {/* CTA */}
                            <div className="flex items-center gap-1 text-green-600 text-sm font-semibold group-hover:gap-2 transition-all">
                                {t('visit_official_page', 'Visit Official Page')} <FaArrowRight size={12} />
                            </div>
                        </Link>
                    ))}
                </div>
            </div>
        </section>
    );
}
