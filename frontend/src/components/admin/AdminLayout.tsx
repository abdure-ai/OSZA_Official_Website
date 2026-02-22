'use client';
import { useState } from 'react';
import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { FaTachometerAlt, FaNewspaper, FaFilePdf, FaBullhorn, FaUsers, FaSignOutAlt, FaImages, FaMapMarkerAlt, FaPhotoVideo, FaBriefcase, FaGavel, FaProjectDiagram, FaQuoteLeft, FaUserTie, FaHandHoldingUsd, FaEnvelope, FaCog } from 'react-icons/fa';

export default function AdminLayout({ children }: { children: React.ReactNode }) {
    const pathname = usePathname();
    const [sidebarOpen, setSidebarOpen] = useState(true);

    const navItems = [
        { name: 'Dashboard', href: '/admin/dashboard', icon: FaTachometerAlt },
        { name: 'Hero Slides', href: '/admin/hero', icon: FaImages },
        { name: 'News Management', href: '/admin/news', icon: FaNewspaper },
        { name: 'Documents', href: '/admin/documents', icon: FaFilePdf },
        { name: 'Emergency Alerts', href: '/admin/alerts', icon: FaBullhorn },
        { name: 'Contact Directory', href: '/admin/directory', icon: FaUserTie },
        { name: 'Contact Us', href: '/admin/contact', icon: FaEnvelope },
        { name: 'Investments', href: '/admin/investment', icon: FaHandHoldingUsd },
        { name: 'Woredas', href: '/admin/woredas', icon: FaMapMarkerAlt },
        { name: 'Gallery', href: '/admin/gallery', icon: FaPhotoVideo },
        { name: 'Vacancies', href: '/admin/vacancies', icon: FaBriefcase },
        { name: 'Tenders', href: '/admin/tenders', icon: FaGavel },
        { name: 'Projects', href: '/admin/projects', icon: FaProjectDiagram },
        { name: 'Admin Message', href: '/admin/message', icon: FaQuoteLeft },
        { name: 'Office Settings', href: '/admin/settings', icon: FaCog },
        { name: 'User Management', href: '/admin/users', icon: FaUsers },
    ];

    return (
        <div className="flex min-h-screen bg-gray-100">
            {/* Sidebar */}
            <aside className={`bg-gray-900 text-white w-64 flex-shrink-0 transition-all ${sidebarOpen ? 'block' : 'hidden md:block'}`}>
                <div className="p-6 border-b border-gray-800">
                    <h2 className="text-xl font-bold">Admin Portal</h2>
                    <p className="text-xs text-gray-400">Oromo Special Zone</p>
                </div>

                <nav className="p-4 space-y-2">
                    {navItems.map((item) => {
                        const isActive = pathname === item.href;
                        return (
                            <Link
                                key={item.href}
                                href={item.href}
                                className={`flex items-center gap-3 px-4 py-3 rounded-lg transition-colors ${isActive ? 'bg-primary text-white' : 'text-gray-300 hover:bg-gray-800'}`}
                            >
                                <item.icon />
                                <span>{item.name}</span>
                            </Link>
                        )
                    })}
                </nav>

                <div className="p-4 border-t border-gray-800 mt-auto">
                    <button
                        onClick={() => {
                            document.cookie = 'adminToken=; path=/; max-age=0';
                            localStorage.removeItem('adminToken');
                            localStorage.removeItem('adminUser');
                            window.location.href = '/admin/login';
                        }}
                        className="flex items-center gap-3 px-4 py-3 text-red-400 hover:text-red-300 w-full"
                    >
                        <FaSignOutAlt />
                        <span>Logout</span>
                    </button>
                </div>
            </aside>

            {/* Main Content Area */}
            <main className="flex-1 p-8">
                {children}
            </main>
        </div>
    );
}
