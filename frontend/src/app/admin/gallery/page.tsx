'use client';
import { useState, useEffect, useCallback } from 'react';
import AdminLayout from '@/components/admin/AdminLayout';
import { FaPlus, FaEdit, FaTrash, FaTimes, FaImages, FaSearch, FaFilter, FaCheckCircle, FaTimesCircle, FaInfoCircle, FaSortAmountDown } from 'react-icons/fa';
import { GalleryItem, WoredaItem } from '@/lib/api';
import { AnimatePresence, motion } from 'framer-motion';

const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api';
const BACKEND_URL = process.env.NEXT_PUBLIC_BACKEND_URL || 'http://localhost:8000';

const PRESET_CATEGORIES = ['Events', 'Infrastructure', 'Agriculture', 'Health', 'Education', 'Culture', 'Other'];

interface FormState {
    title: string;
    title_am: string;
    title_or: string;
    category: string;
    woreda_id: string;
    sort_order: string;
    is_active: boolean;
}
const EMPTY: FormState = { title: '', title_am: '', title_or: '', category: 'Events', woreda_id: '', sort_order: '0', is_active: true };

export default function AdminGallery() {
    const [items, setItems] = useState<GalleryItem[]>([]);
    const [woredas, setWoredas] = useState<WoredaItem[]>([]);
    const [loading, setLoading] = useState(true);
    const [showModal, setShowModal] = useState(false);
    const [editing, setEditing] = useState<GalleryItem | null>(null);
    const [form, setForm] = useState<FormState>(EMPTY);
    const [imageFile, setImageFile] = useState<File | null>(null);
    const [previewUrl, setPreviewUrl] = useState('');
    const [saving, setSaving] = useState(false);
    const [error, setError] = useState('');
    const [searchQuery, setSearchQuery] = useState('');
    const [selectedCategory, setSelectedCategory] = useState('All');

    const token = typeof window !== 'undefined' ? localStorage.getItem('adminToken') || '' : '';

    const load = useCallback(async () => {
        setLoading(true);
        try {
            const [gRes, wRes] = await Promise.all([
                fetch(`${API_URL}/gallery/all`, { headers: { Authorization: `Bearer ${token}` }, cache: 'no-store' }),
                fetch(`${API_URL}/woredas/all`, { headers: { Authorization: `Bearer ${token}` }, cache: 'no-store' }),
            ]);
            if (gRes.ok) setItems(await gRes.json());
            if (wRes.ok) setWoredas(await wRes.json());
        } finally {
            setLoading(false);
        }
    }, [token]);

    useEffect(() => { load(); }, [load]);

    const openAdd = () => {
        setEditing(null); setForm(EMPTY); setImageFile(null); setPreviewUrl(''); setError(''); setShowModal(true);
    };
    const openEdit = (item: GalleryItem) => {
        setEditing(item);
        setForm({
            title: item.title || '',
            title_am: item.title_am || '',
            title_or: item.title_or || '',
            category: item.category || 'Events',
            woreda_id: item.woreda_id ? String(item.woreda_id) : '',
            sort_order: String(item.sort_order || 0),
            is_active: !!item.is_active,
        });
        setImageFile(null);
        setPreviewUrl(item.image_url ? `${BACKEND_URL}${item.image_url}` : '');
        setError('');
        setShowModal(true);
    };

    const set = (k: keyof FormState, v: string | boolean) => setForm(prev => ({ ...prev, [k]: v }));

    const handleImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (!file) return;
        setImageFile(file);
        setPreviewUrl(URL.createObjectURL(file));
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!form.title || !form.category) { setError('Title and category are required.'); return; }
        if (!editing && !imageFile) { setError('Please select an image to upload.'); return; }
        setSaving(true); setError('');
        try {
            const fd = new FormData();
            fd.append('title', form.title);
            fd.append('title_am', form.title_am);
            fd.append('title_or', form.title_or);
            fd.append('category', form.category);
            fd.append('sort_order', form.sort_order);
            fd.append('is_active', form.is_active ? '1' : '0');
            if (form.woreda_id) fd.append('woreda_id', form.woreda_id);
            if (imageFile) fd.append('image', imageFile);

            const url = editing ? `${API_URL}/gallery/${editing.id}?_method=PUT` : `${API_URL}/gallery`;
            const method = 'POST'; // Using POST + _method=PUT for multipart updates in Laravel

            const res = await fetch(url, { method, headers: { Authorization: `Bearer ${token}` }, body: fd });
            if (!res.ok) throw new Error((await res.json()).message || 'Failed');
            setShowModal(false);
            load();
        } catch (err: any) {
            setError(err.message);
        } finally {
            setSaving(false);
        }
    };

    const handleDelete = async (id: number, title: string) => {
        if (!confirm(`Delete "${title}"? This cannot be undone.`)) return;
        await fetch(`${API_URL}/gallery/${id}`, { method: 'DELETE', headers: { Authorization: `Bearer ${token}` } });
        setItems(prev => prev.filter(i => i.id !== id));
    };

    const filteredItems = items.filter(i => {
        const matchesSearch = (i.title || '').toLowerCase().includes(searchQuery.toLowerCase()) ||
            (i.category || '').toLowerCase().includes(searchQuery.toLowerCase());
        const matchesCategory = selectedCategory === 'All' || i.category === selectedCategory;
        return matchesSearch && matchesCategory;
    });

    return (
        <AdminLayout>
            <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h1 className="text-3xl font-bold text-gray-900 tracking-tight">Gallery Management</h1>
                    <p className="text-gray-500 mt-1">Curate and organize the visual storytelling of our zone.</p>
                </div>
                <button
                    onClick={openAdd}
                    className="bg-primary text-white px-6 py-2.5 rounded-xl flex items-center gap-2 hover:bg-opacity-90 transition-all shadow-lg shadow-primary/20 font-medium whitespace-nowrap"
                >
                    <FaPlus className="text-sm" /> Upload New Photo
                </button>
            </div>

            {/* Filters & Search */}
            <div className="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-8 space-y-4">
                <div className="flex flex-col md:flex-row gap-4">
                    <div className="relative flex-1">
                        <FaSearch className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" />
                        <input
                            type="text"
                            placeholder="Search photos by title or keyword..."
                            className="w-full pl-12 pr-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all text-gray-700"
                            value={searchQuery}
                            onChange={(e) => setSearchQuery(e.target.value)}
                        />
                    </div>
                    <div className="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0 scrollbar-hide">
                        <span className="text-xs font-bold text-gray-400 uppercase tracking-widest px-2 flex items-center gap-1">
                            <FaFilter className="text-[10px]" /> Filter:
                        </span>
                        {['All', ...PRESET_CATEGORIES].map(cat => (
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
            </div>

            {/* Photo Grid */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <AnimatePresence mode="popLayout">
                    {loading ? (
                        [...Array(8)].map((_, i) => (
                            <div key={`skeleton-${i}`} className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden animate-pulse aspect-[4/3] flex flex-col">
                                <div className="flex-1 bg-gray-100" />
                                <div className="p-4 space-y-2">
                                    <div className="h-4 bg-gray-100 rounded w-3/4" />
                                    <div className="h-3 bg-gray-50 rounded w-1/2" />
                                </div>
                            </div>
                        ))
                    ) : filteredItems.length === 0 ? (
                        <motion.div
                            initial={{ opacity: 0 }}
                            animate={{ opacity: 1 }}
                            className="col-span-full bg-white rounded-2xl border border-dashed border-gray-200 p-20 text-center"
                        >
                            <div className="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <FaImages className="text-3xl text-gray-300" />
                            </div>
                            <h3 className="text-lg font-bold text-gray-900 mb-2">No visuals found</h3>
                            <p className="text-gray-500 max-w-xs mx-auto mb-8">Try adjusting your filters or upload a new photo to begin your gallery.</p>
                            <button onClick={openAdd} className="text-primary font-bold hover:underline">Upload first photo</button>
                        </motion.div>
                    ) : (
                        filteredItems.map(item => (
                            <motion.div
                                key={item.id}
                                layout
                                initial={{ opacity: 0, scale: 0.9 }}
                                animate={{ opacity: 1, scale: 1 }}
                                exit={{ opacity: 0, scale: 0.9 }}
                                transition={{ duration: 0.2 }}
                                className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden group hover:shadow-xl transition-all duration-500 flex flex-col"
                            >
                                <div className="relative aspect-[4/3] bg-gray-100 overflow-hidden">
                                    {/* eslint-disable-next-line @next/next/no-img-element */}
                                    <img
                                        src={`${BACKEND_URL}${item.image_url}`}
                                        alt={item.title}
                                        className="w-full h-full object-cover group-hover:scale-110 transition duration-700 ease-in-out"
                                    />
                                    <div className="absolute top-3 left-3 flex gap-2">
                                        <span className="text-[10px] font-bold text-white bg-black/40 backdrop-blur-md px-2.5 py-1 rounded-full uppercase tracking-widest border border-white/10">
                                            {item.category}
                                        </span>
                                        {!item.is_active && (
                                            <span className="text-[10px] font-bold text-white bg-rose-500/80 backdrop-blur-md px-2.5 py-1 rounded-full uppercase tracking-widest">
                                                Hidden
                                            </span>
                                        )}
                                    </div>
                                    <div className="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                                        <div className="text-white w-full">
                                            <div className="flex gap-2 w-full">
                                                <button
                                                    onClick={() => openEdit(item)}
                                                    className="flex-1 bg-white/20 backdrop-blur-md hover:bg-white/40 text-white p-2 rounded-xl transition-all"
                                                >
                                                    <FaEdit className="mx-auto" />
                                                </button>
                                                <button
                                                    onClick={() => handleDelete(item.id, item.title)}
                                                    className="flex-1 bg-rose-500/40 backdrop-blur-md hover:bg-rose-500/60 text-white p-2 rounded-xl transition-all"
                                                >
                                                    <FaTrash className="mx-auto" />
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div className="p-4 flex-1 flex flex-col justify-between">
                                    <div>
                                        <h3 className="font-bold text-gray-900 group-hover:text-primary transition-colors truncate">{item.title}</h3>
                                        <div className="flex items-center gap-2 mt-1">
                                            <span className="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">
                                                # Order {item.sort_order}
                                            </span>
                                            {item.woreda_name && (
                                                <span className="bg-gray-50 text-gray-500 text-[10px] px-1.5 py-0.5 rounded italic">
                                                    @{item.woreda_name}
                                                </span>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            </motion.div>
                        ))
                    )}
                </AnimatePresence>
            </div>

            {/* Modal */}
            <AnimatePresence>
                {showModal && (
                    <div className="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto">
                        <motion.div
                            initial={{ opacity: 0, scale: 0.95, y: 20 }}
                            animate={{ opacity: 1, scale: 1, y: 0 }}
                            exit={{ opacity: 0, scale: 0.95, y: 20 }}
                            className="bg-white rounded-3xl shadow-2xl w-full max-w-2xl my-8 overflow-hidden border border-gray-100"
                        >
                            <div className="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50 sticky top-0 z-10">
                                <div>
                                    <h2 className="text-2xl font-bold text-gray-900">
                                        {editing ? 'Refine Gallery Item' : 'New Visual Entry'}
                                    </h2>
                                    <p className="text-gray-500 text-sm mt-1">Populate the gallery with zone visuals.</p>
                                </div>
                                <button onClick={() => setShowModal(false)} className="w-10 h-10 flex items-center justify-center rounded-full text-gray-400 hover:bg-white hover:text-gray-600 transition-all border border-transparent hover:border-gray-200 text-xl">×</button>
                            </div>

                            <form onSubmit={handleSubmit} className="p-8 space-y-8">
                                {error && (
                                    <motion.div
                                        initial={{ opacity: 0, y: -10 }}
                                        animate={{ opacity: 1, y: 0 }}
                                        className="text-rose-600 bg-rose-50 border border-rose-100 p-4 rounded-2xl text-sm flex items-center gap-3"
                                    >
                                        <FaTimesCircle className="shrink-0" />
                                        {error}
                                    </motion.div>
                                )}

                                {/* Image Section */}
                                <div className="space-y-4">
                                    <div className="flex items-center gap-2 text-primary font-bold text-sm uppercase tracking-wider">
                                        <FaImages /> Media Selection
                                    </div>
                                    <div className="relative group">
                                        <div className={`w-full aspect-video rounded-3xl border-2 border-dashed transition-all overflow-hidden flex flex-col items-center justify-center gap-1 ${previewUrl ? 'border-transparent' : 'border-gray-200 bg-gray-50'}`}>
                                            {previewUrl ? (
                                                // eslint-disable-next-line @next/next/no-img-element
                                                <img src={previewUrl} alt="Preview" className="w-full h-full object-cover" />
                                            ) : (
                                                <>
                                                    <div className="w-16 h-16 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center mb-2">
                                                        <FaPlus className="text-gray-300" />
                                                    </div>
                                                    <span className="text-gray-900 font-bold">Select Zone Photo</span>
                                                    <span className="text-gray-400 text-xs text-center px-4">Upload high resolution JPG or PNG images.</span>
                                                </>
                                            )}
                                        </div>
                                        <input type="file" accept="image/*" className="absolute inset-0 opacity-0 cursor-pointer" onChange={handleImageChange} />
                                        {previewUrl && (
                                            <div className="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                                                <span className="text-white font-bold bg-white/20 backdrop-blur-md px-4 py-2 rounded-xl">Replace Photo</span>
                                            </div>
                                        )}
                                    </div>
                                </div>

                                {/* Localized Titles */}
                                <div className="space-y-4">
                                    <div className="flex items-center gap-2 text-primary font-bold text-sm uppercase tracking-wider">
                                        <FaInfoCircle /> Identification
                                    </div>
                                    <div className="grid grid-cols-1 gap-4">
                                        <div className="space-y-1">
                                            <label className="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Caption / Title (EN)</label>
                                            <input
                                                value={form.title}
                                                onChange={e => set('title', e.target.value)}
                                                className="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all font-medium"
                                                placeholder="e.g. Traditional Coffee Ceremony"
                                                required
                                            />
                                        </div>
                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div className="space-y-1">
                                                <label className="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">ርዕስ (AM)</label>
                                                <input
                                                    value={form.title_am}
                                                    onChange={e => set('title_am', e.target.value)}
                                                    className="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all font-amharic"
                                                    placeholder="የቡና ሥነ-ሥርዓት"
                                                />
                                            </div>
                                            <div className="space-y-1">
                                                <label className="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Mata-duree (OR)</label>
                                                <input
                                                    value={form.title_or}
                                                    onChange={e => set('title_or', e.target.value)}
                                                    className="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all"
                                                    placeholder="..."
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {/* Categorization */}
                                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div className="space-y-1">
                                        <label className="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1 text-primary">Category</label>
                                        <select
                                            value={form.category}
                                            onChange={e => set('category', e.target.value)}
                                            className="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all font-bold appearance-none bg-no-repeat"
                                            style={{ backgroundImage: 'url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%236b7280\'%3E%3Cpath stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 9l-7 7-7-7\'%3E%3C/path%3E%3C/svg%3E")', backgroundPosition: 'right 1rem center', backgroundSize: '1.2rem' }}
                                        >
                                            {PRESET_CATEGORIES.map(c => <option key={c} value={c}>{c}</option>)}
                                        </select>
                                    </div>
                                    <div className="space-y-1">
                                        <label className="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Woreda Association</label>
                                        <select
                                            value={form.woreda_id}
                                            onChange={e => set('woreda_id', e.target.value)}
                                            className="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all font-medium appearance-none"
                                            style={{ backgroundImage: 'url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%236b7280\'%3E%3Cpath stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 9l-7 7-7-7\'%3E%3C/path%3E%3C/svg%3E")', backgroundPosition: 'right 1rem center', backgroundSize: '1.2rem' }}
                                        >
                                            <option value="">Zone-wide</option>
                                            {woredas.map(w => <option key={w.id} value={String(w.id)}>{w.name_en}</option>)}
                                        </select>
                                    </div>
                                    <div className="space-y-1">
                                        <label className="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Display Order</label>
                                        <input
                                            type="number"
                                            value={form.sort_order}
                                            onChange={e => set('sort_order', e.target.value)}
                                            className="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all font-bold"
                                            min={0}
                                        />
                                    </div>
                                </div>

                                <div className="flex items-center gap-4 p-5 bg-gray-50 rounded-3xl border border-gray-100">
                                    <div className="relative inline-flex items-center cursor-pointer">
                                        <input
                                            type="checkbox"
                                            id="gallery-active"
                                            className="sr-only peer"
                                            checked={!!form.is_active}
                                            onChange={e => set('is_active', e.target.checked)}
                                        />
                                        <div className="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                        <label htmlFor="gallery-active" className="ml-3 text-sm font-bold text-gray-700 uppercase tracking-widest">Public Visibility</label>
                                    </div>
                                </div>

                                <div className="flex justify-end gap-3 pt-4 sticky bottom-0 bg-white border-t border-gray-50 py-4 -mx-8 px-8">
                                    <button type="button" onClick={() => setShowModal(false)}
                                        className="px-6 py-3 text-gray-500 font-bold hover:text-gray-900 transition-colors">
                                        Discard
                                    </button>
                                    <button
                                        type="submit"
                                        disabled={saving}
                                        className="px-10 py-3 bg-gradient-to-r from-primary to-blue-700 text-white rounded-2xl font-bold shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all active:scale-95 disabled:opacity-50"
                                    >
                                        {saving ? 'Processing...' : editing ? 'Update Visual' : 'Finalize Upload'}
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
