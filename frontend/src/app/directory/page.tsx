'use client';

import { useState, useEffect, useCallback } from 'react';
import { fetchDirectory, DirectoryContact, getFileUrl } from '@/lib/api';
import { FaPhone, FaEnvelope, FaMapMarkerAlt, FaClock, FaSearch, FaUser } from 'react-icons/fa';

const CATEGORIES = ['All', 'Leadership', 'Department', 'Woreda Head', 'Security', 'Health', 'Education', 'Finance'];

export default function DirectoryPage() {
    const [contacts, setContacts] = useState<DirectoryContact[]>([]);
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
        c.position_en.toLowerCase().includes(search.toLowerCase()) ||
        (c.department_en && c.department_en.toLowerCase().includes(search.toLowerCase()))
    );

    return (
        <div className="bg-gray-50 min-h-screen">
            {/* Hero Section */}
            <div className="bg-primary text-white py-16 relative overflow-hidden">
                <div className="absolute inset-0 bg-blue-900 opacity-50"></div>
                <div className="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                <div className="container mx-auto px-4 relative z-10 text-center">
                    <h1 className="text-4xl md:text-5xl font-bold mb-4">Official Contact Directory</h1>
                    <p className="text-xl text-blue-100 max-w-2xl mx-auto">
                        Find contact information for zone leadership, departments, and regional offices.
                    </p>
                </div>
            </div>

            <div className="container mx-auto px-4 py-8 -mt-8 relative z-20">
                {/* Search & Filter Bar */}
                <div className="bg-white rounded-2xl shadow-xl p-4 md:p-6 mb-12 flex flex-col md:flex-row gap-4 items-center">
                    <div className="relative flex-1 w-full">
                        <FaSearch className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" />
                        <input
                            type="text"
                            placeholder="Search by name, position or department..."
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            className="w-full pl-12 pr-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-gray-700"
                        />
                    </div>
                    <div className="flex gap-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0 no-scrollbar">
                        {CATEGORIES.map(cat => (
                            <button
                                key={cat}
                                onClick={() => setCategory(cat)}
                                className={`px-5 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition-all duration-300 ${category === cat
                                        ? 'bg-primary text-white shadow-lg shadow-primary/30'
                                        : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                                    }`}
                            >
                                {cat}
                            </button>
                        ))}
                    </div>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {/* Contacts Grid */}
                    <div className="lg:col-span-2">
                        {loading ? (
                            <div className="flex flex-col items-center justify-center py-20 bg-white rounded-2xl border border-gray-100 italic text-gray-400">
                                <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mb-4"></div>
                                Loading directory...
                            </div>
                        ) : filteredContacts.length === 0 ? (
                            <div className="text-center py-20 bg-white rounded-2xl border border-gray-100">
                                <p className="text-gray-400 italic">No contacts found matching your criteria.</p>
                            </div>
                        ) : (
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {filteredContacts.map(contact => (
                                    <div key={contact.id} className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                                        <div className="flex items-start gap-4 mb-4">
                                            {contact.photo_url ? (
                                                <img
                                                    src={getFileUrl(contact.photo_url)}
                                                    className="w-16 h-16 rounded-2xl object-cover shadow-md border-2 border-white group-hover:scale-110 transition-transform duration-500"
                                                    alt={contact.name_en}
                                                />
                                            ) : (
                                                <div className="w-16 h-16 rounded-2xl bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center text-gray-300 border border-gray-100">
                                                    <FaUser size={24} />
                                                </div>
                                            )}
                                            <div>
                                                <h3 className="font-bold text-gray-900 group-hover:text-primary transition-colors">{contact.name_en}</h3>
                                                <p className="text-xs font-bold text-primary/70 uppercase tracking-widest mt-0.5">{contact.position_en}</p>
                                                {contact.department_en && (
                                                    <p className="text-[10px] text-gray-400 font-medium uppercase mt-1">{contact.department_en}</p>
                                                )}
                                            </div>
                                        </div>

                                        <div className="space-y-2.5 pt-4 border-t border-gray-50">
                                            {contact.phone && (
                                                <a href={`tel:${contact.phone}`} className="flex items-center gap-3 text-sm text-gray-600 hover:text-primary transition-colors group/link">
                                                    <div className="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-green-600 group-hover/link:bg-green-600 group-hover/link:text-white transition-colors">
                                                        <FaPhone size={12} />
                                                    </div>
                                                    <span className="font-medium">{contact.phone}</span>
                                                </a>
                                            )}
                                            {contact.email && (
                                                <a href={`mailto:${contact.email}`} className="flex items-center gap-3 text-sm text-gray-600 hover:text-primary transition-colors group/link">
                                                    <div className="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 group-hover/link:bg-blue-600 group-hover/link:text-white transition-colors">
                                                        <FaEnvelope size={12} />
                                                    </div>
                                                    <span className="font-medium truncate">{contact.email}</span>
                                                </a>
                                            )}
                                            {contact.office_location && (
                                                <div className="flex items-center gap-3 text-sm text-gray-500">
                                                    <div className="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400">
                                                        <FaMapMarkerAlt size={12} />
                                                    </div>
                                                    <span className="line-clamp-1">{contact.office_location}</span>
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>

                    {/* Sidebar info */}
                    <div className="space-y-8">
                        {/* Working Hours */}
                        <div className="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
                            <div className="flex items-center gap-4 mb-6">
                                <div className="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                                    <FaClock size={24} />
                                </div>
                                <h3 className="font-bold text-xl text-gray-800">Working Hours</h3>
                            </div>
                            <ul className="space-y-4 text-sm text-gray-600">
                                <li className="flex justify-between items-center pb-3 border-b border-gray-50">
                                    <span>Monday - Thursday</span>
                                    <span className="font-bold text-gray-900">8:30 AM - 5:30 PM</span>
                                </li>
                                <li className="flex justify-between items-center pb-3 border-b border-gray-50">
                                    <span>Friday</span>
                                    <span className="font-bold text-gray-900">8:30 AM - 11:30 AM</span>
                                </li>
                                <li className="flex justify-between items-center pt-1">
                                    <span>Saturday - Sunday</span>
                                    <span className="font-bold text-red-500 bg-red-50 px-3 py-1 rounded">Closed</span>
                                </li>
                            </ul>
                            <p className="mt-6 text-[11px] text-gray-400 italic">
                                * Offices are closed on public holidays and during prayer times on Friday afternoons.
                            </p>
                        </div>

                        {/* Emergency Numbers */}
                        <div className="bg-gradient-to-br from-red-600 to-red-700 p-8 rounded-2xl shadow-xl shadow-red-200 text-white relative overflow-hidden">
                            <div className="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                            <h3 className="font-bold text-xl mb-6 relative z-10">Emergency Contacts</h3>
                            <ul className="space-y-4 relative z-10">
                                <li className="flex justify-between items-center bg-white/10 p-3 rounded-xl backdrop-blur-sm">
                                    <span className="font-medium">Police</span>
                                    <a href="tel:991" className="bg-white text-red-600 w-12 h-8 flex items-center justify-center rounded-lg font-black hover:scale-110 transition-transform">991</a>
                                </li>
                                <li className="flex justify-between items-center bg-white/10 p-3 rounded-xl backdrop-blur-sm">
                                    <span className="font-medium">Ambulance</span>
                                    <a href="tel:907" className="bg-white text-red-600 w-12 h-8 flex items-center justify-center rounded-lg font-black hover:scale-110 transition-transform">907</a>
                                </li>
                                <li className="flex justify-between items-center bg-white/10 p-3 rounded-xl backdrop-blur-sm">
                                    <span className="font-medium">Fire Brigade</span>
                                    <a href="tel:939" className="bg-white text-red-600 w-12 h-8 flex items-center justify-center rounded-lg font-black hover:scale-110 transition-transform">939</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
