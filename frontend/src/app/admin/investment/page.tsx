'use client';

import AdminLayout from '@/components/admin/AdminLayout';
import { useState, useEffect, useCallback, useRef } from 'react';
import {
    fetchInvestments, createInvestment, updateInvestment, deleteInvestment,
    InvestmentOpportunity, getFileUrl
} from '@/lib/api';
import { FaPlus, FaTrash, FaEdit, FaSave, FaTimes, FaCoins, FaMapMarkerAlt, FaCalendarAlt, FaBriefcase, FaSearch, FaFilter, FaCheckCircle, FaTimesCircle, FaInfoCircle, FaArrowRight, FaBullseye, FaHandHoldingUsd, FaImages } from 'react-icons/fa';
import { AnimatePresence, motion } from 'framer-motion';

const CATEGORIES = ['Agriculture', 'Industry', 'Infrastructure', 'Tourism', 'Health', 'Education', 'Technology', 'Livestock'];
const STATUSES = ['Open', 'In Progress', 'Closed'];

const STATUS_COLORS = {
    'Open': 'bg-emerald-100 text-emerald-700 border-emerald-200',
    'In Progress': 'bg-amber-100 text-amber-700 border-amber-200',
    'Closed': 'bg-slate-100 text-slate-700 border-slate-200'
};

