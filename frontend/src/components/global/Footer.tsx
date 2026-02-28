'use client';
import Link from 'next/link';
import { FaFacebook, FaTwitter, FaTelegram, FaMapMarkerAlt, FaPhone, FaEnvelope } from 'react-icons/fa';
import { useTranslation } from 'react-i18next';

export default function Footer() {
    const { t } = useTranslation();
    return (
        <footer className="bg-gray-900 text-white pt-12 pb-6">
            <div className="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">

                {/* Contact Info */}
                <div>
                    <h3 className="text-xl font-bold mb-4 text-accent">{t('contact_us')}</h3>
                    <ul className="space-y-3 text-gray-300">
                        <li className="flex items-start gap-3">
                            <FaMapMarkerAlt className="mt-1 text-primary" />
                            <span>Oromo Special Zone Administration Office,<br />Kemise, Amhara Region, Ethiopia</span>
                        </li>
                        <li className="flex items-center gap-3">
                            <FaPhone className="text-primary" />
                            <span>+251 33 111 2222</span>
                        </li>
                        <li className="flex items-center gap-3">
                            <FaEnvelope className="text-primary" />
                            <span>info@oromospecialzone.gov.et</span>
                        </li>
                    </ul>
                </div>

                {/* Quick Links */}
                <div>
                    <h3 className="text-xl font-bold mb-4 text-accent">{t('quick_links')}</h3>
                    <ul className="space-y-2 text-gray-300">
                        <li><Link href="/about" className="hover:text-primary transition-colors">{t('strategic_plan', 'Strategic Plan 2025')}</Link></li>
                        <li><Link href="/tenders" className="hover:text-primary transition-colors">{t('tenders')}</Link></li>
                        <li><Link href="/vacancies" className="hover:text-primary transition-colors">{t('vacancies')}</Link></li>
                        <li><Link href="/directory" className="hover:text-primary transition-colors">{t('contact_directory')}</Link></li>
                        <li><Link href="/contact" className="hover:text-primary transition-colors">{t('feedback', 'Public Feedback')}</Link></li>
                    </ul>
                </div>

                {/* Social & Newsletter */}
                <div>
                    <h3 className="text-xl font-bold mb-4 text-accent">{t('stay_connected')}</h3>
                    <div className="flex space-x-4 mb-6">
                        <a href="#" className="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-primary transition-colors">
                            <FaFacebook />
                        </a>
                        <a href="#" className="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-primary transition-colors">
                            <FaTwitter />
                        </a>
                        <a href="#" className="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-primary transition-colors">
                            <FaTelegram />
                        </a>
                    </div>

                    <h4 className="font-semibold mb-2">{t('subscribe_updates', 'Subscribe to Updates')}</h4>
                    <div className="flex">
                        <input
                            type="email"
                            placeholder={t('enter_email')}
                            className="bg-gray-800 border-none text-white px-4 py-2 rounded-l-md focus:ring-1 focus:ring-primary w-full"
                        />
                        <button className="bg-primary px-4 py-2 rounded-r-md hover:bg-blue-600 transition-colors">
                            {t('subscribe')}
                        </button>
                    </div>
                </div>
            </div>

            <div className="border-t border-gray-800 pt-6 text-center text-gray-500 text-sm">
                <p>&copy; {new Date().getFullYear()} Oromo Special Zone Administration. All Rights Reserved.</p>
                <p className="mt-1">Powered by Regional ICT Bureau.</p>
            </div>
        </footer>
    );
}
