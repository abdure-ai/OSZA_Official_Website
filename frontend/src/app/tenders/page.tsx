'use client';

import { useEffect, useState } from 'react';
import { fetchTenders, Tender, getFileUrl } from '@/lib/api';
import { FaGavel, FaFilePdf, FaDownload, FaCalendarAlt, FaSpinner, FaSearch } from 'react-icons/fa';

export default function TendersPage() {
    const [tenders, setTenders] = useState<Tender[]>([]);
    const [loading, setLoading] = useState(true);
    const [searchTerm, setSearchTerm] = useState('');

    useEffect(() => {
        loadTenders();
    }, []);

    async function loadTenders() {
        setLoading(true);
        const data = await fetchTenders();
        setTenders(data);
        setLoading(false);
    }

    const filteredTenders = tenders.filter(t =>
        t.title_en.toLowerCase().includes(searchTerm.toLowerCase()) ||
        t.ref_number?.toLowerCase().includes(searchTerm.toLowerCase())
    );

    return (
        <div className="bg-gray-50 min-h-screen">
            {/* Header */}
            <div className="bg-blue-900 text-white py-16">
                <div className="container mx-auto px-4 text-center">
                    <h1 className="text-4xl font-bold mb-4 flex items-center justify-center gap-3">
                        <FaGavel className="text-primary-light" /> Tenders & Procurement
                    </h1>
                    <p className="text-blue-100 max-w-2xl mx-auto">
                        Transparent procurement opportunities for vendors and service providers. Access official tender notices and documents here.
                    </p>
                </div>
            </div>

            <div className="container mx-auto px-4 py-12">

                {/* Search & Stats Bar */}
                <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-6 justify-between items-center mb-10">
                    <div className="relative w-full md:w-96">
                        <input
                            type="text"
                            placeholder="Search by title or reference number..."
                            className="w-full pl-12 pr-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                        />
                        <FaSearch className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" />
                    </div>
                    <div className="flex gap-8 text-center bg-gray-50 px-8 py-3 rounded-xl border">
                        <div>
                            <span className="block text-2xl font-bold text-primary">{tenders.filter(t => t.status === 'Open').length}</span>
                            <span className="text-xs text-gray-500 uppercase font-bold tracking-wider">Active Tenders</span>
                        </div>
                        <div className="border-l border-gray-200" />
                        <div>
                            <span className="block text-2xl font-bold text-gray-400">{tenders.filter(t => t.status === 'Closed').length}</span>
                            <span className="text-xs text-gray-500 uppercase font-bold tracking-wider">Closed Recently</span>
                        </div>
                    </div>
                </div>

                {/* Tender List */}
                <div className="space-y-6">
                    {loading ? (
                        <div className="py-20 text-center text-gray-400 bg-white rounded-2xl border">
                            <FaSpinner className="animate-spin text-4xl mx-auto mb-4" />
                            <p>Loading procurement notices...</p>
                        </div>
                    ) : filteredTenders.length > 0 ? (
                        filteredTenders.map((t) => (
                            <div
                                key={t.id}
                                className="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow overflow-hidden group"
                            >
                                <div className="flex flex-col lg:flex-row">
                                    {/* Left Status Bar */}
                                    <div className={`lg:w-2 ${t.status === 'Open' ? 'bg-green-500' :
                                            t.status === 'Closed' ? 'bg-red-500' :
                                                'bg-gray-400'
                                        }`} />

                                    <div className="flex-1 p-6 md:p-8 flex flex-col md:flex-row gap-8 items-start md:items-center">
                                        <div className="flex-1">
                                            <div className="flex items-center gap-3 mb-2">
                                                <span className="font-mono text-sm text-primary font-bold bg-blue-50 px-2 py-0.5 rounded">
                                                    {t.ref_number || 'REF-TBD'}
                                                </span>
                                                <span className={`text-[10px] uppercase font-bold tracking-widest px-2 py-0.5 rounded-full ${t.status === 'Open' ? 'text-green-600 bg-green-50' : 'text-red-500 bg-red-50'
                                                    }`}>
                                                    {t.status}
                                                </span>
                                            </div>
                                            <h2 className="text-xl font-bold text-gray-900 group-hover:text-primary transition-colors mb-4">{t.title_en}</h2>

                                            <div className="flex flex-wrap gap-6 text-sm text-gray-500">
                                                <div className="flex items-center gap-2">
                                                    <FaCalendarAlt className="text-gray-400" />
                                                    <span>Submission Deadline: <b className="text-gray-700">{new Date(t.deadline).toLocaleDateString()}</b></span>
                                                </div>
                                                <div className="flex items-center gap-2">
                                                    <FaCalendarAlt className="text-gray-400" />
                                                    <span>Published: <b className="text-gray-700">{new Date(t.created_at).toLocaleDateString()}</b></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div className="w-full md:w-auto flex flex-col gap-3">
                                            {t.file_url ? (
                                                <a
                                                    href={getFileUrl(t.file_url)}
                                                    target="_blank"
                                                    className="bg-primary text-white px-8 py-3 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-opacity-90 transition transform hover:-translate-y-1 shadow-lg shadow-blue-500/20"
                                                >
                                                    <FaFilePdf /> Download Bid Doc
                                                </a>
                                            ) : (
                                                <button disabled className="bg-gray-100 text-gray-400 px-8 py-3 rounded-xl font-bold cursor-not-allowed">
                                                    Doc Not Available
                                                </button>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ))
                    ) : (
                        <div className="py-20 text-center bg-white rounded-2xl border">
                            <div className="text-gray-200 mb-4">
                                <FaGavel className="text-8xl mx-auto" />
                            </div>
                            <h3 className="text-xl font-bold text-gray-800">No Tenders Found</h3>
                            <p className="text-gray-500 mt-2">There are no active procurement notices matching your search.</p>
                        </div>
                    )}
                </div>

                {/* Info Box */}
                <div className="mt-16 bg-blue-50 p-8 rounded-3xl border border-blue-100 flex flex-col md:flex-row gap-8 items-center">
                    <div className="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-3xl shadow-sm">
                        📞
                    </div>
                    <div className="flex-1 text-center md:text-left">
                        <h4 className="font-bold text-lg text-blue-900 mb-1">Need Clarification?</h4>
                        <p className="text-sm text-blue-700">For any inquiries regarding the procurement process, please contact the procurement office directly at <b>+251 33 111 2223</b> or via email.</p>
                    </div>
                    <a href="/contact" className="bg-white text-primary px-6 py-2 rounded-xl font-bold shadow-sm hover:shadow-md transition">Contact Procurement</a>
                </div>

            </div>
        </div>
    );
}