export default function AdminInvestmentsPage() {
    const [investments, setInvestments] = useState<InvestmentOpportunity[]>([]);
    const [loading, setLoading] = useState(true);
    const [uploading, setUploading] = useState(false);
    const [error, setError] = useState('');
    const [success, setSuccess] = useState('');
    const [searchQuery, setSearchQuery] = useState('');
    const [selectedCategory, setSelectedCategory] = useState('All');

    // Editing state
    const [editingId, setEditingId] = useState<number | null>(null);
    const [showModal, setShowModal] = useState(false);

    // Form state
    const [formData, setFormData] = useState({
        title_en: '',
        title_am: '',
        title_or: '',
        description_en: '',
        description_am: '',
        description_or: '',
        category: 'Agriculture',
        location: '',
        location_am: '',
        location_or: '',
        budget: '',
        incentives_en: '',
        incentives_am: '',
        incentives_or: '',
        contact_name: '',
        contact_phone: '',
        contact_email: '',
        status: 'Open' as 'Open' | 'In Progress' | 'Closed'
    });
    const [thumbnail, setThumbnail] = useState<File | null>(null);
    const [previewUrl, setPreviewUrl] = useState('');
    const fileInputRef = useRef<HTMLInputElement>(null);

    const loadInvestments = useCallback(async () => {
        setLoading(true);
        try {
            const data = await fetchInvestments();
            setInvestments(data);
        } finally {
            setLoading(false);
        }
    }, []);

    useEffect(() => { loadInvestments(); }, [loadInvestments]);

    const openAdd = () => {
        resetForm();
        setShowModal(true);
    };

    const handleEdit = (inv: InvestmentOpportunity) => {
        setEditingId(inv.id);
        setFormData({
            title_en: inv.title_en || '',
            title_am: inv.title_am || '',
            title_or: inv.title_or || '',
            description_en: inv.description_en || '',
            description_am: inv.description_am || '',
            description_or: inv.description_or || '',
            category: inv.category || 'Agriculture',
            location: inv.location || '',
            location_am: inv.location_am || '',
            location_or: inv.location_or || '',
            budget: inv.budget || '',
            incentives_en: inv.incentives_en || '',
            incentives_am: inv.incentives_am || '',
            incentives_or: inv.incentives_or || '',
            contact_name: inv.contact_name || '',
            contact_phone: inv.contact_phone || '',
            contact_email: inv.contact_email || '',
            status: inv.status || 'Open'
        });
        setThumbnail(null);
        setPreviewUrl(inv.thumbnail_url ? getFileUrl(inv.thumbnail_url) : '');
        setShowModal(true);
    };

    const resetForm = () => {
        setEditingId(null);
        setFormData({
            title_en: '',
            title_am: '',
            title_or: '',
            description_en: '',
            description_am: '',
            description_or: '',
            category: 'Agriculture',
            location: '',
            location_am: '',
            location_or: '',
            budget: '',
            incentives_en: '',
            incentives_am: '',
            incentives_or: '',
            contact_name: '',
            contact_phone: '',
            contact_email: '',
            status: 'Open'
        });
        setThumbnail(null);
        setPreviewUrl('');
        if (fileInputRef.current) fileInputRef.current.value = '';
        setError('');
        setSuccess('');
    };

    const handleThumbnailChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (file) {
            setThumbnail(file);
            setPreviewUrl(URL.createObjectURL(file));
        }
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setError('');
        setSuccess('');
        setUploading(true);

        try {
            const data = new FormData();
            Object.entries(formData).forEach(([key, value]) => {
                data.append(key, value);
            });
            if (thumbnail) data.append('thumbnail', thumbnail);

            const token = localStorage.getItem('adminToken') || '';

            if (editingId) {
                await updateInvestment(editingId, data, token);
                setSuccess('Opportunity updated successfully.');
            } else {
                await createInvestment(data, token);
                setSuccess('Opportunity created successfully.');
            }

            setShowModal(false);
            loadInvestments();
        } catch (err: any) {
            setError(err.message || 'Operation failed');
        } finally {
            setUploading(false);
        }
    };

    const handleDelete = async (id: number) => {
        if (!confirm('Permanently delete this investment opportunity?')) return;
        try {
            const token = localStorage.getItem('adminToken') || '';
            await deleteInvestment(id, token);
            setInvestments(prev => prev.filter(i => i.id !== id));
        } catch (err: any) {
            alert(err.message || 'Delete failed');
        }
    };

    const filteredInvestments = investments.filter(inv => {
        const matchesSearch = inv.title_en.toLowerCase().includes(searchQuery.toLowerCase()) ||
            (inv.category || '').toLowerCase().includes(searchQuery.toLowerCase());
        const matchesCategory = selectedCategory === 'All' || inv.category === selectedCategory;
        return matchesSearch && matchesCategory;
    });

    return (
        <AdminLayout>
            <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h1 className="text-3xl font-bold text-gray-900 tracking-tight">Investment Opportunities</h1>
                    <p className="text-gray-500 mt-1">Strategic development projects and sector-specific potentials.</p>
                </div>
                <button
                    onClick={openAdd}
                    className="bg-primary text-white px-6 py-2.5 rounded-xl flex items-center gap-2 hover:bg-opacity-90 transition-all shadow-lg shadow-primary/20 font-medium whitespace-nowrap"
                >
                    <FaPlus className="text-sm" /> New Opportunity
                </button>
            </div>

            {/* Search & Stats Summary */}
            <div className="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
                <div className="lg:col-span-3 bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-4">
                    <div className="relative flex-1">
                        <FaSearch className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" />
                        <input
                            type="text"
                            placeholder="Find opportunities by sector, title, or location..."
                            className="w-full pl-12 pr-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all text-gray-700"
                            value={searchQuery}
                            onChange={(e) => setSearchQuery(e.target.value)}
                        />
                    </div>
                    <div className="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0 scrollbar-hide">
                        {['All', ...CATEGORIES.slice(0, 4)].map(cat => (
                            <button
                                key={cat}
                                onClick={() => setSelectedCategory(cat)}
                                className={`px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-all ${selectedCategory === cat
                                    ? 'bg-primary text-white shadow-md shadow-primary/20'
                                    : 'bg-gray-50 text-gray-600 hover:bg-gray-100'
                                    }`}
                            >
                                {cat}
                            </button>
                        ))}
                    </div>
                </div>
                <div className="bg-gradient-to-br from-primary to-blue-700 p-6 rounded-2xl shadow-lg shadow-primary/20 text-white flex items-center justify-between">
                    <div>
                        <p className="text-white/70 text-xs font-bold uppercase tracking-wider">Active Potentials</p>
                        <h3 className="text-3xl font-black mt-1">{investments.length}</h3>
                    </div>
                    <div className="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <FaHandHoldingUsd className="text-2xl" />
                    </div>
                </div>
            </div>

            {/* List Section */}
            <div className="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead className="bg-gray-50/50 border-b border-gray-100">
                            <tr>
                                <th className="px-8 py-5 font-bold text-gray-400 text-[10px] uppercase tracking-widest">Opportunity Detail</th>
                                <th className="px-6 py-5 font-bold text-gray-400 text-[10px] uppercase tracking-widest">Geography</th>
                                <th className="px-6 py-5 font-bold text-gray-400 text-[10px] uppercase tracking-widest">Est. Requirement</th>
                                <th className="px-6 py-5 font-bold text-gray-400 text-[10px] uppercase tracking-widest">Current Status</th>
                                <th className="px-8 py-5 font-bold text-gray-400 text-[10px] uppercase tracking-widest text-right">Registry Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-50">
                            <AnimatePresence mode="popLayout">
                                {loading ? (
                                    [...Array(5)].map((_, i) => (
                                        <tr key={`skeleton-${i}`} className="animate-pulse">
                                            <td className="px-8 py-6">
                                                <div className="flex items-center gap-4">
                                                    <div className="w-12 h-12 bg-gray-100 rounded-xl" />
                                                    <div className="space-y-2">
                                                        <div className="h-4 bg-gray-100 rounded w-48" />
                                                        <div className="h-3 bg-gray-50 rounded w-24" />
                                                    </div>
                                                </div>
                                            </td>
                                            <td colSpan={4}><div className="h-4 bg-gray-50 rounded mx-6 w-1/2" /></td>
                                        </tr>
                                    ))
                                ) : filteredInvestments.length === 0 ? (
                                    <tr>
                                        <td colSpan={5} className="px-8 py-20 text-center">
                                            <div className="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                                <FaBriefcase className="text-3xl text-gray-200" />
                                            </div>
                                            <h3 className="text-lg font-bold text-gray-900 mb-1">No Investment Records</h3>
                                            <p className="text-gray-500 max-w-xs mx-auto">Adjust your search criteria or add the zone's first strategic opportunity.</p>
                                        </td>
                                    </tr>
                                ) : filteredInvestments.map(inv => (
                                    <motion.tr
                                        key={inv.id}
                                        layout
                                        initial={{ opacity: 0 }}
                                        animate={{ opacity: 1 }}
                                        exit={{ opacity: 0 }}
                                        className="group hover:bg-gray-50/50 transition-all"
                                    >
                                        <td className="px-8 py-6">
                                            <div className="flex items-center gap-4">
                                                <div className="relative shrink-0">
                                                    {inv.thumbnail_url ? (
                                                        <img src={getFileUrl(inv.thumbnail_url)} className="w-14 h-14 rounded-2xl object-cover shadow-sm group-hover:scale-110 transition-transform duration-500" alt="" />
                                                    ) : (
                                                        <div className="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-500 border border-indigo-100">
                                                            <FaCoins size={20} />
                                                        </div>
                                                    )}
                                                    <div className="absolute -top-2 -right-2 w-6 h-6 bg-white rounded-full shadow-sm flex items-center justify-center border border-gray-100">
                                                        <FaBullseye className="text-primary text-[10px]" />
                                                    </div>
                                                </div>
                                                <div>
                                                    <span className="font-bold text-gray-900 block group-hover:text-primary transition-colors">{inv.title_en}</span>
                                                    <div className="flex items-center gap-2 mt-1">
                                                        <span className="text-[10px] font-black text-primary uppercase tracking-tighter bg-primary/5 px-2 py-0.5 rounded italic">{inv.category}</span>
                                                        <span className="text-[10px] text-gray-400 font-medium">#{inv.id.toString().padStart(4, '0')}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td className="px-6 py-6">
                                            <div className="flex flex-col">
                                                <span className="text-sm text-gray-700 font-semibold">{inv.location || 'Multiple Sites'}</span>
                                                <span className="text-[10px] text-gray-400 flex items-center gap-1 font-medium"><FaMapMarkerAlt className="text-primary/40" /> Zone Designated Land</span>
                                            </div>
                                        </td>
                                        <td className="px-6 py-6">
                                            <div className="flex flex-col">
                                                <span className="text-sm font-black text-emerald-600 tracking-tight">{inv.budget || 'TBD'}</span>
                                                <span className="text-[10px] text-gray-400 font-medium italic">Estimated CapEx</span>
                                            </div>
                                        </td>
                                        <td className="px-6 py-6">
                                            <span className={`px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border ${STATUS_COLORS[inv.status as keyof typeof STATUS_COLORS]}`}>
                                                {inv.status}
                                            </span>
                                        </td>
                                        <td className="px-8 py-6 text-right">
                                            <div className="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity translate-x-2 group-hover:translate-x-0 transition-transform">
                                                <button
                                                    onClick={() => handleEdit(inv)}
                                                    className="w-10 h-10 flex items-center justify-center bg-white text-blue-600 border border-gray-100 rounded-xl shadow-sm hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all active:scale-95"
                                                >
                                                    <FaEdit size={14} />
                                                </button>
                                                <button
                                                    onClick={() => handleDelete(inv.id)}
                                                    className="w-10 h-10 flex items-center justify-center bg-white text-rose-600 border border-gray-100 rounded-xl shadow-sm hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all active:scale-95"
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

            {/* Modal Overlay */}
            <AnimatePresence>
                {showModal && (
                    <div className="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 pt-10 overflow-y-auto">
                        <motion.div
                            initial={{ opacity: 0, scale: 0.95, y: 20 }}
                            animate={{ opacity: 1, scale: 1, y: 0 }}
                            exit={{ opacity: 0, scale: 0.95, y: 20 }}
                            className="bg-white rounded-[2rem] shadow-2xl w-full max-w-4xl my-auto overflow-hidden border border-gray-100 flex flex-col max-h-[90vh]"
                        >
                            <div className="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/30 shrink-0">
                                <div className="flex items-center gap-4">
                                    <div className="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary">
                                        {editingId ? <FaEdit className="text-xl" /> : <FaPlus className="text-xl" />}
                                    </div>
                                    <div>
                                        <h2 className="text-2xl font-black text-gray-900 tracking-tight">
                                            {editingId ? 'Refine Potential' : 'Onboard Opportunity'}
                                        </h2>
                                        <p className="text-gray-500 text-sm font-medium">Capture the essence of this zone investment.</p>
                                    </div>
                                </div>
                                <button onClick={() => setShowModal(false)} className="w-12 h-12 flex items-center justify-center rounded-2xl text-gray-400 hover:bg-white hover:text-rose-500 transition-all border border-transparent hover:border-gray-200 text-2xl group">
                                    <FaTimes className="group-hover:rotate-90 transition-transform" />
                                </button>
                            </div>

                            <form onSubmit={handleSubmit} className="p-8 space-y-8 overflow-y-auto custom-scrollbar">
                                {error && (
                                    <div className="text-rose-600 bg-rose-50 border border-rose-100 p-4 rounded-2xl text-sm font-bold flex items-center gap-3">
                                        <FaTimesCircle className="shrink-0" /> {error}
                                    </div>
                                )}

                                {/* Multilingual Titles */}
                                <div className="space-y-4">
                                    <div className="flex items-center gap-2 text-primary font-black text-[10px] uppercase tracking-[0.2em]">
                                        <FaInfoCircle /> Primary Identification
                                    </div>
                                    <div className="space-y-4 bg-gray-50/50 p-6 rounded-3xl border border-gray-100">
                                        <div className="space-y-1">
                                            <label className="text-[10px] font-black text-gray-400 ml-1 uppercase">Official Title (English)</label>
                                            <input
                                                type="text"
                                                required
                                                value={formData.title_en}
                                                onChange={e => setFormData({ ...formData, title_en: e.target.value })}
                                                className="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all font-bold placeholder:font-medium"
                                                placeholder="e.g. Tekeze Hydro-Power Integration Phase II"
                                            />
                                        </div>
                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div className="space-y-1">
                                                <label className="text-[10px] font-black text-gray-400 ml-1 uppercase">ርዕስ (አማርኛ)</label>
                                                <input
                                                    type="text"
                                                    value={formData.title_am}
                                                    onChange={e => setFormData({ ...formData, title_am: e.target.value })}
                                                    className="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all font-amharic font-bold"
                                                    placeholder="የኢንቨስትመንት ርዕስ..."
                                                />
                                            </div>
                                            <div className="space-y-1">
                                                <label className="text-[10px] font-black text-gray-400 ml-1 uppercase">Mata-duree (Oromoo)</label>
                                                <input
                                                    type="text"
                                                    value={formData.title_or}
                                                    onChange={e => setFormData({ ...formData, title_or: e.target.value })}
                                                    className="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all font-bold"
                                                    placeholder="Mataduree invastimantii..."
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {/* Core Parameters */}
                                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div className="space-y-1">
                                        <label className="text-[10px] font-black text-gray-400 ml-1 uppercase">Sector Classification</label>
                                        <select
                                            value={formData.category}
                                            onChange={e => setFormData({ ...formData, category: e.target.value })}
                                            className="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all font-bold appearance-none bg-no-repeat bg-[right_1.5rem_center] bg-[length:1rem]"
                                            style={{ backgroundImage: `url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E")` }}
                                        >
                                            {CATEGORIES.map(cat => <option key={cat}>{cat}</option>)}
                                        </select>
                                    </div>
                                    <div className="space-y-1">
                                        <label className="text-[10px] font-black text-gray-400 ml-1 uppercase">Est. Investment Vol.</label>
                                        <input
                                            type="text"
                                            value={formData.budget}
                                            onChange={e => setFormData({ ...formData, budget: e.target.value })}
                                            className="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all font-black text-emerald-600"
                                            placeholder="e.g. 50M ETB"
                                        />
                                    </div>
                                    <div className="space-y-1">
                                        <label className="text-[10px] font-black text-gray-400 ml-1 uppercase">Current Pipeline Status</label>
                                        <select
                                            value={formData.status}
                                            onChange={e => setFormData({ ...formData, status: e.target.value as any })}
                                            className="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all font-bold appearance-none bg-no-repeat bg-[right_1.5rem_center] bg-[length:1rem]"
                                            style={{ backgroundImage: `url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E")` }}
                                        >
                                            {STATUSES.map(s => <option key={s}>{s}</option>)}
                                        </select>
                                    </div>
                                </div>

                                {/* Geographic Context */}
                                <div className="space-y-4">
                                    <div className="flex items-center gap-2 text-primary font-black text-[10px] uppercase tracking-[0.2em]">
                                        <FaMapMarkerAlt /> Geographic Designation
                                    </div>
                                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div className="space-y-1">
                                            <input
                                                type="text"
                                                value={formData.location}
                                                onChange={e => setFormData({ ...formData, location: e.target.value })}
                                                className="w-full px-5 py-4 bg-gray-50 border-gray-100 border rounded-2xl focus:ring-2 focus:ring-primary/20 outline-none font-bold"
                                                placeholder="Location (EN)"
                                            />
                                        </div>
                                        <div className="space-y-1">
                                            <input
                                                type="text"
                                                value={formData.location_am}
                                                onChange={e => setFormData({ ...formData, location_am: e.target.value })}
                                                className="w-full px-5 py-4 bg-gray-50 border-gray-100 border rounded-2xl focus:ring-2 focus:ring-primary/20 outline-none font-amharic font-bold"
                                                placeholder="አካባቢ (አማርኛ)"
                                            />
                                        </div>
                                        <div className="space-y-1">
                                            <input
                                                type="text"
                                                value={formData.location_or}
                                                onChange={e => setFormData({ ...formData, location_or: e.target.value })}
                                                className="w-full px-5 py-4 bg-gray-50 border-gray-100 border rounded-2xl focus:ring-2 focus:ring-primary/20 outline-none font-bold"
                                                placeholder="Bakka (Oromoo)"
                                            />
                                        </div>
                                    </div>
                                </div>

                                {/* Descriptive Content - Tabs-like structure for languages */}
                                <div className="space-y-6">
                                    <div className="flex items-center gap-2 text-primary font-black text-[10px] uppercase tracking-[0.2em]">
                                        <FaCalendarAlt /> Detailed Narrative & Benefits
                                    </div>

                                    <div className="bg-slate-50 rounded-[2.5rem] p-8 border border-slate-100">
                                        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                            {/* English Section */}
                                            <div className="space-y-4">
                                                <div className="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                                    <span className="w-1.5 h-1.5 bg-blue-500 rounded-full" /> Narrative & Incentives (EN)
                                                </div>
                                                <textarea
                                                    value={formData.description_en}
                                                    onChange={e => setFormData({ ...formData, description_en: e.target.value })}
                                                    className="w-full px-6 py-5 bg-white border border-slate-100 rounded-3xl focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 outline-none h-40 font-medium text-sm leading-relaxed"
                                                    placeholder="Opportunity description in English..."
                                                />
                                                <textarea
                                                    value={formData.incentives_en}
                                                    onChange={e => setFormData({ ...formData, incentives_en: e.target.value })}
                                                    className="w-full px-6 py-4 bg-white border border-slate-100 rounded-2xl focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 outline-none h-24 font-bold text-sm"
                                                    placeholder="Available incentives (English)..."
                                                />
                                            </div>

                                            {/* Amharic Section */}
                                            <div className="space-y-4">
                                                <div className="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                                    <span className="w-1.5 h-1.5 bg-amber-500 rounded-full" /> ዝርዝር መግለጫ እና ማበረታቻዎች (AM)
                                                </div>
                                                <textarea
                                                    value={formData.description_am}
                                                    onChange={e => setFormData({ ...formData, description_am: e.target.value })}
                                                    className="w-full px-6 py-5 bg-white border border-slate-100 rounded-3xl focus:ring-4 focus:ring-amber-500/5 focus:border-amber-500 outline-none h-40 font-amharic text-sm leading-relaxed"
                                                    placeholder="የኢንቨስትመንት ዝርዝር መግለጫ..."
                                                />
                                                <textarea
                                                    value={formData.incentives_am}
                                                    onChange={e => setFormData({ ...formData, incentives_am: e.target.value })}
                                                    className="w-full px-6 py-4 bg-white border border-slate-100 rounded-2xl focus:ring-4 focus:ring-amber-500/5 focus:border-amber-500 outline-none h-24 font-amharic font-bold text-sm"
                                                    placeholder="የሚሰጡ ማበረታቻዎች..."
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {/* Visual Representation & Contact */}
                                <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                    <div className="space-y-4">
                                        <div className="flex items-center gap-2 text-primary font-black text-[10px] uppercase tracking-[0.2em]">
                                            <FaImages className="text-primary/60" /> Visual Identity
                                        </div>
                                        <div className="relative group overflow-hidden rounded-[2rem] border-2 border-dashed border-gray-200 aspect-[2/1] bg-gray-50 hover:bg-white hover:border-primary/50 transition-all flex items-center justify-center">
                                            {previewUrl ? (
                                                <img src={previewUrl} className="w-full h-full object-cover" alt="Preview" />
                                            ) : (
                                                <div className="text-center p-6">
                                                    <div className="w-12 h-12 bg-white rounded-2xl shadow-sm flex items-center justify-center mx-auto mb-3 border border-gray-100">
                                                        <FaPlus className="text-gray-300" />
                                                    </div>
                                                    <p className="text-xs font-bold text-gray-400 uppercase tracking-widest leading-loose">Upload Representative <br /> Hero Image</p>
                                                </div>
                                            )}
                                            <input
                                                ref={fileInputRef}
                                                type="file"
                                                accept="image/*"
                                                onChange={handleThumbnailChange}
                                                className="absolute inset-0 opacity-0 cursor-pointer z-10"
                                            />
                                            {previewUrl && (
                                                <div className="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm">
                                                    <span className="text-white text-[10px] font-black uppercase tracking-[0.3em] bg-white/10 px-6 py-3 rounded-2xl border border-white/20">Change Visual</span>
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                    <div className="space-y-6">
                                        <div className="flex items-center gap-2 text-primary font-black text-[10px] uppercase tracking-[0.2em]">
                                            <FaInfoCircle className="text-primary/60" /> Focal Person
                                        </div>
                                        <div className="bg-gray-50 p-8 rounded-[2rem] border border-gray-100 space-y-4">
                                            <div className="space-y-1">
                                                <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Assignee / Contact</label>
                                                <input
                                                    type="text"
                                                    value={formData.contact_name}
                                                    onChange={e => setFormData({ ...formData, contact_name: e.target.value })}
                                                    className="w-full px-5 py-3.5 bg-white border border-gray-100 rounded-2xl focus:ring-2 focus:ring-primary/20 outline-none font-bold"
                                                    placeholder="Focal Person Name"
                                                />
                                            </div>
                                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <input
                                                    type="text"
                                                    value={formData.contact_phone}
                                                    onChange={e => setFormData({ ...formData, contact_phone: e.target.value })}
                                                    className="w-full px-5 py-3.5 bg-white border border-gray-100 rounded-2xl focus:ring-2 focus:ring-primary/20 outline-none font-bold text-sm"
                                                    placeholder="Contact Phone"
                                                />
                                                <input
                                                    type="email"
                                                    value={formData.contact_email}
                                                    onChange={e => setFormData({ ...formData, contact_email: e.target.value })}
                                                    className="w-full px-5 py-3.5 bg-white border border-gray-100 rounded-2xl focus:ring-2 focus:ring-primary/20 outline-none font-bold text-sm"
                                                    placeholder="Official Email"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {/* Form Footer */}
                                <div className="flex items-center justify-between pt-8 border-t border-gray-100 shrink-0">
                                    <button
                                        type="button"
                                        onClick={() => setShowModal(false)}
                                        className="text-gray-400 hover:text-gray-600 font-bold uppercase tracking-widest text-[10px] flex items-center gap-2 group"
                                    >
                                        <FaTimes className="group-hover:scale-125 transition-transform" /> Discard Changes
                                    </button>
                                    <div className="flex gap-4">
                                        <button
                                            type="submit"
                                            disabled={uploading}
                                            className="px-12 py-4 bg-gradient-to-r from-primary to-blue-700 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-xl shadow-primary/30 hover:shadow-primary/50 transition-all active:scale-95 disabled:opacity-50 flex items-center gap-3"
                                        >
                                            {uploading ? 'Processing Data...' : editingId ? (
                                                <><FaSave /> Commit Blueprint</>
                                            ) : (
                                                <><FaPlus /> Release Opportunity</>
                                            )}
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

// Add these to layout or global css if needed:
// .custom-scrollbar::-webkit-scrollbar { width: 4px; }
// .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
// .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
