'use client';

import { useEffect, useState } from 'react';
import AdminLayout from '@/components/admin/AdminLayout';
import { fetchTenders, createTender, updateTender, deleteTender, Tender, getFileUrl } from '@/lib/api';
import { FaPlus, FaEdit, FaTrash, FaSpinner, FaFilePdf, FaSearch, FaCalendarAlt, FaHashtag, FaInfoCircle, FaFileAlt } from 'react-icons/fa';
import { AnimatePresence, motion } from 'framer-motion';

const STATUS_COLORS = {
    Open: 'bg-emerald-100 text-emerald-700 border-emerald-200',
    Closed: 'bg-rose-100 text-rose-700 border-rose-200',
    Awarded: 'bg-amber-100 text-amber-700 border-amber-200',
    Cancelled: 'bg-slate-100 text-slate-700 border-slate-200',
    active: 'bg-emerald-100 text-emerald-700 border-emerald-200',
    closed: 'bg-rose-100 text-rose-700 border-rose-200',
    archived: 'bg-slate-100 text-slate-700 border-slate-200',
};

export default function AdminTendersPage() {
    const [tenders, setTenders] = useState<Tender[]>([]);
    const [loading, setLoading] = useState(true);
    const [showModal, setShowModal] = useState(false);
    const [editingTender, setEditingTender] = useState<Tender | null>(null);
    const [token, setToken] = useState('');
    const [file, setFile] = useState<File | null>(null);
    const [searchQuery, setSearchQuery] = useState('');

    const [formData, setFormData] = useState({
        title_en: '',
        title_am: '',
        title_or: '',
        ref_number: '',
        deadline: '',
        status: 'Open' as const,
        description_en: '',
        description_am: '',
        description_or: '',
    });

    useEffect(() => {
        const t = localStorage.getItem('adminToken') || '';
        setToken(t);
        loadTenders();
    }, []);

    async function loadTenders() {
        setLoading(true);
        const data = await fetchTenders();
        setTenders(data);
        setLoading(false);
    }

    const handleOpenModal = (tender?: Tender) => {
        setFile(null);
        if (tender) {
            setEditingTender(tender);
            setFormData({
                title_en: tender.title_en || '',
                title_am: tender.title_am || '',
                title_or: tender.title_or || '',
                ref_number: tender.ref_number || '',
                deadline: tender.deadline ? tender.deadline.split('T')[0] : '',
                status: tender.status as any,
                description_en: tender.description_en || '',
                description_am: tender.description_am || '',
                description_or: tender.description_or || '',
            });
        } else {
            setEditingTender(null);
            setFormData({
                title_en: '',
                title_am: '',
                title_or: '',
                ref_number: '',
                deadline: '',
                status: 'Open',
                description_en: '',
                description_am: '',
                description_or: '',
            });
        }
        setShowModal(true);
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        const data = new FormData();
        Object.entries(formData).forEach(([key, value]) => {
            data.append(key, value);
        });
        if (file) data.append('document', file);

        try {
            if (editingTender) {
                await updateTender(editingTender.id, data, token);
            } else {
                await createTender(data, token);
            }
            setShowModal(false);
            loadTenders();
        } catch (error: any) {
            alert(error.message);
        }
    };

    const handleDelete = async (id: number) => {
        if (confirm('Are you sure you want to delete this tender?')) {
            try {
                await deleteTender(id, token);
                loadTenders();
            } catch (error: any) {
                alert(error.message);
            }
        }
    };

    const filteredTenders = tenders.filter(t =>
        t.title_en.toLowerCase().includes(searchQuery.toLowerCase()) ||
        t.ref_number?.toLowerCase().includes(searchQuery.toLowerCase())
    );

    return (
        <AdminLayout>
            <div className="mb-10">
                <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div className="flex items-center gap-3 mb-2">
                            <span className="h-1 w-12 bg-indigo-600 rounded-full"></span>
                            <span className="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-600">Procurement Office</span>
                        </div>
                        <h1 className="text-4xl font-black text-slate-900 tracking-tight italic">
                            Tender <span className="text-indigo-600">Archive</span>
                        </h1>
                        <p className="text-slate-500 mt-2 text-sm font-medium">Manage institutional bidding opportunities and contractual notices.</p>
                    </div>
                    <button
                        onClick={() => handleOpenModal()}
                        className="group bg-slate-900 text-white px-8 py-4 rounded-2xl flex items-center gap-3 hover:bg-slate-800 transition-all shadow-2xl shadow-slate-200 active:scale-95"
                    >
                        <div className="w-6 h-6 bg-white/10 rounded-lg flex items-center justify-center group-hover:rotate-90 transition-transform">
                            <FaPlus className="text-xs" />
                        </div>
                        <span className="font-black text-[10px] uppercase tracking-widest">Publish New Tender</span>
                    </button>
                </div>
            </div>

            <div className="space-y-8">
                {/* Search and Filters Refined */}
                <div className="bg-white/60 backdrop-blur-md p-2 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col md:flex-row gap-2 items-center">
                    <div className="relative flex-1 w-full group">
                        <FaSearch className="absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-600 transition-colors" />
                        <input
                            type="text"
                            placeholder="Search tender titles, reference IDs..."
                            className="w-full pl-14 pr-6 py-4 bg-transparent border-none rounded-2xl focus:ring-0 text-sm font-bold text-slate-700 placeholder:text-slate-300"
                            value={searchQuery}
                            onChange={(e) => setSearchQuery(e.target.value)}
                        />
                    </div>
                </div>

                {/* Registry Table */}
                <div className="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="w-full text-left border-collapse">
                            <thead>
                                <tr className="bg-slate-900">
                                    <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Reference</th>
                                    <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Procurement Detail</th>
                                    <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Closing</th>
                                    <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Status</th>
                                    <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Control</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-50">
                                <AnimatePresence mode="popLayout">
                                    {loading ? (
                                        [...Array(6)].map((_, i) => (
                                            <tr key={`skeleton-${i}`} className="animate-pulse">
                                                <td className="px-8 py-6"><div className="h-4 bg-slate-100 rounded w-20"></div></td>
                                                <td className="px-8 py-6">
                                                    <div className="h-4 bg-slate-100 rounded w-64 mb-2"></div>
                                                    <div className="h-3 bg-slate-50 rounded w-40"></div>
                                                </td>
                                                <td className="px-8 py-6"><div className="h-4 bg-slate-100 rounded w-24"></div></td>
                                                <td className="px-8 py-6"><div className="h-6 bg-slate-100 rounded-full w-16"></div></td>
                                                <td className="px-8 py-6 flex justify-end gap-2"><div className="h-10 w-10 bg-slate-100 rounded-xl"></div></td>
                                            </tr>
                                        ))
                                    ) : filteredTenders.map((t) => (
                                        <motion.tr
                                            key={t.id}
                                            layout
                                            initial={{ opacity: 0, y: 10 }}
                                            animate={{ opacity: 1, y: 0 }}
                                            exit={{ opacity: 0, scale: 0.95 }}
                                            className="hover:bg-indigo-50/30 transition-all group"
                                        >
                                            <td className="px-8 py-6">
                                                <div className="flex flex-col">
                                                    <span className="font-mono text-[10px] font-black text-indigo-600 bg-indigo-50 px-3 py-1 rounded-lg w-fit">
                                                        {t.ref_number || 'UNASSIGNED'}
                                                    </span>
                                                </div>
                                            </td>
                                            <td className="px-8 py-6">
                                                <div className="max-w-md">
                                                    <div className="font-bold text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-1">
                                                        {t.title_en}
                                                    </div>
                                                    <div className="flex items-center gap-4 mt-2">
                                                        {t.file_url ? (
                                                            <a
                                                                href={getFileUrl(t.file_url)}
                                                                target="_blank"
                                                                className="text-[9px] font-black uppercase tracking-widest text-emerald-600 flex items-center gap-2 bg-emerald-50 px-2 py-1 rounded-md hover:bg-emerald-100 transition-colors"
                                                            >
                                                                <FaFilePdf size={10} /> Manifest Attached
                                                            </a>
                                                        ) : (
                                                            <span className="text-[9px] font-black uppercase tracking-widest text-slate-300 flex items-center gap-2">
                                                                <FaFileAlt size={10} /> No Document
                                                            </span>
                                                        )}
                                                    </div>
                                                </div>
                                            </td>
                                            <td className="px-8 py-6">
                                                <div className="flex items-center gap-3 text-slate-500 font-bold text-xs italic">
                                                    <FaCalendarAlt className="text-indigo-400 group-hover:rotate-12 transition-transform" />
                                                    {new Date(t.deadline).toLocaleDateString(undefined, {
                                                        month: 'short',
                                                        day: 'numeric',
                                                        year: 'numeric'
                                                    })}
                                                </div>
                                            </td>
                                            <td className="px-8 py-6">
                                                <span className={`px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-[0.1em] border-2 shadow-sm ${STATUS_COLORS[t.status as keyof typeof STATUS_COLORS] || 'bg-slate-100 text-slate-700 border-slate-200'}`}>
                                                    {t.status}
                                                </span>
                                            </td>
                                            <td className="px-8 py-6 text-right">
                                                <div className="flex justify-end gap-2">
                                                    <button
                                                        onClick={() => handleOpenModal(t)}
                                                        className="w-10 h-10 flex items-center justify-center bg-white text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all shadow-sm border border-slate-100"
                                                        title="Refine Entry"
                                                    >
                                                        <FaEdit size={14} />
                                                    </button>
                                                    <button
                                                        onClick={() => handleDelete(t.id)}
                                                        className="w-10 h-10 flex items-center justify-center bg-white text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all shadow-sm border border-slate-100"
                                                        title="Withdraw Tender"
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
            </div>

            {/* Modal */}
            <AnimatePresence>
                {showModal && (
                    <div className="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto">
                        <motion.div
                            initial={{ opacity: 0, scale: 0.95, y: 20 }}
                            animate={{ opacity: 1, scale: 1, y: 0 }}
                            exit={{ opacity: 0, scale: 0.95, y: 20 }}
                            className="bg-white rounded-3xl shadow-2xl w-full max-w-4xl my-8 overflow-hidden border border-gray-100"
                        >
                            <div className="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                                <div>
                                    <h2 className="text-2xl font-bold text-gray-900">{editingTender ? 'Edit Tender Notice' : 'New Tender Announcement'}</h2>
                                    <p className="text-gray-500 text-sm mt-1">Fill in the details for the procurement opportunity.</p>
                                </div>
                                <button
                                    onClick={() => setShowModal(false)}
                                    className="w-10 h-10 flex items-center justify-center rounded-full text-gray-400 hover:bg-white hover:text-gray-600 transition-all border border-transparent hover:border-gray-200"
                                >
                                    ×
                                </button>
                            </div>

                            <form onSubmit={handleSubmit} className="p-8 space-y-8">
                                {/* Localized Titles */}
                                <div className="space-y-4">
                                    <div className="flex items-center gap-2 text-primary font-bold text-sm uppercase tracking-wider mb-4">
                                        <FaInfoCircle /> Tender Information
                                    </div>
                                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div className="space-y-2">
                                            <label className="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Title (English)</label>
                                            <input
                                                type="text"
                                                required
                                                className="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all"
                                                placeholder="e.g. Supply of IT Equipment"
                                                value={formData.title_en}
                                                onChange={(e) => setFormData({ ...formData, title_en: e.target.value })}
                                            />
                                        </div>
                                        <div className="space-y-2">
                                            <label className="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">ርዕስ (Amharic)</label>
                                            <input
                                                type="text"
                                                className="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all font-amharic"
                                                placeholder="የአይቲ መሳሪያዎች አቅርቦት"
                                                value={formData.title_am}
                                                onChange={(e) => setFormData({ ...formData, title_am: e.target.value })}
                                            />
                                        </div>
                                        <div className="space-y-2">
                                            <label className="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Mata-duree (Oromo)</label>
                                            <input
                                                type="text"
                                                className="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all"
                                                placeholder="Dhiyeessii Meeshaalee IT"
                                                value={formData.title_or}
                                                onChange={(e) => setFormData({ ...formData, title_or: e.target.value })}
                                            />
                                        </div>
                                    </div>
                                </div>

                                {/* Logistics and Status */}
                                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div className="space-y-2">
                                        <label className="flex items-center gap-2 text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">
                                            <FaHashtag className="text-[10px]" /> Reference Number
                                        </label>
                                        <input
                                            type="text"
                                            className="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all"
                                            placeholder="TNDR/2024/001"
                                            value={formData.ref_number}
                                            onChange={(e) => setFormData({ ...formData, ref_number: e.target.value })}
                                        />
                                    </div>
                                    <div className="space-y-2">
                                        <label className="flex items-center gap-2 text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">
                                            <FaCalendarAlt className="text-[10px]" /> Closing Date
                                        </label>
                                        <input
                                            type="date"
                                            required
                                            className="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all"
                                            value={formData.deadline}
                                            onChange={(e) => setFormData({ ...formData, deadline: e.target.value })}
                                        />
                                    </div>
                                    <div className="space-y-2">
                                        <label className="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Current Status</label>
                                        <select
                                            className="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all appearance-none"
                                            value={formData.status}
                                            onChange={(e) => setFormData({ ...formData, status: e.target.value as any })}
                                        >
                                            <option value="Open">Open (Active)</option>
                                            <option value="Closed">Closed</option>
                                            <option value="Awarded">Awarded</option>
                                            <option value="Cancelled">Cancelled</option>
                                        </select>
                                    </div>
                                </div>

                                {/* Descriptions */}
                                <div className="space-y-4 mt-6">
                                    <div className="flex items-center gap-2 text-primary font-bold text-sm uppercase tracking-wider mb-2">
                                        <FaFileAlt /> Detailed Descriptions
                                    </div>
                                    <div className="grid grid-cols-1 gap-6">
                                        <div className="space-y-2">
                                            <label className="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Description (English)</label>
                                            <textarea
                                                className="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all h-24"
                                                placeholder="Provide full details of the tender requirements..."
                                                value={formData.description_en}
                                                onChange={(e) => setFormData({ ...formData, description_en: e.target.value })}
                                            />
                                        </div>
                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div className="space-y-2">
                                                <label className="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">መግለጫ (Amharic)</label>
                                                <textarea
                                                    className="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all h-24 font-amharic"
                                                    value={formData.description_am}
                                                    onChange={(e) => setFormData({ ...formData, description_am: e.target.value })}
                                                />
                                            </div>
                                            <div className="space-y-2">
                                                <label className="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Ibsa (Oromo)</label>
                                                <textarea
                                                    className="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all h-24"
                                                    value={formData.description_or}
                                                    onChange={(e) => setFormData({ ...formData, description_or: e.target.value })}
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {/* Document Upload */}
                                <div className="bg-blue-50/50 p-6 rounded-2xl border border-blue-100 flex flex-col md:flex-row items-center gap-6">
                                    <div className="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-blue-100 flex-shrink-0">
                                        <FaFilePdf className="text-3xl text-rose-500" />
                                    </div>
                                    <div className="flex-1 text-center md:text-left">
                                        <h4 className="font-bold text-gray-900">Tender Document (PDF or Word)</h4>
                                        <p className="text-sm text-gray-500 mt-1">Upload the official tender document for bidders to download.</p>
                                        <input
                                            type="file"
                                            className="mt-4 block w-full text-sm text-slate-500
                                                file:mr-4 file:py-2 file:px-4
                                                file:rounded-full file:border-0
                                                file:text-xs file:font-bold
                                                file:bg-primary file:text-white
                                                hover:file:bg-opacity-90 transition-all"
                                            onChange={(e) => setFile(e.target.files?.[0] || null)}
                                        />
                                    </div>
                                </div>

                                <div className="pt-6 border-t border-gray-100 flex justify-end gap-3 sticky bottom-0 bg-white">
                                    <button
                                        type="button"
                                        onClick={() => setShowModal(false)}
                                        className="px-6 py-3 text-gray-600 font-bold hover:text-gray-900 transition-colors"
                                    >
                                        Discard Changes
                                    </button>
                                    <button
                                        type="submit"
                                        className="px-10 py-3 bg-gradient-to-r from-primary to-blue-700 text-white rounded-xl font-bold shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all active:scale-95"
                                    >
                                        {editingTender ? 'Update Announcement' : 'Publish Tender'}
                                    </button>
                                </div>
                            </form>
                        </motion.div>
                    </div>
                )}
            </AnimatePresence>
        </AdminLayout>
    );
}
