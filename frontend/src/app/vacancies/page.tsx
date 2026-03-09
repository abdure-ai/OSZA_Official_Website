'use client';

import { useEffect, useState } from 'react';
import Link from 'next/link';
import { fetchVacancies, Vacancy } from '@/lib/api';
import { FaBriefcase, FaMapMarkerAlt, FaCalendarAlt, FaChevronRight, FaSpinner, FaFilter, FaBuilding } from 'react-icons/fa';
import { useTranslation } from 'react-i18next';

export default function VacanciesPage() {
    const { t } = useTranslation();
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
        <div className="bg-gray-50 min-h-screen pb-20">
            {/* ═══════════════════════════════════════════ VACANCIES HERO ══ */}
            <section className="relative bg-blue-900 text-white py-20 md:py-32 overflow-hidden">
                <div className="absolute inset-0 z-0">
                    <div className="absolute inset-0 bg-gradient-to-r from-blue-950/80 to-blue-900/40 z-10"></div>
                    <img
                        src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?q=80&w=1920&auto=format&fit=crop"
                        className="w-full h-full object-cover scale-110 opacity-60"
                        alt="Vacancies Hero"
                    />
                </div>

                <div className="container mx-auto px-4 relative z-20">
                    <div className="max-w-3xl">
                        <span className="inline-block px-4 py-1.5 bg-[#f5a623] text-blue-900 text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-6 shadow-xl">
                            Careers
                        </span>
                        <h1 className="text-5xl md:text-7xl font-black mb-4 leading-none antialiased drop-shadow-2xl italic tracking-tight">
                            Join Our <span className="text-[#f5a623]">Team</span>
                        </h1>
                        <p className="text-lg md:text-xl text-gray-200 font-medium opacity-90">
                            Build a career with purpose. Join the dedicated team serving the Oromo Special Zone administration.
                        </p>
                    </div>
                </div>
                {/* Bottom fade */}
                <div className="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-gray-50 to-transparent"></div>
            </section>

            <div className="container mx-auto px-4 -mt-10 relative z-30">
                <div className="grid grid-cols-1 lg:grid-cols-4 gap-12 max-w-[1440px] mx-auto">

                    {/* Sidebar Filters Refined */}
                    <div className="lg:col-span-1 space-y-8">
                        <div className="bg-white p-8 rounded-[2.5rem] shadow-2xl border border-gray-100">
                            <h3 className="text-sm font-black text-blue-950 mb-8 flex items-center gap-3 uppercase tracking-widest">
                                <FaFilter className="text-blue-600" /> Filter Options
                            </h3>

                            <div className="space-y-6">
                                <div>
                                    <label className="block text-[10px] font-black text-gray-400 mb-3 uppercase tracking-widest">Department</label>
                                    <select
                                        className="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all shadow-inner appearance-none cursor-pointer"
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
                                    <label className="block text-[10px] font-black text-gray-400 mb-3 uppercase tracking-widest">Job Type</label>
                                    <select
                                        className="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all shadow-inner appearance-none cursor-pointer"
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
                                    className="w-full py-4 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-red-500 transition-colors border-t border-gray-100 pt-6 mt-6"
                                >
                                    Clear all filters
                                </button>
                            </div>
                        </div>

                        <div className="bg-blue-900 text-white p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden group">
                            <div className="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16 group-hover:scale-110 transition-transform duration-700"></div>
                            <h4 className="text-xl font-black mb-4 italic tracking-tight relative z-10">Talent Pool</h4>
                            <p className="text-sm text-blue-100/60 mb-6 font-medium relative z-10">Don't see a perfect match? Send your CV for future opportunities.</p>
                            <Link href="/contact" className="block text-center bg-[#f5a623] text-blue-900 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:scale-105 transition-all shadow-xl relative z-10">
                                Contact HR
                            </Link>
                        </div>
                    </div>

                    {/* Vacancy List Refined */}
                    <div className="lg:col-span-3 space-y-6">
                        {loading ? (
                            <div className="py-32 text-center bg-white rounded-[3rem] border-2 border-dashed border-gray-100 italic font-bold text-gray-400 animate-pulse">
                                Finding Careers...
                            </div>
                        ) : vacancies.length > 0 ? (
                            vacancies.map((v) => (
                                <Link
                                    key={v.id}
                                    href={`/vacancies/${v.id}`}
                                    className="block bg-white p-8 md:p-10 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-2xl transition-all duration-500 group relative overflow-hidden"
                                >
                                    <div className="flex flex-col md:flex-row md:items-center justify-between gap-8">
                                        <div className="flex-1">
                                            <div className="flex flex-wrap items-center gap-3 mb-4">
                                                <span className="bg-blue-50 text-blue-600 text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest shadow-sm">
                                                    {v.department}
                                                </span>
                                                <span className="bg-gray-100 text-gray-500 text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest">
                                                    {v.vacancy_type}
                                                </span>
                                            </div>
                                            <h2 className="text-2xl font-black text-gray-900 group-hover:text-blue-600 transition-colors mb-4 italic tracking-tight leading-tight">
                                                {v.title_en}
                                            </h2>
                                            <div className="flex flex-wrap gap-6 text-[10px] font-black uppercase tracking-widest text-gray-400">
                                                <div className="flex items-center gap-2">
                                                    <FaBuilding className="text-blue-600/40" /> {v.location_en}
                                                </div>
                                                <div className="flex items-center gap-2">
                                                    <FaCalendarAlt className="text-blue-600/40" /> Deadline: <b className="text-gray-900">{new Date(v.deadline).toLocaleDateString(undefined, { dateStyle: 'long' })}</b>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="flex-shrink-0 flex flex-col sm:flex-row gap-3">
                                            <Link
                                                href={`/vacancies/${v.id}`}
                                                className="bg-white text-blue-900 border-2 border-blue-900 px-8 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest flex items-center gap-3 transition-all hover:bg-blue-50"
                                            >
                                                View Detail
                                            </Link>
                                            <span className="bg-blue-900 text-white px-8 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest flex items-center gap-3 shadow-xl group-hover:bg-[#f5a623] group-hover:text-blue-900 transition-all group-active:scale-95">
                                                Apply Now <FaChevronRight className="text-[8px]" />
                                            </span>
                                        </div>
                                    </div>
                                    {/* Hover indicator */}
                                    <div className="absolute left-0 top-0 bottom-0 w-2 bg-blue-600 -translate-x-full group-hover:translate-x-0 transition-transform duration-500"></div>
                                </Link>
                            ))
                        ) : (
                            <div className="text-center py-32 bg-white rounded-[3rem] border-2 border-dashed border-gray-100">
                                <div className="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <FaBriefcase className="text-3xl text-gray-200" />
                                </div>
                                <h3 className="text-xl font-black text-gray-900 mb-2 italic tracking-tight">No Vacancies Found</h3>
                                <p className="text-gray-400 text-sm">Join our talent database for future roles.</p>
                            </div>
                        )}
                    </div>

                </div>
            </div>
        </div>
    );
}

