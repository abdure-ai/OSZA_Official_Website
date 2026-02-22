'use client';

import { useEffect, useState } from 'react';
import { fetchAdminMessage, AdminMessage, getFileUrl } from '@/lib/api';
import { FaQuoteLeft, FaSpinner } from 'react-icons/fa';
import { useTranslation } from 'react-i18next';

export default function AdminMessageSection() {
    const { t, i18n } = useTranslation();
    const currentLang = i18n.language;
    const [msg, setMsg] = useState<AdminMessage | null>(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        fetchAdminMessage().then(data => {
            setMsg(data);
            setLoading(false);
        });
    }, []);

    if (loading) {
        return (
            <div className="py-20 flex justify-center items-center">
                <FaSpinner className="animate-spin text-4xl text-primary" />
            </div>
        );
    }

    if (!msg || !msg.is_active) return null;

    return (
        <section className="py-20 bg-white overflow-hidden">
            <div className="container mx-auto px-4">
                <div className="max-w-6xl mx-auto">
                    <div className="flex flex-col md:flex-row items-center gap-12 lg:gap-20">
                        {/* Image Side */}
                        <div className="w-full md:w-1/3 lg:w-2/5">
                            <div className="relative">
                                {/* Decorative elements */}
                                <div className="absolute -top-6 -left-6 w-24 h-24 bg-primary/10 rounded-3xl -z-10" />
                                <div className="absolute -bottom-6 -right-6 w-32 h-32 bg-primary-light/20 rounded-full -z-10 blur-xl" />

                                {msg.photo_url ? (
                                    <img
                                        src={getFileUrl(msg.photo_url)}
                                        alt={msg.name}
                                        className="w-full h-auto aspect-square object-cover rounded-[2.5rem] shadow-2xl border-8 border-white transform hover:scale-[1.02] transition-transform duration-500"
                                    />
                                ) : (
                                    <div className="w-full aspect-square bg-gray-100 rounded-[2.5rem] flex items-center justify-center border-8 border-white shadow-xl">
                                        <span className="text-6xl text-gray-200">📷</span>
                                    </div>
                                )}

                                <div className="absolute bottom-6 -right-4 bg-primary text-white p-4 rounded-2xl shadow-xl flex items-center justify-center">
                                    <FaQuoteLeft className="text-2xl" />
                                </div>
                            </div>
                        </div>

                        {/* Content Side */}
                        <div className="flex-1 text-center md:text-left">
                            <h2 className="text-sm font-bold text-primary uppercase tracking-[0.2em] mb-4">{t('admin_message')}</h2>
                            <h3 className="text-3xl lg:text-4xl font-black text-gray-900 mb-8 leading-tight">
                                {t('admin_message_tagline', 'Dedication to Excellence & Community Service')}
                            </h3>

                            <div className="relative">
                                <p className="text-lg lg:text-xl text-gray-600 italic leading-relaxed mb-10 relative z-10">
                                    "{(msg as any)[`message_${currentLang}`] || msg.message_en}"
                                </p>
                            </div>

                            <div className="flex flex-col items-center md:items-start">
                                <h4 className="text-xl font-bold text-gray-900">{msg.name}</h4>
                                <p className="text-primary font-medium">{msg.title_position}</p>
                            </div>

                            <div className="mt-10 h-1 w-20 bg-primary/20 rounded-full hidden md:block" />
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
