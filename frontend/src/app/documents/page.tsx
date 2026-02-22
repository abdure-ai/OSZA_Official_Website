'use client';

import { FaFilePdf, FaDownload, FaSearch, FaFileWord, FaFileImage, FaFile, FaBookOpen, FaLanguage, FaFileAlt } from 'react-icons/fa';
import { useState, useEffect } from 'react';
import { fetchDocuments, DocumentItem, getFileUrl } from '@/lib/api';

export default function DocumentsPage() {
    const [documents, setDocuments] = useState<DocumentItem[]>([]);
    const [category, setCategory] = useState("All Categories");
    const [search, setSearch] = useState("");
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const loadDocs = async () => {
            setLoading(true);
            const data = await fetchDocuments(category, search);
            setDocuments(data);
            setLoading(false);
        };

        const timeoutId = setTimeout(() => {
            loadDocs();
        }, 300);

        return () => clearTimeout(timeoutId);
    }, [category, search]);

    const getIcon = (type: string) => {
        if (type === 'pdf') return <FaFilePdf className="text-red-500" />;
        if (type === 'doc' || type === 'docx') return <FaFileWord className="text-blue-500" />;
        if (['jpg', 'jpeg', 'png'].includes(type)) return <FaFileImage className="text-green-500" />;
        return <FaFile className="text-gray-500" />;
    };

    return (
        <div className="bg-gray-50 min-h-screen pb-20">
            {/* Hero Section */}
            <div className="bg-white border-b py-16 mb-12">
                <div className="container mx-auto px-4 text-center max-w-3xl">
                    <div className="inline-flex items-center gap-2 text-primary font-bold uppercase tracking-[0.2em] text-xs mb-4">
                        <FaBookOpen />
                        <span>Knowledge Base</span>
                    </div>
                    <h1 className="text-4xl md:text-5xl font-black text-gray-900 mb-6 font-primary">
                        Digital Library & Resources
                    </h1>
                    <p className="text-lg text-gray-600 leading-relaxed">
                        Access official reports, strategic plans, legal frameworks, and educational materials
                        curated for the Oromo Special Zone administration and community.
                    </p>
                </div>
            </div>

            <div className="container mx-auto px-4">
                {/* Search & Filter Bar */}
                <div className="max-w-6xl mx-auto mb-12">
                    <div className="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-4 items-center">
                        <div className="relative flex-1 w-full">
                            <input
                                type="text"
                                placeholder="Search by title, author or keyword..."
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                className="w-full pl-12 pr-4 py-3 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none"
                            />
                            <FaSearch className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" />
                        </div>
                        <div className="w-full md:w-64">
                            <select
                                value={category}
                                onChange={(e) => setCategory(e.target.value)}
                                className="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none appearance-none cursor-pointer"
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
                        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                            {[1, 2, 3, 4].map(i => (
                                <div key={i} className="animate-pulse">
                                    <div className="bg-gray-200 aspect-[3/4] rounded-2xl mb-4" />
                                    <div className="h-4 bg-gray-200 rounded w-3/4 mb-2" />
                                    <div className="h-4 bg-gray-200 rounded w-1/2" />
                                </div>
                            ))}
                        </div>
                    ) : documents.length > 0 ? (
                        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 md:gap-8">
                            {documents.map((doc) => (
                                <div key={doc.id} className="group flex flex-col">
                                    {/* Book Card */}
                                    <div className="relative aspect-[3/4] bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group-hover:shadow-xl group-hover:-translate-y-2 transition-all duration-300">
                                        {doc.cover_image_url ? (
                                            <img
                                                src={getFileUrl(doc.cover_image_url)}
                                                alt={doc.title_en}
                                                className="w-full h-full object-cover"
                                            />
                                        ) : (
                                            <div className="w-full h-full bg-gradient-to-br from-gray-50 to-gray-100 flex flex-col items-center justify-center p-6 text-center">
                                                <div className="text-4xl mb-4 opacity-20">
                                                    {getIcon(doc.file_type)}
                                                </div>
                                                <span className="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                                    Official {doc.file_type} Resource
                                                </span>
                                            </div>
                                        )}

                                        {/* Overlay Type Tag */}
                                        <div className="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-2 py-1 rounded-lg text-[10px] font-bold uppercase shadow-sm">
                                            {doc.file_type}
                                        </div>

                                        {/* Hover Quick Actions */}
                                        <div className="absolute inset-0 bg-primary/80 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center transition-opacity duration-300 p-4 text-center">
                                            <a
                                                href={getFileUrl(doc.file_url)}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="bg-white text-primary px-4 py-2 rounded-full font-bold text-sm shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-transform flex items-center gap-2"
                                            >
                                                <FaDownload size={14} /> Download
                                            </a>
                                        </div>
                                    </div>

                                    {/* Info */}
                                    <div className="mt-4 flex flex-col flex-grow">
                                        <span className="text-[10px] font-bold text-primary uppercase tracking-widest mb-1">
                                            {doc.category}
                                        </span>
                                        <h3 className="font-bold text-gray-900 leading-snug line-clamp-2 group-hover:text-primary transition-colors cursor-default">
                                            {doc.title_en}
                                        </h3>
                                        <p className="text-xs text-gray-500 mt-1 line-clamp-1 italic">
                                            {doc.author || 'Oromo Special Zone'}
                                        </p>

                                        {/* Metadata */}
                                        <div className="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-[10px] text-gray-400">
                                            <span className="flex items-center gap-1">
                                                <FaFileAlt /> {doc.pages ? `${doc.pages} pages` : 'Document'}
                                            </span>
                                            <span className="flex items-center gap-1">
                                                <FaLanguage /> {doc.language || 'English'}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <div className="text-center py-20 bg-white rounded-3xl border-2 border-dashed border-gray-100">
                            <div className="text-4xl mb-4">📚</div>
                            <h3 className="text-lg font-bold text-gray-900">No resources found</h3>
                            <p className="text-gray-500 mt-1">Try adjusting your search or category filters.</p>
                        </div>
                    )}

                    {/* Footer Info */}
                    <div className="mt-16 text-center text-sm text-gray-400">
                        Showing {documents.length} resources in the digital library
                    </div>
                </div>
            </div>
        </div>
    );
}
