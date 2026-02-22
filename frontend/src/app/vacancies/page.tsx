'use client';

import { useEffect, useState } from 'react';
import Link from 'next/link';
import { fetchVacancies, Vacancy } from '@/lib/api';
import { FaBriefcase, FaMapMarkerAlt, FaCalendarAlt, FaChevronRight, FaSpinner, FaFilter } from 'react-icons/fa';

export default function VacanciesPage() {
    const [vacancies, setVacancies] = useState<Vacancy[]>([]);
    const [loading, setLoading] = useState(true);
    const [filter, setFilter] = useState({
        department: '',
        type: ''
    });

    useEffect(() => {
        loadVacancies();
    }, [filter]);

    async function loadVacancies() {
        setLoading(true);
        const data = await fetchVacancies({
            department: filter.department || undefined,
            type: filter.type || undefined,
            active: 'true'
        });
        setVacancies(data);
        setLoading(false);
    }

    const departments = Array.from(new Set(vacancies.map(v => v.department)));

    return (
        <div className="bg-gray-50 min-h-screen">
            {/* Header */}
            <div className="bg-primary text-white py-16">
                <div className="container mx-auto px-4 text-center">
                    <h1 className="text-4xl font-bold mb-4">Job Vacancies</h1>
                    <p className="text-blue-100 max-w-2xl mx-auto">
                        Join our team and help build a better future for the Oromo Special Zone. Explore our latest opportunities across various departments.
                    </p>
                </div>
            </div>

            <div className="container mx-auto px-4 py-12">
                <div className="grid grid-cols-1 lg:grid-cols-4 gap-8">

                    {/* Sidebar Filters */}
                    <div className="lg:col-span-1 space-y-6">
                        <div className="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                            <h3 className="font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <FaFilter className="text-primary text-sm" /> Filter Results
                            </h3>

                            <div className="space-y-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Department</label>
                                    <select
                                        className="w-full p-2 border rounded-md text-sm"
                                        value={filter.department}
                                        onChange={(e) => setFilter({ ...filter, department: e.target.value })}
                                    >
                                        <option value="">All Departments</option>
                                        {departments.map(d => (
                                            <option key={d} value={d}>{d}</option>
                                        ))}
                                    </select>
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Job Type</label>
                                    <select
                                        className="w-full p-2 border rounded-md text-sm"
                                        value={filter.type}
                                        onChange={(e) => setFilter({ ...filter, type: e.target.value })}
                                    >
                                        <option value="">All Types</option>
                                        <option value="Full-time">Full-time</option>
                                        <option value="Part-time">Part-time</option>
                                        <option value="Contract">Contract</option>
                                        <option value="Internship">Internship</option>
                                    </select>
                                </div>

                                <button
                                    onClick={() => setFilter({ department: '', type: '' })}
                                    className="text-primary text-sm font-medium hover:underline"
                                >
                                    Clear all filters
                                </button>
                            </div>
                        </div>

                        <div className="bg-blue-900 text-white p-6 rounded-xl shadow-lg">
                            <h4 className="font-bold mb-2">Can't find what you're looking for?</h4>
                            <p className="text-sm text-blue-100 mb-4">Send us your CV to our talent database for future opportunities.</p>
                            <a href="/contact" className="block text-center bg-white text-blue-900 py-2 rounded font-bold hover:bg-blue-50 transition">Contact HR</a>
                        </div>
                    </div>

                    {/* Vacancy List */}
                    <div className="lg:col-span-3 space-y-6">
                        {loading ? (
                            <div className="py-20 text-center text-gray-400 bg-white rounded-xl border">
                                <FaSpinner className="animate-spin text-4xl mx-auto mb-4" />
                                <p>Finding job opportunities...</p>
                            </div>
                        ) : vacancies.length > 0 ? (
                            vacancies.map((v) => (
                                <Link
                                    key={v.id}
                                    href={`/vacancies/${v.id}`}
                                    className="block bg-white p-6 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow group"
                                >
                                    <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                        <div className="flex-1">
                                            <div className="flex items-center gap-2 mb-2">
                                                <span className="bg-blue-50 text-primary text-xs font-bold px-2 py-1 rounded uppercase tracking-wider">
                                                    {v.department}
                                                </span>
                                                <span className="bg-gray-100 text-gray-600 text-xs font-medium px-2 py-1 rounded">
                                                    {v.vacancy_type}
                                                </span>
                                            </div>
                                            <h2 className="text-xl font-bold text-gray-800 group-hover:text-primary transition-colors">{v.title_en}</h2>
                                            <div className="flex flex-wrap gap-4 mt-3 text-sm text-gray-500">
                                                <div className="flex items-center gap-1">
                                                    <FaMapMarkerAlt className="text-gray-400" /> {v.location_en}
                                                </div>
                                                <div className="flex items-center gap-1">
                                                    <FaCalendarAlt className="text-gray-400" /> Deadline: {new Date(v.deadline).toLocaleDateString()}
                                                </div>
                                            </div>
                                        </div>
                                        <div className="text-primary font-bold flex items-center gap-1">
                                            Apply Now <FaChevronRight className="text-xs" />
                                        </div>
                                    </div>
                                </Link>
                            ))
                        ) : (
                            <div className="py-20 text-center bg-white rounded-xl border">
                                <div className="text-gray-300 mb-4">
                                    <FaBriefcase className="text-6xl mx-auto" />
                                </div>
                                <h3 className="text-xl font-bold text-gray-800">No Vacancies Found</h3>
                                <p className="text-gray-500 mt-2">Try adjusting your filters or check back later.</p>
                            </div>
                        )}
                    </div>

                </div>
            </div>
        </div>
    );
}

