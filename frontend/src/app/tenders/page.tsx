'use client';

import { useEffect, useState } from 'react';
import { fetchTenders, Tender, getFileUrl } from '@/lib/api';
import Link from 'next/link';
import { FaGavel, FaFilePdf, FaDownload, FaCalendarAlt, FaSpinner, FaSearch, FaInfoCircle } from 'react-icons/fa';
import { useTranslation } from 'react-i18next';

export default function TendersPage() {
    const { t } = useTranslation();
    const [tenders, setTenders] = useState<Tender[]>([]);
    const [loading, setLoading] = useState(true);
    const [searchTerm, setSearchTerm] = useState('');
    const [statusFilter, setStatusFilter] = useState('');

    useEffect(() => {
        loadTenders();
    }, []);

    async function loadTenders() {
        setLoading(true);
        const data = await fetchTenders();
        setTenders(data);
        setLoading(false);
    }

    const filteredTenders = tenders.filter(t => {
        const matchesSearch = t.title_en.toLowerCase().includes(searchTerm.toLowerCase()) ||
            (t.ref_number || '').toLowerCase().includes(searchTerm.toLowerCase());
        const matchesStatus = statusFilter === '' || t.status.toLowerCase() === statusFilter.toLowerCase();
        return matchesSearch && matchesStatus;
    });

    return (
        <div className="bg-gray-50 min-h-screen pb-20">
            {/* ═══════════════════════════════════════════ TENDERS HERO ══ */}
            <section className="relative bg-blue-900 text-white py-20 md:py-32 overflow-hidden">
                <div className="absolute inset-0 z-0">
                    <div className="absolute inset-0 bg-gradient-to-r from-blue-950/80 to-blue-900/40 z-10"></div>
                    <img
                        src="https://images.unsplash.com/photo-1573164713988-8cdad5d97ec7?q=80&w=1920&auto=format&fit=crop"
                        className="w-full h-full object-cover scale-105 opacity-60"
                        alt="Tenders Hero"
                    />
                </div>

                <div className="container mx-auto px-4 relative z-20">
                    <div className="max-w-3xl">
                        <span className="inline-block px-4 py-1.5 bg-[#f5a623] text-blue-900 text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-6 shadow-xl">
                            Procurement
                        </span>
                        <h1 className="text-5xl md:text-7xl font-black mb-4 leading-none antialiased drop-shadow-2xl italic tracking-tight">
                            Latest <span className="text-[#f5a623]">Tenders</span>
                        </h1>
                        <p className="text-lg md:text-xl text-gray-200 font-medium opacity-90">
                            Transparent opportunities for business and community project partnerships.
                        </p>
                    </div>
                </div>
                {/* Bottom fade */}
                <div className="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-gray-50 to-transparent"></div>
            </section>

            <div className="container mx-auto px-4 -mt-10 relative z-30">
                {/* Procurement Dashboard Controls */}
                <div className="max-w-6xl mx-auto mb-16">
                    <div className="bg-white p-6 md:p-8 rounded-[2.5rem] shadow-2xl border border-gray-100 flex flex-col lg:flex-row gap-8 items-center">
                        <div className="relative flex-1 w-full group">
                            <FaSearch className="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 group-hover:text-blue-600 transition-colors" />
                            <input
                                type="text"
                                placeholder="Search by title or reference number..."
                                value={searchTerm}
                                onChange={(e) => setSearchTerm(e.target.value)}
                                className="w-full pl-14 pr-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all shadow-inner"
                            />
                        </div>

                        <div className="flex flex-wrap items-center gap-4 w-full lg:w-auto">
                            <select
                                value={statusFilter}
                                onChange={(e) => setStatusFilter(e.target.value)}
                                className="px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all shadow-inner appearance-none cursor-pointer min-w-[160px]"
                            >
                                <option value="">All Status</option>
                                <option value="open">Open</option>
                                <option value="closed">Closed</option>
                            </select>

                            <div className="bg-blue-900 text-white px-8 py-4 rounded-2xl flex items-center gap-6 shadow-xl">
                                <div className="text-center">
                                    <span className="block text-xl font-black text-[#f5a623]">{tenders.filter(t => t.status.toLowerCase() === 'open').length}</span>
                                    <span className="text-[8px] font-black uppercase tracking-widest opacity-60">Open</span>
                                </div>
                                <div className="w-px h-8 bg-white/10" />
                                <div className="text-center">
                                    <span className="block text-xl font-black text-white/40">{tenders.filter(t => t.status.toLowerCase() !== 'open').length}</span>
                                    <span className="text-[8px] font-black uppercase tracking-widest opacity-40">Closed</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Tender List */}
                <div className="max-w-6xl mx-auto space-y-6">
                    {loading ? (
                        <div className="py-32 text-center bg-white rounded-[3rem] border-2 border-dashed border-gray-100 italic font-bold text-gray-400 animate-pulse">
                            Loading Procurement Notices...
                        </div>
                    ) : filteredTenders.length > 0 ? (
                        filteredTenders.map((tender) => (
                            <div
                                key={tender.id}
                                className="bg-white rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden group flex flex-col md:flex-row items-center p-4 md:p-6 gap-6"
                            >
                                <div className="flex-1 w-full">
                                    <div className="flex flex-wrap items-center gap-4 mb-4">
                                        <span className={`px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest ${tender.status.toLowerCase() === 'open'
                                            ? 'bg-green-100 text-green-700 shadow-lg shadow-green-100'
                                            : 'bg-gray-100 text-gray-500'
                                            }`}>
                                            {tender.status}
                                        </span>
                                        {tender.ref_number && (
                                            <span className="text-[10px] font-black uppercase tracking-widest text-gray-400 bg-gray-50 px-3 py-1 rounded-lg">
                                                REF: {tender.ref_number}
                                            </span>
                                        )}
                                    </div>
                                    <h2 className="text-xl font-black text-gray-900 group-hover:text-blue-600 transition-colors mb-4 italic tracking-tight">
                                        {tender.title_en}
                                    </h2>
                                    <div className="flex flex-wrap gap-6 text-[10px] font-black uppercase tracking-widest text-gray-400">
                                        <div className="flex items-center gap-2">
                                            <FaCalendarAlt className="text-blue-600/40" />
                                            <span>Deadline: <b className="text-gray-900">{new Date(tender.deadline).toLocaleDateString(undefined, { dateStyle: 'long' })}</b></span>
                                        </div>
                                    </div>
                                </div>

                                <div className="w-full md:w-auto flex flex-col sm:flex-row md:flex-col gap-3 min-w-[200px]">
                                    <Link
                                        href={`/tenders/${tender.id}`}
                                        className="bg-white text-blue-900 border-2 border-blue-900 px-8 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-blue-50 transition-all group-active:scale-95 shadow-sm"
                                    >
                                        View Detail
                                    </Link>
                                    {tender.file_url ? (
                                        <a
                                            href={getFileUrl(tender.file_url)}
                                            target="_blank"
                                            className="bg-blue-900 text-white px-8 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-[#f5a623] hover:text-blue-900 transition-all shadow-xl group-active:scale-95"
                                        >
                                            <FaDownload size={14} /> Download
                                        </a>
                                    ) : (
                                        <div className="px-8 py-4 bg-gray-50 text-gray-400 rounded-2xl font-black text-[10px] uppercase tracking-widest text-center border-2 border-dashed border-gray-200">
                                            No Document
                                        </div>
                                    )}
                                </div>
                            </div>
                        ))
                    ) : (
                        <div className="text-center py-32 bg-white rounded-[3rem] border-2 border-dashed border-gray-100">
                            <div className="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <FaGavel className="text-3xl text-gray-200" />
                            </div>
                            <h3 className="text-xl font-black text-gray-900 mb-2 italic tracking-tight">No Tenders Found</h3>
                            <p className="text-gray-400 text-sm">Try adjusting your search or filters.</p>
                        </div>
                    )}
                </div>

                {/* Support Box */}
                <div className="max-w-6xl mx-auto mt-20">
                    <div className="bg-blue-50/50 backdrop-blur-md rounded-[3rem] p-10 md:p-12 border border-white flex flex-col lg:flex-row items-center gap-10">
                        <div className="w-20 h-20 bg-[#f5a623] rounded-[2rem] flex items-center justify-center text-3xl shadow-2xl text-blue-900 rotate-3 group-hover:rotate-0 transition-transform">
                            📞
                        </div>
                        <div className="flex-1 text-center lg:text-left">
                            <h4 className="text-2xl font-black text-blue-950 mb-3 italic tracking-tight">Need Clarification?</h4>
                            <p className="text-blue-900/60 font-medium leading-relaxed max-w-xl">
                                For inquiries regarding the procurement process, contact the office directly at <b className="text-blue-950">+251 33 111 2223</b> or visit us during business hours.
                            </p>
                        </div>
                        <a href="/contact" className="bg-blue-900 text-white px-10 py-5 rounded-[2rem] font-black text-[10px] uppercase tracking-[0.2em] shadow-2xl hover:bg-[#f5a623] hover:text-blue-900 transition-all hover:scale-105">
                            Contact Office
                        </a>
                    </div>
                </div>
            </div>
        </div>
    );
}

