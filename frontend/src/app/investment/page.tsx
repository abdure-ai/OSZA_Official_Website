'use client';

import { useState, useEffect, useCallback } from 'react';
import { fetchInvestments, InvestmentOpportunity, getFileUrl } from '@/lib/api';
import { FaCoins, FaMapMarkerAlt, FaFileAlt, FaCheckCircle, FaSearch, FaArrowRight, FaBuilding, FaChartLine, FaLightbulb, FaShieldAlt } from 'react-icons/fa';
import { useTranslation } from 'react-i18next';

const SECTORS = ['All', 'Agriculture', 'Industry', 'Infrastructure', 'Tourism', 'Health', 'Education', 'Technology', 'Livestock'];

export default function InvestmentPage() {
    const { t } = useTranslation();
    const [opportunities, setOpportunities] = useState<InvestmentOpportunity[]>([]);
    const [loading, setLoading] = useState(true);
    const [search, setSearch] = useState('');
    const [category, setCategory] = useState('All');

    const loadInvestments = useCallback(async () => {
        setLoading(true);
        const data = await fetchInvestments(category);
        setOpportunities(data);
        setLoading(false);
    }, [category]);

    useEffect(() => { loadInvestments(); }, [loadInvestments]);

    const filtered = opportunities.filter(o =>
        o.title_en.toLowerCase().includes(search.toLowerCase()) ||
        (o.description_en && o.description_en.toLowerCase().includes(search.toLowerCase())) ||
        (o.location && o.location.toLowerCase().includes(search.toLowerCase()))
    );

    return (
        <div className="bg-gray-50 min-h-screen pb-20">
            {/* ═══════════════════════════════════════════ INVESTMENT HERO ══ */}
            <section className="relative bg-blue-900 text-white py-20 md:py-32 overflow-hidden">
                <div className="absolute inset-0 z-0">
                    <div className="absolute inset-0 bg-gradient-to-r from-blue-950/80 to-blue-900/40 z-10"></div>
                    <img
                        src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070"
                        className="w-full h-full object-cover scale-105 opacity-40"
                        alt="Investment Hero"
                    />
                </div>

                <div className="container mx-auto px-4 relative z-20">
                    <div className="max-w-3xl">
                        <span className="inline-block px-4 py-1.5 bg-[#f5a623] text-blue-900 text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-6 shadow-xl">
                            Economic Potential
                        </span>
                        <h1 className="text-5xl md:text-7xl font-black mb-4 leading-none antialiased drop-shadow-2xl italic tracking-tight">
                            Invest in our <span className="text-[#f5a623]">Future</span>
                        </h1>
                        <p className="text-lg md:text-xl text-gray-200 font-medium opacity-90 max-w-2xl">
                            Discover high-yield opportunities in the Oromo Special Zone. We offer a supportive environment and rich resources for sustainable business growth.
                        </p>
                    </div>
                </div>
                {/* Bottom fade */}
                <div className="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-gray-50 to-transparent"></div>
            </section>

            <div className="container mx-auto px-4 -mt-10 relative z-30">
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-12 max-w-[1440px] mx-auto">

                    {/* Main Content: Opportunities Grid */}
                    <div className="lg:col-span-2 space-y-8">
                        {/* Search & Filter Controls */}
                        <div className="bg-white p-6 md:p-8 rounded-[2.5rem] shadow-2xl border border-gray-100 flex flex-col sm:flex-row gap-6 items-center">
                            <div className="relative flex-1 w-full group">
                                <FaSearch className="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 group-hover:text-blue-600 transition-colors" />
                                <input
                                    type="text"
                                    placeholder="Find opportunities by title or location..."
                                    value={search}
                                    onChange={(e) => setSearch(e.target.value)}
                                    className="w-full pl-14 pr-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all shadow-inner"
                                />
                            </div>
                            <select
                                value={category}
                                onChange={(e) => setCategory(e.target.value)}
                                className="w-full sm:w-auto px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all shadow-inner appearance-none cursor-pointer min-w-[180px]"
                            >
                                {SECTORS.map(s => <option key={s} value={s}>{s === 'All' ? 'All Sectors' : s}</option>)}
                            </select>
                        </div>

                        {/* Inventory Grid */}
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {loading ? (
                                Array.from({ length: 4 }).map((_, i) => (
                                    <div key={i} className="bg-white rounded-[2rem] h-[400px] animate-pulse border border-gray-100 shadow-sm" />
                                ))
                            ) : filtered.length > 0 ? (
                                filtered.map(opp => (
                                    <div key={opp.id} className="bg-white rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden group flex flex-col">
                                        <div className="relative h-52 overflow-hidden">
                                            {opp.thumbnail_url ? (
                                                <img src={getFileUrl(opp.thumbnail_url)} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt={opp.title_en} />
                                            ) : (
                                                <div className="w-full h-full bg-blue-50 flex items-center justify-center text-blue-200">
                                                    <FaBuilding size={48} />
                                                </div>
                                            )}
                                            <div className="absolute top-4 left-4">
                                                <span className="bg-white/90 backdrop-blur-md text-blue-900 px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg">
                                                    {opp.category || 'General'}
                                                </span>
                                            </div>
                                        </div>

                                        <div className="p-8 flex-1 flex flex-col">
                                            <h3 className="text-xl font-black text-gray-900 mb-2 italic tracking-tight group-hover:text-blue-600 transition-colors">
                                                {opp.title_en}
                                            </h3>
                                            <p className="text-sm text-gray-500 line-clamp-2 mb-6 font-medium leading-relaxed">
                                                {opp.description_en}
                                            </p>

                                            <div className="mt-auto pt-6 border-t border-gray-50 flex items-center justify-between">
                                                <span className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                                    Sector: <b className="text-gray-900">{opp.category || 'Mixed'}</b>
                                                </span>
                                                <button className="text-blue-600 font-black text-[10px] uppercase tracking-widest flex items-center gap-2 hover:translate-x-1 transition-transform">
                                                    Details <FaArrowRight size={8} />
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                ))
                            ) : (
                                <div className="col-span-2 py-32 text-center bg-white rounded-[3rem] border-2 border-dashed border-gray-100">
                                    <FaLightbulb className="text-4xl text-gray-200 mx-auto mb-4" />
                                    <h3 className="text-xl font-black text-gray-900 italic tracking-tight">No Opportunities Found</h3>
                                    <p className="text-gray-400 text-sm">Try adjusting your filters or search.</p>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Sidebar: Why Invest & CTA */}
                    <div className="lg:col-span-1 space-y-10">
                        <div className="bg-white p-10 rounded-[2.5rem] shadow-2xl border border-gray-100">
                            <h4 className="text-sm font-black text-blue-950 mb-10 flex items-center gap-3 uppercase tracking-widest">
                                Why Invest Here?
                            </h4>

                            <ul className="space-y-8">
                                {[
                                    { icon: FaMapMarkerAlt, color: 'bg-green-100 text-green-600', title: 'Strategic Location', desc: 'Main transit corridor with access to major regional markets.' },
                                    { icon: FaCoins, color: 'bg-blue-100 text-blue-600', title: 'Rich Resources', desc: 'Abundant natural resources and a fertile environment for agriculture.' },
                                    { icon: FaShieldAlt, color: 'bg-amber-100 text-amber-600', title: 'Bureau Support', desc: 'Dedicated incentives and administrative support for new ventures.' }
                                ].map((item, i) => (
                                    <li key={i} className="flex gap-5 group">
                                        <div className={`w-12 h-12 rounded-2xl ${item.color} flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300 shadow-sm`}>
                                            <item.icon size={20} />
                                        </div>
                                        <div>
                                            <p className="font-black text-sm text-gray-900 italic tracking-tight mb-1">{item.title}</p>
                                            <p className="text-[11px] text-gray-500 font-medium leading-relaxed">{item.desc}</p>
                                        </div>
                                    </li>
                                ))}
                            </ul>
                        </div>

                        <div className="bg-blue-900 text-white p-10 rounded-[2.5rem] shadow-2xl relative overflow-hidden group">
                            <div className="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16 group-hover:scale-110 transition-transform duration-700"></div>
                            <h4 className="text-2xl font-black mb-4 italic tracking-tight relative z-10">Ready to Start?</h4>
                            <p className="text-sm text-blue-100/60 mb-8 font-medium relative z-10 leading-relaxed">
                                Contact our investment bureau for a consultation, site visits, or to request more detailed regional data.
                            </p>
                            <a href="/contact" className="block text-center bg-[#f5a623] text-blue-900 py-5 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:scale-105 transition-all shadow-xl relative z-10 group-active:scale-95">
                                Contact Bureau
                            </a>
                        </div>

                        {/* Contact Info Card */}
                        <div className="bg-gray-900 p-8 rounded-[2rem] text-white">
                            <p className="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Direct Investment Line</p>
                            <p className="text-2xl font-black text-white italic tracking-tight">+251 33 111 2222</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    );
}
