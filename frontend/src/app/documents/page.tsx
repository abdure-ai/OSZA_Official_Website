'use client';

import { FaFilePdf, FaDownload, FaSearch, FaFileWord, FaFileImage, FaFile, FaBookOpen, FaLanguage, FaFileAlt } from 'react-icons/fa';
import { useState, useEffect } from 'react';
import { fetchDocuments, DocumentItem, getFileUrl } from '@/lib/api';
import { useTranslation } from 'react-i18next';

export default function DocumentsPage() {
    const { t } = useTranslation();
    const [documents, setDocuments] = useState<DocumentItem[]>([]);
    const [category, setCategory] = useState("All Categories");
    const [search, setSearch] = useState("");
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const loadDocs = async () => {
            setLoading(true);
            const data = await fetchDocuments(category === "All Categories" ? "" : category, search);
            setDocuments(data);
            setLoading(false);
        };

        const timeoutId = setTimeout(() => {
            loadDocs();
        }, 300);

        return () => clearTimeout(timeoutId);
    }, [category, search]);

    const getIcon = (type: string) => {
        if (type?.toLowerCase() === 'pdf') return <FaFilePdf className="text-red-500" />;
        if (['doc', 'docx'].includes(type?.toLowerCase())) return <FaFileWord className="text-blue-500" />;
        if (['jpg', 'jpeg', 'png'].includes(type?.toLowerCase())) return <FaFileImage className="text-green-500" />;
        return <FaFile className="text-gray-500" />;
    };

    return (
        <div className="bg-gray-50 min-h-screen pb-20">
            {/* ═══════════════════════════════════════════ DOCUMENTS HERO ══ */}
            <section className="relative bg-blue-900 text-white py-20 md:py-32 overflow-hidden">
                <div className="absolute inset-0 z-0">
                    <div className="absolute inset-0 bg-gradient-to-r from-blue-950/80 to-blue-900/40 z-10"></div>
                    <img
                        src="https://images.unsplash.com/photo-1544377193-33dcf4d68fb5?q=80&w=1920&auto=format&fit=crop"
                        className="w-full h-full object-cover scale-105 opacity-60"
                        alt="Documents Hero"
                    />
                </div>

                <div className="container mx-auto px-4 relative z-20">
                    <div className="max-w-3xl">
                        <span className="inline-block px-4 py-1.5 bg-[#f5a623] text-blue-900 text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-6 shadow-xl">
                            Public Archive
                        </span>
                        <h1 className="text-5xl md:text-7xl font-black mb-4 leading-none antialiased drop-shadow-2xl italic tracking-tight">
                            Digital <span className="text-[#f5a623]">Library</span>
                        </h1>
                        <p className="text-lg md:text-xl text-gray-200 font-medium opacity-90">
                            Access official publications, legislative reports, and development policy documents.
                        </p>
                    </div>
                </div>
                {/* Bottom fade */}
                <div className="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-gray-50 to-transparent"></div>
            </section>

            <div className="container mx-auto px-4 -mt-10 relative z-30">
                {/* Search & Filter Bar Refined */}
                <div className="max-w-6xl mx-auto mb-16">
                    <div className="bg-white p-6 md:p-8 rounded-[2.5rem] shadow-2xl border border-gray-100 flex flex-col md:flex-row gap-6 items-center">
                        <div className="relative flex-1 w-full group">
                            <FaSearch className="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 group-hover:text-blue-600 transition-colors" />
                            <input
                                type="text"
                                placeholder="Search by title, author or keyword..."
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                className="w-full pl-14 pr-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all shadow-inner"
                            />
                        </div>
                        <div className="w-full md:w-72">
                            <select
                                value={category}
                                onChange={(e) => setCategory(e.target.value)}
                                className="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all shadow-inner appearance-none cursor-pointer"
                            >
                                <option>All Categories</option>
                                <option>Planning</option>
                                <option>Finance</option>
                                <option>Education</option>
                                <option>Health</option>
                                <option>Legal</option>
                                <option>Policy</option>
                                <option>Report</option>
                            </select>
                        </div>
                    </div>
                </div>

                {/* Grid */}
                <div className="max-w-6xl mx-auto">
                    {loading ? (
                        <div className="grid grid-cols-2 lg:grid-cols-4 gap-8">
                            {[1, 2, 3, 4].map(i => (
                                <div key={i} className="animate-pulse">
                                    <div className="bg-gray-200 aspect-[3/4] rounded-[2rem] mb-6" />
                                    <div className="h-4 bg-gray-200 rounded w-3/4 mb-2" />
                                    <div className="h-4 bg-gray-200 rounded w-1/2" />
                                </div>
                            ))}
                        </div>
                    ) : documents.length > 0 ? (
                        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-8">
                            {documents.map((doc) => (
                                <div key={doc.id} className="group flex flex-col">
                                    {/* Book Card */}
                                    <div className="relative aspect-[3/4] bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden group-hover:shadow-2xl group-hover:-translate-y-2 transition-all duration-500">
                                        {doc.cover_image_url ? (
                                            <img
                                                src={getFileUrl(doc.cover_image_url)}
                                                alt={doc.title_en}
                                                className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                            />
                                        ) : (
                                            <div className="w-full h-full bg-gradient-to-br from-blue-50 to-indigo-50 flex flex-col items-center justify-center p-6 text-center group-hover:from-indigo-100 transition-colors">
                                                <div className="text-5xl mb-6 opacity-40 filter drop-shadow-xl group-hover:scale-125 transition-transform">
                                                    {getIcon(doc.file_type)}
                                                </div>
                                                <span className="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-400 group-hover:text-indigo-600 transition-colors">
                                                    Official {doc.file_type?.toUpperCase() || 'Digital'} Resource
                                                </span>
                                                <div className="absolute inset-0 border-8 border-white/30 rounded-[2rem] pointer-events-none"></div>
                                            </div>
                                        )}

                                        {/* Overlay Type Tag */}
                                        <div className="absolute top-4 right-4 bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl z-20">
                                            {doc.file_type}
                                        </div>

                                        {/* Hover Quick Actions */}
                                        <div className="absolute inset-0 bg-blue-900/60 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center transition-all duration-500 p-6 text-center backdrop-blur-sm z-30">
                                            <div className="flex flex-col gap-3 w-full max-w-[160px]">
                                                <a
                                                    href={getFileUrl(doc.file_url)}
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    className="bg-white text-blue-900 px-6 py-3 rounded-full font-black text-[10px] uppercase tracking-widest shadow-2xl transform translate-y-8 group-hover:translate-y-0 transition-all duration-500 flex items-center justify-center gap-2 hover:bg-gray-100 active:scale-95"
                                                >
                                                    <FaBookOpen size={12} /> Read Online
                                                </a>
                                                <a
                                                    href={getFileUrl(doc.file_url)}
                                                    download
                                                    className="bg-[#f5a623] text-blue-900 px-6 py-3 rounded-full font-black text-[10px] uppercase tracking-widest shadow-2xl transform translate-y-12 group-hover:translate-y-0 transition-all duration-700 flex items-center justify-center gap-2 hover:scale-105 active:scale-95"
                                                >
                                                    <FaDownload size={12} /> Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    {/* Info */}
                                    <div className="mt-6 flex flex-col flex-grow">
                                        <span className="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mb-2 block">
                                            {doc.category}
                                        </span>
                                        <h3 className="font-bold text-gray-900 leading-tight line-clamp-2 group-hover:text-blue-600 transition-colors cursor-default mb-2">
                                            {doc.title_en}
                                        </h3>
                                        <p className="text-xs text-gray-400 line-clamp-1 italic font-medium">
                                            {doc.author || 'Oromo Special Zone Administration'}
                                        </p>

                                        {/* Metadata */}
                                        <div className="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                                            <span className="flex items-center gap-1.5">
                                                <FaFileAlt className="text-blue-600/30" /> {doc.pages ? `${doc.pages} pages` : 'Document'}
                                            </span>
                                            <span className="flex items-center gap-1.5">
                                                <FaLanguage className="text-blue-600/30" /> {doc.language || 'English'}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <div className="text-center py-32 bg-white rounded-[3rem] border-2 border-dashed border-gray-100">
                            <div className="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <FaBookOpen className="text-3xl text-gray-200" />
                            </div>
                            <h3 className="text-xl font-black text-gray-900 mb-2 italic tracking-tight">Archives Empty</h3>
                            <p className="text-gray-400 text-sm">Try adjusting your search or category filters.</p>
                        </div>
                    )}

                    {/* Footer Info */}
                    <div className="mt-24 text-center">
                        <span className="bg-gray-100 text-gray-400 px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-widest">
                            {documents.length} resources in public archive
                        </span>
                    </div>
                </div>
            </div>
        </div>
    );
}
