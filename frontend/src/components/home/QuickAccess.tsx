'use client';
import Link from 'next/link';
import {
    HiOutlineBriefcase,
    HiOutlineDocumentText,
    HiOutlinePhotograph,
    HiOutlineChartBar,
    HiOutlineLibrary,
    HiOutlineChatAlt2
} from 'react-icons/hi';
import { useTranslation } from 'react-i18next';

export default function QuickAccess() {
    const { t } = useTranslation();

    const quickLinks = [
        {
            icon: HiOutlineChartBar,
            title: t("projects"),
            desc: t("development_works"),
            href: "/projects",
            color: "from-blue-500 to-indigo-600",
            lightColor: "bg-blue-50",
            iconColor: "text-blue-600"
        },
        {
            icon: HiOutlineLibrary,
            title: t("digital_library"),
            desc: t("digital_resources"),
            href: "/documents",
            color: "from-emerald-500 to-teal-600",
            lightColor: "bg-emerald-50",
            iconColor: "text-emerald-600"
        },
        {
            icon: HiOutlineDocumentText,
            title: t("tenders"),
            desc: t("procurement"),
            href: "/tenders",
            color: "from-amber-500 to-orange-600",
            lightColor: "bg-amber-50",
            iconColor: "text-amber-600"
        },
        {
            icon: HiOutlineBriefcase,
            title: t("vacancies"),
            desc: t("join_our_team"),
            href: "/vacancies",
            color: "from-purple-500 to-pink-600",
            lightColor: "bg-purple-50",
            iconColor: "text-purple-600"
        },
        {
            icon: HiOutlinePhotograph,
            title: t("photo_gallery"),
            desc: t("visual_stories"),
            href: "/gallery",
            color: "from-rose-500 to-red-600",
            lightColor: "bg-rose-50",
            iconColor: "text-rose-600"
        },
        {
            icon: HiOutlineChatAlt2,
            title: t("investment"),
            desc: t("opportunities"),
            href: "/investment",
            color: "from-cyan-500 to-blue-600",
            lightColor: "bg-cyan-50",
            iconColor: "text-cyan-600"
        },
    ];

    return (
        <section className="py-12 -mt-20 relative z-20 antialiased">
            <div className="container mx-auto px-4">
                <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                    {quickLinks.map((link, index) => (
                        <Link
                            key={index}
                            href={link.href}
                            className="group relative bg-white p-6 rounded-[2rem] shadow-xl shadow-gray-200/50 hover:shadow-2xl hover:shadow-gray-300/60 transition-all duration-500 hover:-translate-y-2 border border-gray-100 overflow-hidden"
                        >
                            {/* Animated Background Gradient on Hover */}
                            <div className={`absolute inset-0 bg-gradient-to-br ${link.color} opacity-0 group-hover:opacity-5 transition-opacity duration-500`} />

                            <div className="relative z-10 flex flex-col items-center">
                                {/* Icon Container */}
                                <div className={`w-16 h-16 mb-4 rounded-2xl ${link.lightColor} flex items-center justify-center group-hover:scale-110 transition-transform duration-500 shadow-inner`}>
                                    <link.icon className={`text-3xl ${link.iconColor} group-hover:scale-110 transition-transform duration-500`} />
                                </div>

                                <h3 className="font-black text-gray-900 text-sm md:text-base uppercase tracking-tight mb-1 group-hover:text-blue-600 transition-colors duration-300">
                                    {link.title}
                                </h3>

                                <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest opacity-80 group-hover:opacity-100 transition-opacity leading-tight">
                                    {link.desc}
                                </p>

                                {/* Bottom Decorative Line */}
                                <div className={`mt-4 h-1 w-0 bg-gradient-to-r ${link.color} rounded-full group-hover:w-12 transition-all duration-500`} />
                            </div>
                        </Link>
                    ))}
                </div>
            </div>
        </section>
    );
}
