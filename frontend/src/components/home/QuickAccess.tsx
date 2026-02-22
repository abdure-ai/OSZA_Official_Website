'use client';
import Link from 'next/link';
import { FaFileDownload, FaAddressBook, FaProjectDiagram, FaBriefcase, FaLightbulb, FaGavel } from 'react-icons/fa';
import { useTranslation } from 'react-i18next';

export default function QuickAccess() {
    const { t } = useTranslation();

    const quickLinks = [
        { icon: FaFileDownload, title: t("digital_library"), desc: t("digital_resources"), href: "/documents", color: "text-blue-600" },
        { icon: FaAddressBook, title: t("contact_directory"), desc: t("find_key_officials"), href: "/directory", color: "text-green-600" },
        { icon: FaProjectDiagram, title: t("projects"), desc: t("development_works"), href: "/projects", color: "text-purple-600" },
        { icon: FaBriefcase, title: t("vacancies"), desc: t("join_our_team"), href: "/vacancies", color: "text-orange-600" },
        { icon: FaGavel, title: t("tenders"), desc: t("procurement"), href: "/tenders", color: "text-red-900" },
        { icon: FaLightbulb, title: t("investment"), desc: t("opportunities"), href: "/investment", color: "text-yellow-600" },
    ];
    return (
        <section className="py-12 -mt-16 relative z-20">
            <div className="container mx-auto px-4">
                <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    {quickLinks.map((link, index) => (
                        <Link
                            key={index}
                            href={link.href}
                            className="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow flex flex-col items-center text-center border border-gray-100 group"
                        >
                            <div className={`text-3xl mb-3 ${link.color} group-hover:scale-110 transition-transform`}>
                                <link.icon />
                            </div>
                            <h3 className="font-bold text-gray-800 text-sm md:text-base">{link.title}</h3>
                            <p className="text-xs text-gray-500 mt-1">{link.desc}</p>
                        </Link>
                    ))}
                </div>
            </div>
        </section>
    );
}
