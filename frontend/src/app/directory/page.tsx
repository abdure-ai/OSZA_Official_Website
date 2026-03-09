'use client';

import { useState, useEffect, useCallback } from 'react';
import { fetchDirectory, DirectoryItem, getFileUrl } from '@/lib/api';
import { FaPhone, FaEnvelope, FaMapMarkerAlt, FaClock, FaSearch, FaUser, FaShieldAlt, FaAmbulance, FaFireExtinguisher } from 'react-icons/fa';

const CATEGORIES = ['All', 'Leadership', 'Department', 'Woreda Head', 'Security', 'Health', 'Education', 'Finance'];

export default function DirectoryPage() {
    const [contacts, setContacts] = useState<DirectoryItem[]>([]);
    const [loading, setLoading] = useState(true);
    const [search, setSearch] = useState('');
    const [category, setCategory] = useState('All');

    const loadContacts = useCallback(async () => {
        setLoading(true);
        const data = await fetchDirectory(category);
        setContacts(data);
        setLoading(false);
    }, [category]);

    useEffect(() => { loadContacts(); }, [loadContacts]);

    const filteredContacts = contacts.filter(c =>
        c.name_en.toLowerCase().includes(search.toLowerCase()) ||
        (c.position_en && c.position_en.toLowerCase().includes(search.toLowerCase())) ||
        (c.department_en && c.department_en.toLowerCase().includes(search.toLowerCase()))
    );

    // Grouping by department
    const groupedContacts = filteredContacts.reduce((acc: { [key: string]: DirectoryItem[] }, contact) => {
        const dept = contact.department_en || 'General Administration';
        if (!acc[dept]) acc[dept] = [];
        acc[dept].push(contact);
        return acc;
    }, {});

    return (
        <div className="bg-gray-50 min-h-screen pb-20">
            {/* ═══════════════════════════════════════════ DIRECTORY HERO ══ */}
            <section className="relative bg-[#1a56db] text-white py-20 overflow-hidden">
                <div className="absolute inset-0 z-0">
                    <div className="absolute inset-0 bg-blue-900/50"></div>
                </div>
                <div className="container mx-auto px-4 relative z-10">
                    <div className="max-w-3xl">
                        <span className="inline-block px-4 py-1.5 bg-[#f5a623] text-blue-900 text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-6 shadow-xl">
                            Staff & Departments
                        </span>
                        <h1 className="text-5xl md:text-7xl font-black mb-4 leading-none antialiased drop-shadow-2xl italic tracking-tight">
                            Official <span className="text-[#f5a623]">Directory</span>
                        </h1>
                        <p className="text-lg md:text-xl text-blue-100 font-medium opacity-90 max-w-2xl">
                            Access contact information for the Oromo Special Zone leadership, administration departments, and regional offices.
                        </p>
                    </div>
                </div>
                {/* Bottom fade */}
                <div className="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-gray-50 to-transparent"></div>
            </section>

            <div className="container mx-auto px-4 -mt-10 relative z-30">
                <div className="grid grid-cols-1 lg:grid-cols-4 gap-12 max-w-[1440px] mx-auto">

                    {/* Main Content: Directory Listing */}
                    <div className="lg:col-span-3 space-y-12">
                        {/* Search & Filter Controls */}
                        <div className="bg-white p-6 md:p-8 rounded-[2.5rem] shadow-2xl border border-gray-100 flex flex-col sm:row gap-6 items-center">
                            <div className="relative flex-1 w-full group">
                                <FaSearch className="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 group-hover:text-blue-600 transition-colors" />
                                <input
                                    type="text"
                                    placeholder="Search by name, position, or department..."
                                    value={search}
                                    onChange={(e) => setSearch(e.target.value)}
                                    className="w-full pl-14 pr-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all shadow-inner"
                                />
                            </div>
                            <div className="flex gap-2 overflow-x-auto w-full no-scrollbar">
                                {CATEGORIES.map(cat => (
                                    <button
                                        key={cat}
                                        onClick={() => setCategory(cat)}
                                        className={`px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest whitespace-nowrap transition-all ${category === cat
                                            ? 'bg-blue-600 text-white shadow-lg shadow-blue-200'
                                            : 'bg-gray-50 text-gray-500 hover:bg-gray-100'
                                            }`}
                                    >
                                        {cat}
                                    </button>
                                ))}
                            </div>
                        </div>

                        {loading ? (
                            <div className="py-32 text-center bg-white rounded-[3rem] border border-gray-100">
                                <div className="animate-spin rounded-full h-12 w-12 border-b-4 border-blue-600 mx-auto mb-6"></div>
                                <p className="text-gray-400 font-bold italic tracking-tight">Accessing records...</p>
                            </div>
                        ) : Object.keys(groupedContacts).length > 0 ? (
                            Object.entries(groupedContacts).map(([dept, members]) => (
                                <div key={dept} className="space-y-6">
                                    <div className="flex items-center gap-4 px-2">
                                        <div className="h-px flex-1 bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
                                        <h2 className="text-sm font-black text-gray-400 uppercase tracking-[0.3em] italic">{dept}</h2>
                                        <div className="h-px flex-1 bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
                                    </div>

                                    <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                        {members.map(contact => (
                                            <div key={contact.id} className="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm hover:shadow-2xl transition-all duration-500 group">
                                                <div className="flex items-start gap-4 mb-6">
                                                    {contact.photo_url ? (
                                                        <img
                                                            src={getFileUrl(contact.photo_url)}
                                                            className="w-14 h-14 rounded-2xl object-cover shadow-lg border-2 border-white group-hover:scale-110 transition-transform duration-500"
                                                            alt={contact.name_en}
                                                        />
                                                    ) : (
                                                        <div className="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-300 border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition-all duration-500">
                                                            <FaUser size={20} />
                                                        </div>
                                                    )}
                                                    <div className="min-w-0">
                                                        <h3 className="font-black text-gray-900 text-sm truncate group-hover:text-blue-600 transition-colors uppercase tracking-tight">{contact.name_en}</h3>
                                                        <p className="text-[10px] font-black text-blue-600 uppercase tracking-widest mt-1 italic">{contact.position_en}</p>
                                                    </div>
                                                </div>

                                                <div className="space-y-3 pt-4 border-t border-gray-50">
                                                    {contact.phone && (
                                                        <a href={`tel:${contact.phone}`} className="flex items-center gap-3 text-xs text-gray-500 hover:text-blue-600 font-bold transition-colors">
                                                            <div className="w-8 h-8 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:text-blue-600 group-hover:bg-blue-50">
                                                                <FaPhone size={10} />
                                                            </div>
                                                            {contact.phone}
                                                        </a>
                                                    )}
                                                    {contact.email && (
                                                        <a href={`mailto:${contact.email}`} className="flex items-center gap-3 text-xs text-gray-500 hover:text-blue-600 font-bold transition-colors">
                                                            <div className="w-8 h-8 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:text-blue-600 group-hover:bg-blue-50">
                                                                <FaEnvelope size={10} />
                                                            </div>
                                                            <span className="truncate">{contact.email}</span>
                                                        </a>
                                                    )}
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            ))
                        ) : (
                            <div className="py-32 text-center bg-white rounded-[3rem] border-2 border-dashed border-gray-100">
                                <FaUser className="text-4xl text-gray-200 mx-auto mb-4" />
                                <h3 className="text-xl font-black text-gray-900 italic tracking-tight">No Results Found</h3>
                                <p className="text-gray-400 text-sm">No directory records match your current search criteria.</p>
                            </div>
                        )}
                    </div>

                    {/* Sidebar: Hours & Emergencies */}
                    <div className="lg:col-span-1 space-y-10">
                        {/* Working Hours */}
                        <div className="bg-white p-8 rounded-[2.5rem] shadow-2xl border border-gray-100">
                            <h4 className="text-xs font-black text-blue-950 mb-8 uppercase tracking-widest flex items-center gap-3">
                                <FaClock /> Working Hours
                            </h4>
                            <div className="space-y-4">
                                {[
                                    { day: 'Mon - Thu', hours: '8:30 AM - 5:30 PM' },
                                    { day: 'Friday', hours: '8:30 AM - 11:30 AM' },
                                    { day: 'Sat - Sun', hours: 'Closed', highlight: true }
                                ].map((item, i) => (
                                    <div key={i} className="flex justify-between items-center py-3 border-b border-gray-50 last:border-0">
                                        <span className="text-[11px] font-bold text-gray-400 uppercase tracking-widest">{item.day}</span>
                                        <span className={`text-xs font-black ${item.highlight ? 'text-red-500' : 'text-gray-900'} uppercase`}>{item.hours}</span>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* Emergency Numbers */}
                        <div className="bg-blue-900 text-white p-10 rounded-[2.5rem] shadow-2xl relative overflow-hidden group">
                            <div className="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16 group-hover:scale-110 transition-transform duration-700"></div>
                            <h4 className="text-xs font-black mb-10 italic tracking-widest relative z-10 flex items-center gap-3">
                                <FaShieldAlt /> Emergency Desk
                            </h4>

                            <ul className="space-y-6 relative z-10">
                                {[
                                    { label: 'Police', icon: FaShieldAlt, phone: '991' },
                                    { label: 'Ambulance', icon: FaAmbulance, phone: '907' },
                                    { label: 'Fire', icon: FaFireExtinguisher, phone: '939' }
                                ].map((e, i) => (
                                    <li key={i} className="flex justify-between items-center group/btn">
                                        <span className="text-[11px] font-black uppercase tracking-widest opacity-60 italic">{e.label}</span>
                                        <a href={`tel:${e.phone}`} className="h-10 px-6 bg-white text-blue-900 rounded-xl flex items-center justify-center font-black text-xs hover:scale-110 transition-transform shadow-xl">
                                            {e.phone}
                                        </a>
                                    </li>
                                ))}
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    );
}
