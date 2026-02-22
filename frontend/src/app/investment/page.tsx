'use client';

import { useState, useEffect, useCallback } from 'react';
import { fetchInvestments, InvestmentOpportunity, getFileUrl } from '@/lib/api';
import { FaCoins, FaMapMarkerAlt, FaFileAlt, FaCheckCircle, FaSearch, FaArrowRight, FaBuilding, FaChartLine } from 'react-icons/fa';

const SECTORS = ['All', 'Agriculture', 'Industry', 'Infrastructure', 'Tourism', 'Health', 'Education', 'Technology', 'Livestock'];

export default function InvestmentPage() {
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
        <div className="bg-white min-h-screen pb-20">
            {/* Hero Section */}
            <div className="relative bg-blue-900 h-[500px] flex items-center overflow-hidden">
                <div className="absolute inset-0 z-0">
                    <img
                        src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070"
                        className="w-full h-full object-cover opacity-30"
                        alt="Investment Hero"
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-blue-900 via-transparent to-transparent"></div>
                </div>

                <div className="container mx-auto px-4 relative z-10">
                    <div className="max-w-3xl">
                        <span className="bg-primary/20 text-primary-light border border-primary/30 px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest mb-6 inline-block">
                            Economic Growth & Prosperity
                        </span>
                        <h1 className="text-5xl md:text-7xl font-black text-white mb-6 leading-tight">
                            Invest in Oromo <br /> <span className="text-primary-light">Special Zone</span>
                        </h1>
                        <p className="text-xl text-blue-100 mb-8 leading-relaxed">
                            Discover high-yield opportunities in Agriculture, Industry, and Tourism. Join us in building a sustainable and prosperous future.
                        </p>
                        <div className="flex flex-wrap gap-4">
                            <button className="bg-primary text-white px-8 py-4 rounded-xl font-bold hover:bg-primary/90 transition-all shadow-xl shadow-primary/20 flex items-center gap-2">
                                Explore Sectors <FaArrowRight />
                            </button>
                            <button className="bg-white/10 backdrop-blur-md border border-white/20 text-white px-8 py-4 rounded-xl font-bold hover:bg-white/20 transition-all">
                                Investment Guide
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {/* Why Invest Stats */}
            <div className="container mx-auto px-4 -mt-16 relative z-20">
                <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                    {[
                        { icon: FaBuilding, label: "Growing Economy", val: "8.5%", sub: "Annual GDP Growth" },
                        { icon: FaMapMarkerAlt, label: "Strategic Location", val: "Main Highway", sub: "Addis - Desse Corridor" },
                        { icon: FaCoins, label: "Incentives", val: "Tax-Free", sub: "Up to 5 Years" },
                        { icon: FaChartLine, label: "Market Size", val: "1.2M+", sub: "Regional Consumers" }
                    ].map((s, i) => (
                        <div key={i} className="bg-white p-6 rounded-2xl shadow-xl border border-gray-50 flex items-center gap-4">
                            <div className="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                                <s.icon size={24} />
                            </div>
                            <div>
                                <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{s.label}</p>
                                <p className="text-xl font-black text-gray-900">{s.val}</p>
                                <p className="text-xs text-gray-500">{s.sub}</p>
                            </div>
                        </div>
                    ))}
                </div>
            </div>

            <div className="container mx-auto px-4 mt-20">
                <div className="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
                    <div>
                        <h2 className="text-3xl font-black text-gray-900 mb-2">Available Opportunities</h2>
                        <div className="h-1.5 w-24 bg-primary rounded-full"></div>
                    </div>

                    <div className="flex flex-col md:flex-row gap-4 w-full md:w-auto">
                        <div className="relative">
                            <FaSearch className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" />
                            <input
                                type="text"
                                placeholder="Search project title..."
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                className="pl-12 pr-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 outline-none w-full md:w-64"
                            />
                        </div>
                    </div>
                </div>

                {/* Filter Tabs */}
                <div className="flex gap-2 overflow-x-auto pb-6 no-scrollbar mb-8">
                    {SECTORS.map(s => (
                        <button
                            key={s}
                            onClick={() => setCategory(s)}
                            className={`px-6 py-2.5 rounded-xl text-sm font-bold transition-all ${category === s
                                    ? 'bg-primary text-white shadow-lg shadow-primary/30'
                                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                                }`}
                        >
                            {s}
                        </button>
                    ))}
                </div>

                {/* Opportunities Grid */}
                {loading ? (
                    <div className="py-20 text-center text-gray-400 italic">Exploring the zone for opportunities...</div>
                ) : filtered.length === 0 ? (
                    <div className="py-20 text-center bg-gray-50 rounded-3xl border border-dashed text-gray-400">
                        No investment opportunities found in this sector yet.
                    </div>
                ) : (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        {filtered.map(opp => (
                            <div key={opp.id} className="group flex flex-col bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                                <div className="relative h-56 overflow-hidden">
                                    {opp.thumbnail_url ? (
                                        <img src={getFileUrl(opp.thumbnail_url)} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt={opp.title_en} />
                                    ) : (
                                        <div className="w-full h-full bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center text-blue-200">
                                            <FaBuilding size={48} />
                                        </div>
                                    )}
                                    <div className="absolute top-4 left-4">
                                        <span className="bg-white/90 backdrop-blur-md text-primary px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                            {opp.category}
                                        </span>
                                    </div>
                                    {opp.status === 'Open' && (
                                        <div className="absolute top-4 right-4">
                                            <span className="bg-green-500 text-white px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider animate-pulse flex items-center gap-1">
                                                <FaCheckCircle /> Accepting Bids
                                            </span>
                                        </div>
                                    )}
                                </div>

                                <div className="p-8 flex-1 flex flex-col">
                                    <h3 className="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary transition-colors leading-tight">
                                        {opp.title_en}
                                    </h3>
                                    <p className="text-sm text-gray-500 line-clamp-3 mb-6 flex-1">
                                        {opp.description_en}
                                    </p>

                                    <div className="grid grid-cols-2 gap-4 mb-6">
                                        <div className="bg-gray-50 p-3 rounded-2xl">
                                            <p className="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Budget</p>
                                            <p className="text-sm font-black text-gray-800">{opp.budget || 'Negotiable'}</p>
                                        </div>
                                        <div className="bg-gray-50 p-3 rounded-2xl">
                                            <p className="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Location</p>
                                            <p className="text-sm font-black text-gray-800 line-clamp-1">{opp.location || 'Various'}</p>
                                        </div>
                                    </div>

                                    <button className="w-full py-4 bg-gray-900 text-white rounded-2xl font-bold text-sm group-hover:bg-primary transition-all duration-300 flex items-center justify-center gap-2 shadow-lg shadow-gray-200 group-hover:shadow-primary/30">
                                        View Full Details <FaArrowRight size={12} />
                                    </button>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </div>

            {/* Newsletter/Contact Section */}
            <div className="container mx-auto px-4 mt-24">
                <div className="bg-primary rounded-[3rem] p-12 md:p-20 text-white flex flex-col lg:flex-row items-center gap-12 relative overflow-hidden">
                    <div className="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
                    <div className="flex-1 relative z-10">
                        <h2 className="text-4xl font-black mb-6">Ready to Invest?</h2>
                        <p className="text-xl text-blue-100 mb-8">
                            Our team is ready to provide you with all the necessary data, legal support, and site visits to make your investment a success.
                        </p>
                        <div className="flex flex-col sm:flex-row gap-8">
                            <div>
                                <p className="text-xs font-bold text-blue-200 uppercase tracking-widest mb-2">Direct Line</p>
                                <p className="text-2xl font-black">+251 33 111 2222</p>
                            </div>
                            <div>
                                <p className="text-xs font-bold text-blue-200 uppercase tracking-widest mb-2">Office Email</p>
                                <p className="text-2xl font-black underline underline-offset-8 decoration-2 decoration-primary-light">investment@osza.gov.et</p>
                            </div>
                        </div>
                    </div>
                    <div className="w-full lg:w-1/3 relative z-10">
                        <div className="bg-white/10 backdrop-blur-xl p-8 rounded-3xl border border-white/20">
                            <h4 className="font-bold text-xl mb-4 text-center">Request Data Pack</h4>
                            <div className="space-y-4">
                                <input type="text" placeholder="Full Name" className="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 outline-none focus:bg-white/20" />
                                <input type="email" placeholder="Email Address" className="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 outline-none focus:bg-white/20" />
                                <select className="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 outline-none focus:bg-white/20 text-white/60 appearance-none">
                                    <option>Select Sector of Interest</option>
                                    {SECTORS.map(s => <option key={s} className="text-gray-900">{s}</option>)}
                                </select>
                                <button className="w-full py-4 bg-white text-primary rounded-xl font-bold hover:bg-blue-50 transition-all">
                                    Get Investment Pack
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
