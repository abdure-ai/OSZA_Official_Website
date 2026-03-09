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
                <div className="bg-gradient-to-br from-primary/5 to-white border border-primary/10 rounded-[2rem] p-8 md:p-16 relative">
                    <div className="flex flex-col lg:flex-row gap-12 lg:gap-20 items-center lg:items-start text-center lg:text-left">
                        {/* Admin Photo */}
                        <div className="flex-shrink-0 relative w-full lg:w-1/3 flex justify-center lg:justify-start">
                            <div className="w-64 h-64 md:w-80 md:h-80 lg:w-[400px] lg:h-[400px] rounded-3xl overflow-hidden border-8 border-white shadow-2xl relative z-10 bg-gray-100 transform -rotate-2 hover:rotate-0 transition-transform duration-500">
                                {msg.photo_url ? (
                                    <img
                                        src={getFileUrl(msg.photo_url)}
                                        alt={msg.name}
                                        className="w-full h-full object-cover"
                                    />
                                ) : (
                                    <div className="w-full h-full flex items-center justify-center bg-gray-50 text-primary">
                                        <span className="text-6xl">📷</span>
                                    </div>
                                )}
                            </div>
                            {/* Decorative Background Elements */}
                            <div className="absolute -bottom-8 -right-8 w-48 h-48 bg-accent/10 rounded-full blur-3xl -z-0" />
                            <div className="absolute -top-8 -left-8 w-48 h-48 bg-primary/10 rounded-full blur-3xl -z-0" />
                        </div>

                        {/* Message Content */}
                        <div className="lg:w-2/3 py-4">
                            <div className="inline-flex items-center gap-3 mb-6">
                                <span className="bg-primary text-white text-xs font-bold uppercase tracking-[0.2em] px-5 py-2 rounded-full shadow-lg shadow-primary/20">
                                    Official Communication
                                </span>
                            </div>

                            <h2 className="text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 mb-2 leading-tight">
                                {msg.name}
                            </h2>
                            <p className="text-primary font-bold text-lg md:text-xl mb-10 pb-6 border-b-2 border-dashed border-primary/10 inline-block">
                                {msg.title_position}
                            </p>

                            <div className="relative max-w-3xl">
                                <FaQuoteLeft className="absolute -top-10 -left-12 text-8xl text-primary/5 rotate-180 hidden md:block" />
                                <div className="text-gray-700 leading-relaxed text-xl md:text-2xl font-medium italic relative z-10 antialiased">
                                    "{(msg as any)[`message_${currentLang}`] || msg.message_en}"
                                </div>
                            </div>

                            <div className="mt-12 flex justify-center lg:justify-end">
                                <div className="text-right">
                                    <div className="h-24 w-48 relative flex items-center justify-center">
                                        {/* eslint-disable-next-line @next/next/no-img-element */}
                                        <img
                                            src="https://upload.wikimedia.org/wikipedia/commons/e/e0/Signature_of_Barack_Obama.svg"
                                            alt="Signature"
                                            className="h-16 opacity-30 grayscale contrast-200 absolute rotate-[-5deg]"
                                        />
                                        <div className="w-full h-[2px] bg-gray-200/50 mt-12" />
                                    </div>
                                    <div className="text-[10px] font-black text-gray-300 tracking-[0.3em] uppercase italic mt-2">
                                        Electronic Verification
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Large Decorative Icon */}
                    <div className="absolute bottom-0 right-0 p-12 opacity-[0.03] pointer-events-none hidden lg:block">
                        <FaQuoteLeft className="w-64 h-64 text-primary" />
                    </div>
                </div>
            </div>
        </section>
    );
}
