'use client';
import { useState } from 'react';
import Link from 'next/link';
import { useTranslation } from 'react-i18next';
import { FaSearch, FaBars, FaTimes, FaGlobe } from 'react-icons/fa';
import '../../i18n/config'; // Init i18n

export default function Navbar() {
    const { t, i18n } = useTranslation();
    const [isMenuOpen, setIsMenuOpen] = useState(false);
    const [langDropdownOpen, setLangDropdownOpen] = useState(false);

    const toggleLanguage = (lang: string) => {
        i18n.changeLanguage(lang);
        setLangDropdownOpen(false);
    };

    return (
        <nav className="bg-white shadow-md sticky top-0 z-40">
            <div className="container mx-auto px-4">
                <div className="flex justify-between items-center h-20">

                    {/* Logo Section */}
                    <Link href="/" className="flex items-center gap-3">
                        {/* Replace with actual logo */}
                        <div className="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-bold text-xl">
                            O
                        </div>
                        <div className="flex flex-col">
                            <span className="font-bold text-lg leading-tight text-gray-800">
                                {t('title_main', 'Oromo Special Zone')}
                            </span>
                            <span className="text-xs text-gray-500 font-medium">
                                {t('title_sub', 'Administration')}
                            </span>
                        </div>
                    </Link>

                    {/* Desktop Navigation */}
                    <div className="hidden md:flex items-center space-x-8">
                        <Link href="/" className="text-gray-700 hover:text-primary font-medium transition-colors">
                            {t('home')}
                        </Link>
                        <Link href="/news" className="text-gray-700 hover:text-primary font-medium transition-colors">
                            {t('news')}
                        </Link>
                        <Link href="/documents" className="text-gray-700 hover:text-primary font-medium transition-colors">
                            {t('documents')}
                        </Link>
                        <Link href="/about" className="text-gray-700 hover:text-primary font-medium transition-colors">
                            {t('about')}
                        </Link>
                        <Link href="/contact" className="text-gray-700 hover:text-primary font-medium transition-colors">
                            {t('contact')}
                        </Link>
                    </div>

                    {/* Right Actions: Search & Language */}
                    <div className="hidden md:flex items-center space-x-4">
                        {/* Search Bar */}
                        <div className="relative">
                            <input
                                type="text"
                                placeholder={t('search')}
                                className="pl-3 pr-10 py-1.5 border rounded-full text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary w-40 transition-all focus:w-64"
                            />
                            <FaSearch className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm" />
                        </div>

                        {/* Login Button */}
                        <Link
                            href="/admin/login"
                            className="bg-primary/10 text-primary hover:bg-primary hover:text-white px-4 py-1.5 rounded-full text-sm font-bold transition-all border border-primary/20"
                        >
                            {t('login')}
                        </Link>

                        {/* Language Switcher */}
                        <div className="relative">
                            <button
                                onClick={() => setLangDropdownOpen(!langDropdownOpen)}
                                className="flex items-center gap-1 text-gray-700 hover:text-primary transition-colors focus:outline-none"
                            >
                                <FaGlobe />
                                <span className="uppercase text-sm font-semibold">{i18n.language}</span>
                            </button>

                            {langDropdownOpen && (
                                <div className="absolute right-0 mt-2 w-32 bg-white border rounded-md shadow-lg py-1">
                                    <button onClick={() => toggleLanguage('en')} className="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">English</button>
                                    <button onClick={() => toggleLanguage('am')} className="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Amharic</button>
                                    <button onClick={() => toggleLanguage('or')} className="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Afaan Oromo</button>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Mobile Menu Button */}
                    <button
                        className="md:hidden text-gray-700 focus:outline-none"
                        onClick={() => setIsMenuOpen(!isMenuOpen)}
                    >
                        {isMenuOpen ? <FaTimes size={24} /> : <FaBars size={24} />}
                    </button>
                </div>
            </div>

            {/* Mobile Menu Dropdown */}
            {isMenuOpen && (
                <div className="md:hidden bg-white border-t px-4 py-4 space-y-4 shadow-inner">
                    <Link href="/" className="block text-gray-700 hover:text-primary font-medium" onClick={() => setIsMenuOpen(false)}>
                        {t('home')}
                    </Link>
                    <Link href="/news" className="block text-gray-700 hover:text-primary font-medium" onClick={() => setIsMenuOpen(false)}>
                        {t('news')}
                    </Link>
                    <Link href="/documents" className="block text-gray-700 hover:text-primary font-medium" onClick={() => setIsMenuOpen(false)}>
                        {t('documents')}
                    </Link>
                    <Link href="/about" className="block text-gray-700 hover:text-primary font-medium" onClick={() => setIsMenuOpen(false)}>
                        {t('about')}
                    </Link>
                    <Link href="/contact" className="block text-gray-700 hover:text-primary font-medium" onClick={() => setIsMenuOpen(false)}>
                        {t('contact')}
                    </Link>
                    <Link href="/admin/login" className="block text-primary font-bold" onClick={() => setIsMenuOpen(false)}>
                        {t('login')}
                    </Link>

                    <div className="pt-4 border-t border-gray-100">
                        <input
                            type="text"
                            placeholder="Search..."
                            className="w-full px-4 py-2 border rounded-md text-sm mb-4"
                        />
                        <div className="flex gap-4 justify-center">
                            <button onClick={() => toggleLanguage('en')} className={`px-2 py-1 text-sm rounded ${i18n.language === 'en' ? 'bg-gray-200' : ''}`}>EN</button>
                            <button onClick={() => toggleLanguage('am')} className={`px-2 py-1 text-sm rounded ${i18n.language === 'am' ? 'bg-gray-200' : ''}`}>AM</button>
                            <button onClick={() => toggleLanguage('or')} className={`px-2 py-1 text-sm rounded ${i18n.language === 'or' ? 'bg-gray-200' : ''}`}>OR</button>
                        </div>
                    </div>
                </div>
            )}
        </nav>
    );
}
