'use client';
import { useState, useEffect, useCallback, useRef } from 'react';
import AdminLayout from '@/components/admin/AdminLayout';
import { FaTrash, FaUpload, FaFilePdf, FaFileWord, FaFileImage, FaFile, FaPlus, FaTimes, FaBook, FaSearch, FaChevronRight, FaInfoCircle, FaEdit, FaSave, FaExternalLinkAlt, FaImage, FaLayerGroup, FaLanguage } from 'react-icons/fa';
import {
    fetchDocuments,
    uploadDocument,
    updateDocument,
    deleteDocument,
    DocumentItem,
    getFileUrl,
} from '@/lib/api';
import { AnimatePresence, motion } from 'framer-motion';

const CATEGORIES = ['Planning', 'Finance', 'Education', 'Health', 'Legal', 'Policy', 'Report'];

export default function AdminDocumentsPage() {
    const [documents, setDocuments] = useState<DocumentItem[]>([]);
    const [loading, setLoading] = useState(true);
    const [showModal, setShowModal] = useState(false);
    const [uploading, setUploading] = useState(false);
    const [error, setError] = useState('');
    const [searchQuery, setSearchQuery] = useState('');
    const [editingId, setEditingId] = useState<number | null>(null);

    // Form state
    const [formData, setFormData] = useState({
        title_en: '',
        title_am: '',
        title_or: '',
        category: 'Planning',
        author: '',
        description_en: '',
        pages: '',
        language: 'English',
    });
    const [file, setFile] = useState<File | null>(null);
    const [cover, setCover] = useState<File | null>(null);
    const [coverPreview, setCoverPreview] = useState('');

    const loadDocuments = useCallback(async () => {
        setLoading(true);
        try {
            const data = await fetchDocuments();
            setDocuments(data);
        } catch (err) {
            console.error('Fetch error:', err);
        } finally {
            setLoading(false);
        }
    }, []);

    useEffect(() => { loadDocuments(); }, [loadDocuments]);

    const getIcon = (type: string) => {
        const t = (type || '').toLowerCase();
        if (t.includes('pdf')) return <FaFilePdf className="text-rose-500" />;
        if (t.includes('doc')) return <FaFileWord className="text-blue-500" />;
        if (['jpg', 'jpeg', 'png', 'gif'].some(ext => t.includes(ext))) return <FaFileImage className="text-emerald-500" />;
        return <FaFile className="text-gray-400" />;
    };

    const openAdd = () => {
        setEditingId(null);
        setFormData({
            title_en: '',
            title_am: '',
            title_or: '',
            category: 'Planning',
            author: '',
            description_en: '',
            pages: '',
            language: 'English',
        });
        setFile(null);
        setCover(null);
        setCoverPreview('');
        setError('');
        setShowModal(true);
    };

    const handleEdit = (doc: DocumentItem) => {
        setEditingId(doc.id);
        setFormData({
            title_en: doc.title_en || '',
            title_am: doc.title_am || '',
            title_or: doc.title_or || '',
            category: doc.category || 'Planning',
            author: doc.author || '',
            description_en: doc.description_en || '',
            pages: doc.pages?.toString() || '',
            language: doc.language || 'English',
        });
        setFile(null);
        setCover(null);
        setCoverPreview(doc.cover_image_url ? getFileUrl(doc.cover_image_url) : '');
        setError('');
        setShowModal(true);
    };

    const handleUpload = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!editingId && !file) { setError('Please select a document file.'); return; }
        if (!formData.title_en.trim()) { setError('English title is required.'); return; }

        setUploading(true);
        setError('');
        const token = localStorage.getItem('adminToken') || '';

        try {
            const fd = new FormData();
            Object.entries(formData).forEach(([key, value]) => {
                fd.append(key, value);
            });
            if (file) fd.append('file', file);
            if (cover) fd.append('cover_image', cover);
            if (editingId) fd.append('_method', 'PUT');

            if (editingId) {
                await updateDocument(editingId, fd, token);
            } else {
                await uploadDocument(fd, token);
            }
            setShowModal(false);
            loadDocuments();
        } catch (err: any) {
            setError(err.message || 'Operation failed.');
        } finally {
            setUploading(false);
        }
    };

    const handleDelete = async (id: number, title: string) => {
        if (!confirm(`Archive resource "${title}"? This cannot be undone.`)) return;
        const token = localStorage.getItem('adminToken') || '';
        try {
            await deleteDocument(id, token);
            setDocuments((prev) => prev.filter((d) => d.id !== id));
        } catch (err: any) {
            alert(err.message || 'Failed to delete.');
        }
    };

    const filteredDocs = documents.filter(doc =>
        doc.title_en.toLowerCase().includes(searchQuery.toLowerCase()) ||
        doc.category.toLowerCase().includes(searchQuery.toLowerCase())
    );

    return (
        <AdminLayout>
            <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
                <div>
                    <h1 className="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                        <span className="p-3 bg-indigo-600 text-white rounded-2xl shadow-xl shadow-indigo-100">
                            <FaBook size={24} />
                        </span>
                        Digital Asset Registry
                    </h1>
                    <p className="text-slate-500 mt-2 font-medium flex items-center gap-2">
                        System authorized repository for official documents and publications.
                        <span className="w-1.5 h-1.5 bg-slate-300 rounded-full" />
                        <span className="text-indigo-600 font-bold">{documents.length} Managed Assets</span>
                    </p>
                </div>
                <button
                    onClick={openAdd}
                    className="bg-indigo-600 text-white px-8 py-4 rounded-2xl flex items-center gap-3 hover:bg-slate-900 transition-all shadow-2xl shadow-indigo-200 font-black uppercase text-[10px] tracking-widest active:scale-95"
                >
                    <FaPlus /> Authorize New Entry
                </button>
            </div>

            {/* Search & Statistics */}
            <div className="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
                <div className="lg:col-span-3 bg-white p-4 rounded-3xl shadow-sm border border-slate-100 relative group transition-all hover:shadow-indigo-50/50">
                    <FaSearch className="absolute left-8 top-1/2 -translate-y-1/2 text-slate-300 transition-colors group-focus-within:text-indigo-600" />
                    <input
                        type="text"
                        placeholder="Filter registry by keyword, category, or authoring body..."
                        value={searchQuery}
                        onChange={(e) => setSearchQuery(e.target.value)}
                        className="w-full pl-14 pr-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-indigo-600/5 outline-none transition-all font-bold text-slate-600 placeholder:text-slate-400 placeholder:font-medium"
                    />
                </div>
                <div className="bg-indigo-50 border border-indigo-100 p-6 rounded-3xl flex items-center justify-between group overflow-hidden relative">
                    <div className="relative z-10">
                        <p className="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">Archive Size</p>
                        <h3 className="text-3xl font-black text-indigo-600">{documents.length} Units</h3>
                    </div>
                    <FaLayerGroup className="text-6xl text-indigo-100 absolute -right-2 top-1/2 -translate-y-1/2 transform translate-x-4 opacity-0 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-500" />
                </div>
            </div>

            {/* Asset Table */}
            <div className="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead className="bg-slate-50/50 border-b border-slate-100">
                            <tr>
                                <th className="px-8 py-6 font-black text-slate-400 uppercase text-[10px] tracking-widest">Resource Identity</th>
                                <th className="px-6 py-6 font-black text-slate-400 uppercase text-[10px] tracking-widest hidden md:table-cell">Authority</th>
                                <th className="px-6 py-6 font-black text-slate-400 uppercase text-[10px] tracking-widest">Classification</th>
                                <th className="px-6 py-6 font-black text-slate-400 uppercase text-[10px] tracking-widest">Entry Date</th>
                                <th className="px-8 py-6 font-black text-slate-400 uppercase text-[10px] tracking-widest text-right">Registry Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-50">
                            <AnimatePresence mode="popLayout">
                                {loading ? (
                                    Array.from({ length: 6 }).map((_, i) => (
                                        <tr key={i} className="animate-pulse">
                                            <td className="px-8 py-6 flex items-center gap-4">
                                                <div className="w-12 h-16 bg-slate-100 rounded-xl" />
                                                <div className="space-y-2">
                                                    <div className="h-4 bg-slate-100 rounded w-48" />
                                                    <div className="h-3 bg-slate-50 rounded w-24" />
                                                </div>
                                            </td>
                                            <td colSpan={4} className="px-6 py-6"><div className="h-4 bg-slate-50 rounded w-1/2" /></td>
                                        </tr>
                                    ))
                                ) : filteredDocs.length === 0 ? (
                                    <tr>
                                        <td colSpan={5} className="px-8 py-20 text-center">
                                            <div className="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                                <FaBook className="text-4xl text-slate-200" />
                                            </div>
                                            <h3 className="text-xl font-black text-slate-900 tracking-tight mb-2 uppercase">Archive Empty</h3>
                                            <p className="text-slate-400 text-sm italic font-medium">No assets registered in the current directory.</p>
                                        </td>
                                    </tr>
                                ) : filteredDocs.map((doc) => (
                                    <motion.tr
                                        layout
                                        initial={{ opacity: 0 }}
                                        animate={{ opacity: 1 }}
                                        exit={{ opacity: 0 }}
                                        key={doc.id}
                                        className="hover:bg-indigo-50/20 transition-all group"
                                    >
                                        <td className="px-8 py-6">
                                            <div className="flex items-center gap-5">
                                                <div className="relative shrink-0">
                                                    {doc.cover_image_url ? (
                                                        <img
                                                            src={getFileUrl(doc.cover_image_url)}
                                                            className="w-12 h-16 object-cover rounded-xl shadow-sm group-hover:scale-110 transition-transform duration-500 ring-4 ring-white"
                                                            alt="Cover"
                                                        />
                                                    ) : (
                                                        <div className="w-12 h-16 bg-slate-50 rounded-xl border-2 border-slate-100 flex items-center justify-center text-xl group-hover:border-indigo-100 group-hover:bg-white transition-all">
                                                            {getIcon(doc.file_type)}
                                                        </div>
                                                    )}
                                                </div>
                                                <div>
                                                    <span className="font-black text-slate-900 block truncate max-w-[200px] lg:max-w-xs group-hover:text-indigo-600 transition-colors uppercase text-xs tracking-tight">{doc.title_en}</span>
                                                    <span className="text-[10px] uppercase font-black text-indigo-400 tracking-widest flex items-center gap-1.5 mt-1 bg-indigo-50/50 px-2 py-0.5 rounded italic w-fit">
                                                        {getIcon(doc.file_type)} {doc.file_type?.toUpperCase() || 'DOCUMENT'}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td className="px-6 py-6">
                                            <span className="font-bold text-slate-600 text-sm hidden md:block">
                                                {doc.author || 'Generic Authority'}
                                            </span>
                                        </td>
                                        <td className="px-6 py-6">
                                            <div className="flex flex-col gap-1.5">
                                                <span className="bg-indigo-600 text-white px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest w-fit shadow-lg shadow-indigo-100/50">
                                                    {doc.category}
                                                </span>
                                                <span className="text-[10px] text-slate-400 font-bold uppercase tracking-tighter italic">
                                                    {doc.pages || '0'} Folios • {doc.language || 'English'}
                                                </span>
                                            </div>
                                        </td>
                                        <td className="px-6 py-6">
                                            <span className="text-slate-500 tabular-nums font-bold text-sm">
                                                {new Date(doc.created_at).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' })}
                                            </span>
                                        </td>
                                        <td className="px-8 py-6">
                                            <div className="flex justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0">
                                                <a
                                                    href={getFileUrl(doc.file_url)}
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    className="w-10 h-10 flex items-center justify-center rounded-xl bg-white text-emerald-600 border border-slate-100 hover:bg-emerald-600 hover:text-white transition-all shadow-sm active:scale-95"
                                                    title="Inspect Asset"
                                                >
                                                    <FaExternalLinkAlt size={14} />
                                                </a>
                                                <button
                                                    onClick={() => handleEdit(doc)}
                                                    className="w-10 h-10 flex items-center justify-center rounded-xl bg-white text-indigo-600 border border-slate-100 hover:bg-indigo-600 hover:text-white transition-all shadow-sm active:scale-95"
                                                    title="Refine Record"
                                                >
                                                    <FaEdit size={14} />
                                                </button>
                                                <button
                                                    onClick={() => handleDelete(doc.id, doc.title_en)}
                                                    className="w-10 h-10 flex items-center justify-center rounded-xl bg-white text-rose-600 border border-slate-100 hover:bg-rose-600 hover:text-white transition-all shadow-sm active:scale-95"
                                                    title="Archive"
                                                >
                                                    <FaTrash size={14} />
                                                </button>
                                            </div>
                                        </td>
                                    </motion.tr>
                                ))}
                            </AnimatePresence>
                        </tbody>
                    </table>
                </div>
            </div>

            {/* Registration Overlay */}
            <AnimatePresence>
                {showModal && (
                    <div className="fixed inset-0 bg-slate-900/80 backdrop-blur-md flex items-center justify-center z-50 p-4 pt-10 overflow-y-auto">
                        <motion.div
                            initial={{ opacity: 0, scale: 0.95, y: 30 }}
                            animate={{ opacity: 1, scale: 1, y: 0 }}
                            exit={{ opacity: 0, scale: 0.95, y: 30 }}
                            className="bg-white rounded-[3rem] shadow-[0_32px_64px_-16px_rgba(0,0,0,0.3)] w-full max-w-5xl my-auto overflow-hidden border border-slate-100 flex flex-col max-h-[90vh]"
                        >
                            <div className="p-10 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
                                <div className="flex items-center gap-5">
                                    <div className="w-16 h-16 bg-indigo-600 text-white rounded-[1.5rem] flex items-center justify-center shadow-xl shadow-indigo-200">
                                        <FaLayerGroup size={24} />
                                    </div>
                                    <div>
                                        <h2 className="text-3xl font-black text-slate-900 tracking-tighter italic">
                                            {editingId ? 'Refine Asset Record' : 'Authorize Asset Registry'}
                                        </h2>
                                        <p className="text-[10px] text-slate-400 font-bold uppercase tracking-[0.3em] mt-1">Digital Logistics Identification Terminal</p>
                                    </div>
                                </div>
                                <button onClick={() => setShowModal(false)} className="w-14 h-14 flex items-center justify-center rounded-full text-slate-400 hover:text-rose-500 transition-all border border-transparent hover:border-slate-100 text-3xl group">
                                    <FaTimes className="group-hover:rotate-90 transition-transform" />
                                </button>
                            </div>

                            <form onSubmit={handleUpload} className="flex-grow overflow-y-auto p-12 space-y-12 scrollbar-thin scrollbar-thumb-slate-200">
                                {error && (
                                    <motion.div
                                        initial={{ opacity: 0, x: -10 }}
                                        animate={{ opacity: 1, x: 0 }}
                                        className="text-rose-600 bg-rose-50 border-2 border-rose-100 p-6 rounded-[2rem] text-sm font-black flex items-center gap-5"
                                    >
                                        <span className="w-10 h-10 bg-rose-600 text-white rounded-full flex items-center justify-center shrink-0 shadow-lg shadow-rose-200">
                                            <FaTimes />
                                        </span>
                                        {error}
                                    </motion.div>
                                )}

                                {/* Identification Section */}
                                <div className="space-y-8">
                                    <div className="flex items-center gap-3 text-indigo-600 font-black text-[11px] uppercase tracking-[0.2em]">
                                        <span className="w-8 h-[2px] bg-indigo-600" /> Administrative Identification
                                    </div>
                                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                                        <div className="space-y-2">
                                            <label className="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                                                Registry Title (EN) <span className="text-rose-500">*</span>
                                            </label>
                                            <input
                                                value={formData.title_en}
                                                onChange={e => setFormData({ ...formData, title_en: e.target.value })}
                                                required
                                                className="w-full bg-slate-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-[1.5rem] px-6 py-5 outline-none transition-all font-black text-slate-700 placeholder:font-medium shadow-sm active:scale-105"
                                                placeholder="Formal nomenclature..."
                                            />
                                        </div>
                                        <div className="space-y-2">
                                            <label className="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">ርዕስ (አማርኛ)</label>
                                            <input
                                                value={formData.title_am}
                                                onChange={e => setFormData({ ...formData, title_am: e.target.value })}
                                                className="w-full bg-slate-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-[1.5rem] px-6 py-5 outline-none transition-all font-amharic font-bold text-slate-700 shadow-sm"
                                                placeholder="የሰነድ ርዕስ..."
                                            />
                                        </div>
                                        <div className="space-y-2">
                                            <label className="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">BAKKA (OROMOO)</label>
                                            <input
                                                value={formData.title_or}
                                                onChange={e => setFormData({ ...formData, title_or: e.target.value })}
                                                className="w-full bg-slate-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-[1.5rem] px-6 py-5 outline-none transition-all font-bold text-slate-700 shadow-sm"
                                                placeholder="Bakka galmee..."
                                            />
                                        </div>
                                    </div>
                                </div>

                                {/* Logistics Section */}
                                <div className="space-y-8">
                                    <div className="flex items-center gap-3 text-indigo-600 font-black text-[11px] uppercase tracking-[0.2em]">
                                        <span className="w-8 h-[2px] bg-indigo-600" /> Logistics & Classification
                                    </div>
                                    <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
                                        <div className="md:col-span-2 space-y-2">
                                            <label className="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Issuing Authority</label>
                                            <input
                                                value={formData.author}
                                                onChange={e => setFormData({ ...formData, author: e.target.value })}
                                                className="w-full bg-slate-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-[1.5rem] px-6 py-5 outline-none transition-all font-bold text-slate-700"
                                                placeholder="e.g. OSZA Planning & Finance Bureau"
                                            />
                                        </div>
                                        <div className="space-y-2">
                                            <label className="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-2"><FaLayerGroup /> Category</label>
                                            <select
                                                value={formData.category}
                                                onChange={e => setFormData({ ...formData, category: e.target.value })}
                                                className="w-full bg-slate-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-[1.5rem] px-6 py-5 outline-none transition-all font-black text-slate-700 appearance-none cursor-pointer"
                                            >
                                                {CATEGORIES.map(c => <option key={c} value={c}>{c}</option>)}
                                            </select>
                                        </div>
                                        <div className="space-y-2">
                                            <label className="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-2"><FaLanguage /> Language</label>
                                            <input
                                                value={formData.language}
                                                onChange={e => setFormData({ ...formData, language: e.target.value })}
                                                className="w-full bg-slate-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-[1.5rem] px-6 py-5 outline-none transition-all font-black text-slate-700 text-center"
                                            />
                                        </div>
                                    </div>
                                </div>

                                {/* Narrative Section */}
                                <div className="space-y-4">
                                    <label className="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-2"><FaInfoCircle /> Narrative Summary</label>
                                    <textarea
                                        value={formData.description_en}
                                        onChange={e => setFormData({ ...formData, description_en: e.target.value })}
                                        rows={4}
                                        className="w-full bg-slate-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-[2rem] px-8 py-6 outline-none transition-all font-medium text-slate-600 leading-relaxed shadow-inner"
                                        placeholder="Enter abstract or formal overview of the document contents..."
                                    />
                                </div>

                                {/* Assets Selection */}
                                <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
                                    <div className="space-y-6">
                                        <div className="flex items-center gap-3 text-emerald-600 font-black text-[11px] uppercase tracking-[0.2em]">
                                            <span className="w-8 h-[2px] bg-emerald-600" /> Digital Artifact Payload
                                        </div>
                                        <div className="relative group p-10 bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/20 transition-all cursor-pointer flex flex-col items-center justify-center text-center">
                                            <input
                                                type="file"
                                                accept=".pdf,.doc,.docx"
                                                onChange={e => setFile(e.target.files?.[0] || null)}
                                                className="absolute inset-0 opacity-0 cursor-pointer z-10"
                                            />
                                            {file ? (
                                                <motion.div initial={{ scale: 0.8 }} animate={{ scale: 1 }} className="flex flex-col items-center">
                                                    <div className="w-20 h-20 bg-emerald-600 text-white rounded-3xl flex items-center justify-center shadow-xl shadow-emerald-100 mb-4 animate-bounce">
                                                        <FaFilePdf size={32} />
                                                    </div>
                                                    <p className="font-black text-slate-900 text-sm truncate max-w-[200px] mb-1">{file.name}</p>
                                                    <p className="text-[10px] font-black text-emerald-600 uppercase tracking-widest italic">Asset Verified for Upload</p>
                                                </motion.div>
                                            ) : (
                                                <>
                                                    <div className="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-slate-300 shadow-sm mb-4 group-hover:scale-110 transition-transform">
                                                        <FaUpload size={24} />
                                                    </div>
                                                    <h4 className="font-black text-slate-800 tracking-tight">Main Binary</h4>
                                                    <p className="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">PDF, DOC (ENCRYPTED TUNNEL)</p>
                                                </>
                                            )}
                                        </div>
                                    </div>

                                    <div className="space-y-6">
                                        <div className="flex items-center gap-3 text-purple-600 font-black text-[11px] uppercase tracking-[0.2em]">
                                            <span className="w-8 h-[2px] bg-purple-600" /> Visual Identity (Cover)
                                        </div>
                                        <div className="flex items-center gap-8 p-10 bg-slate-50 rounded-[3rem] border border-slate-100">
                                            <div className="w-28 h-36 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center justify-center overflow-hidden shrink-0 group relative">
                                                {coverPreview ? (
                                                    <img src={coverPreview} className="w-full h-full object-cover" alt="Preview" />
                                                ) : (
                                                    <FaImage className="text-3xl text-slate-200" />
                                                )}
                                                <input
                                                    type="file"
                                                    accept="image/*"
                                                    onChange={e => {
                                                        const f = e.target.files?.[0];
                                                        if (f) { setCover(f); setCoverPreview(URL.createObjectURL(f)); }
                                                    }}
                                                    className="absolute inset-0 opacity-0 cursor-pointer z-10"
                                                />
                                            </div>
                                            <div className="flex-1">
                                                <h4 className="font-black text-slate-900 tracking-tight">Registry Cover Image</h4>
                                                <p className="text-xs text-slate-500 font-medium leading-relaxed mb-4">Strategic visual identifier for the library interface.</p>
                                                <button type="button" className="relative px-6 py-3 bg-white border-2 border-slate-100 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-600 hover:border-purple-600 hover:text-purple-600 transition-all shadow-sm">
                                                    Assign New Cover
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {/* Footer Actions */}
                                <div className="pt-12 border-t border-slate-100 flex justify-between items-center sticky bottom-0 bg-white/90 backdrop-blur-xl pb-6">
                                    <button
                                        type="button"
                                        onClick={() => setShowModal(false)}
                                        className="text-slate-400 font-black uppercase tracking-widest text-[11px] hover:text-rose-600 transition-colors flex items-center gap-2 group"
                                    >
                                        <FaTimes className="group-hover:rotate-90 transition-transform" /> Discard Registry
                                    </button>
                                    <div className="flex items-center gap-6">
                                        {uploading && (
                                            <div className="flex items-center gap-3 text-[10px] font-black uppercase tracking-widest text-indigo-400">
                                                <div className="w-5 h-5 border-2 border-indigo-600 border-t-transparent rounded-full animate-spin" />
                                                Archiving Enters...
                                            </div>
                                        )}
                                        <button
                                            type="submit"
                                            disabled={uploading}
                                            className="px-16 py-5 bg-indigo-600 text-white rounded-[1.5rem] font-black uppercase tracking-[0.2em] text-[11px] shadow-[0_20px_40px_-12px_rgba(79,70,229,0.4)] hover:bg-slate-900 hover:shadow-none transition-all active:scale-95 disabled:opacity-50 flex items-center gap-4"
                                        >
                                            {uploading ? 'Processing binary...' : editingId ? <><FaSave /> Commit Changes</> : <><FaUpload /> Secure Registry</>}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </motion.div>
                    </div>
                )}
            </AnimatePresence>
        </AdminLayout>
    );
}
