'use client';
import { useEffect, useState } from 'react';
import Link from 'next/link';
import { useTranslation } from 'react-i18next';
import { fetchWoredas, WoredaItem, getFileUrl } from '@/lib/api';
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
                <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    {woredas.map(w => (
                        <Link
                            key={w.id}
                            href={`/woreda/${w.slug}`}
                            className="group relative rounded-2xl overflow-hidden border border-gray-100 hover:border-primary/30 shadow-sm hover:shadow-md transition-all bg-white flex flex-col items-center p-5 text-center"
                        >
                            {/* Logo/Icon */}
                            {w.logo_url ? (
                                <img
                                    src={getFileUrl(w.logo_url)}
                                    alt={(w as any)[`name_${currentLang}`] || w.name_en}
                                    className="w-14 h-14 rounded-full object-cover mb-3 border-2 border-white shadow"
                                />
                            ) : (
                                <div className="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center mb-3 border-2 border-white shadow">
                                    <span className="text-primary font-bold text-xl">
                                        {((w as any)[`name_${currentLang}`] || w.name_en).substring(0, 1).toUpperCase()}
                                    </span>
                                </div>
                            )}

                            <span className="font-semibold text-sm text-gray-800 group-hover:text-primary transition-colors leading-tight">
                                {(w as any)[`name_${currentLang}`] || w.name_en}
                            </span>

                            {((w as any)[`capital_${currentLang}`] || w.capital_en) && (
                                <span className="text-xs text-gray-400 mt-1">
                                    {(w as any)[`capital_${currentLang}`] || w.capital_en}
                                </span>
                            )}
                        </Link>
                    ))}
                </div>
            </div>
        </section>
    );
}
