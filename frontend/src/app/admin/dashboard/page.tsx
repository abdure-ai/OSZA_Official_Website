'use client';

import { useEffect, useState } from 'react';
import AdminLayout from '@/components/admin/AdminLayout';
import { fetchStats, DashboardStats } from '@/lib/api';
import Link from 'next/link';

const statCards = [
    { key: 'news' as keyof DashboardStats, title: 'Total News', color: 'bg-blue-500', icon: '📰' },
    { key: 'documents' as keyof DashboardStats, title: 'Documents', color: 'bg-green-500', icon: '📄' },
    { key: 'alerts' as keyof DashboardStats, title: 'Active Alerts', color: 'bg-red-500', icon: '🚨' },
    { key: 'vacancies' as keyof DashboardStats, title: 'Open Vacancies', color: 'bg-orange-500', icon: '💼' },
    { key: 'tenders' as keyof DashboardStats, title: 'Open Tenders', color: 'bg-indigo-600', icon: '⚖️' },
    { key: 'users' as keyof DashboardStats, title: 'Users', color: 'bg-purple-500', icon: '👤' },
];

export default function DashboardPage() {
    const [stats, setStats] = useState<DashboardStats | null>(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const token = localStorage.getItem('adminToken') || '';
        fetchStats(token).then((data) => {
            setStats(data);
            setLoading(false);
        });
    }, []);

    return (
        <AdminLayout>
            <div className="mb-6">
                <h1 className="text-2xl font-bold text-gray-800">Dashboard Overview</h1>
                <p className="text-gray-500 text-sm mt-1">
                    Live data from the database.
                </p>
            </div>

            {/* Stats Grid */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {statCards.map(({ key, title, color, icon }) => (
                    <div key={key} className="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-gray-500 text-sm">{title}</p>
                                {loading ? (
                                    <div className="h-9 w-16 bg-gray-200 rounded animate-pulse mt-1" />
                                ) : (
                                    <h3 className="text-3xl font-bold text-gray-800">
                                        {stats ? stats[key] : '—'}
                                    </h3>
                                )}
                            </div>
                            <div className={`w-10 h-10 rounded-full ${color} flex items-center justify-center text-lg`}>
                                {icon}
                            </div>
                        </div>
                    </div>
                ))}
            </div>

            {/* Quick Actions */}
            <div className="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 className="font-bold text-lg mb-4 text-gray-800">Quick Actions</h2>
                <div className="flex flex-wrap gap-4">
                    <Link href="/admin/news" className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                        Manage News
                    </Link>
                    <Link href="/admin/documents" className="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                        Manage Documents
                    </Link>
                    <Link href="/admin/users" className="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition">
                        Manage Users
                    </Link>
                </div>
            </div>
        </AdminLayout>
    );
}
