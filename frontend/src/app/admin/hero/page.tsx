'use client';
import { useState, useEffect, useCallback } from 'react';
import AdminLayout from '@/components/admin/AdminLayout';
import { FaPlus, FaTrash, FaEdit, FaTimes, FaImage, FaVideo } from 'react-icons/fa';

interface HeroSlide {
    id: number;
    title_en: string;
    subtitle_en: string;
    title_am?: string;
    subtitle_am?: string;
    title_or?: string;
    subtitle_or?: string;
    media_url: string;
    media_type: 'image' | 'video';
    cta_text: string;
    cta_text_am?: string;
    cta_text_or?: string;
    cta_url: string;
    sort_order: number;
    is_active: boolean;
}

type SlideFormData = {
    title_en: string;
    subtitle_en: string;
    title_am: string;
    subtitle_am: string;
    title_or: string;
    subtitle_or: string;
    media_type: 'image' | 'video';
    cta_text: string;
    cta_text_am: string;
    cta_text_or: string;
    cta_url: string;
    sort_order: number;
    is_active: boolean;
};

const EMPTY_FORM: SlideFormData = {
    title_en: '',
    subtitle_en: '',
    title_am: '',
    subtitle_am: '',
    title_or: '',
    subtitle_or: '',
    media_type: 'image',
    cta_text: '',
    cta_text_am: '',
    cta_text_or: '',
    cta_url: '',
    sort_order: 0,
    is_active: true,
};

const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api';
const BACKEND_URL = process.env.NEXT_PUBLIC_BACKEND_URL || 'http://localhost:8000';

export default function AdminHeroPage() {
    const [slides, setSlides] = useState<HeroSlide[]>([]);
    const [loading, setLoading] = useState(true);
    const [showModal, setShowModal] = useState(false);
    const [editingSlide, setEditingSlide] = useState<HeroSlide | null>(null);
    const [form, setForm] = useState<SlideFormData>(EMPTY_FORM);
    const [mediaFile, setMediaFile] = useState<File | null>(null);
    const [saving, setSaving] = useState(false);
    const [error, setError] = useState('');
    const [toast, setToast] = useState<{ msg: string; type: 'success' | 'error' } | null>(null);
    const [activeTab, setActiveTab] = useState<'en' | 'am' | 'or' | 'media'>('en');

    const showToast = (msg: string, type: 'success' | 'error' = 'success') => {
        setToast({ msg, type });
        setTimeout(() => setToast(null), 4000);
    };

    const token = typeof window !== 'undefined' ? localStorage.getItem('adminToken') || '' : '';

    const loadSlides = useCallback(async () => {
        setLoading(true);
        try {
            const res = await fetch(`${API_URL}/hero/all`, {
                headers: { Authorization: `Bearer ${token}` },
                cache: 'no-store',
            });
            if (res.ok) setSlides(await res.json());
        } catch (e) {
            console.error(e);
        } finally {
            setLoading(false);
        }
    }, [token]);

    useEffect(() => { loadSlides(); }, [loadSlides]);

    const openAdd = () => {
        setEditingSlide(null);
        setForm(EMPTY_FORM);
        setMediaFile(null);
        setError('');
        setActiveTab('media');
        setShowModal(true);
    };

    const openEdit = (slide: HeroSlide) => {
        setEditingSlide(slide);
        setForm({
            title_en: slide.title_en || '',
            subtitle_en: slide.subtitle_en || '',
            title_am: slide.title_am || '',
            subtitle_am: slide.subtitle_am || '',
            title_or: slide.title_or || '',
            subtitle_or: slide.subtitle_or || '',
            media_type: slide.media_type,
            cta_text: slide.cta_text || '',
            cta_text_am: slide.cta_text_am || '',
            cta_text_or: slide.cta_text_or || '',
            cta_url: slide.cta_url || '',
            sort_order: slide.sort_order,
            is_active: slide.is_active,
        });
        setMediaFile(null);
        setError('');
        setActiveTab('en');
        setShowModal(true);
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!editingSlide && !mediaFile) {
            setError('Please select an image or video file for the slide.');
            return;
        }
        setSaving(true);
        setError('');

        try {
            if (editingSlide) {
                const isMultipart = !!mediaFile;
                let body: any;

                if (isMultipart) {
                    body = new FormData();
                    body.append('media', mediaFile!);
                    body.append('_method', 'PUT'); // Spoofing for Laravel
                    Object.entries(form).forEach(([k, v]) => {
                        body.append(k, String(v));
                    });
                } else {
                    body = JSON.stringify({ ...form, is_active: form.is_active ? 1 : 0 });
                }

                const res = await fetch(`${API_URL}/hero/${editingSlide.id}`, {
                    method: 'POST', // Always POST for multipart or PUT spoofing
                    headers: isMultipart ? { Authorization: `Bearer ${token}` } : {
                        'Content-Type': 'application/json',
                        'X-HTTP-Method-Override': 'PUT',
                        Authorization: (token ? `Bearer ${token}` : ''),
                    },
                    body: body,
                });
                if (!res.ok) throw new Error((await res.json()).message || 'Failed to update');
            } else {
                const fd = new FormData();
                fd.append('media', mediaFile!);
                Object.entries(form).forEach(([k, v]) => {
                    fd.append(k, String(v));
                });

                const res = await fetch(`${API_URL}/hero`, {
                    method: 'POST',
                    headers: { Authorization: `Bearer ${token}` },
                    body: fd,
                });
                if (!res.ok) throw new Error((await res.json()).message || 'Failed to create slide');
            }

            const res = await fetch(`${API_URL}/hero`, {
                method: 'POST',
                headers: { Authorization: `Bearer ${token}` },
                body: fd,
            });
            if (!res.ok) throw new Error((await res.json()).message || 'Failed to create slide');
        }
            setShowModal(false);
        showToast(editingSlide ? 'Slide updated successfully!' : 'Slide created successfully!');
        loadSlides();
    } catch (err: any) {
        setError(err.message || 'An error occurred.');
        showToast(err.message || 'Action failed', 'error');
    } finally {
        setSaving(false);
    }
};

const handleDelete = async (id: number) => {
    if (!confirm('Delete this slide? This cannot be undone.')) return;
    try {
        await fetch(`${API_URL}/hero/${id}`, {
            method: 'DELETE',
            headers: { Authorization: `Bearer ${token}` },
        });
        setSlides((prev) => prev.filter((s) => s.id !== id));
        showToast('Slide deleted successfully');
    } catch {
        showToast('Failed to delete slide', 'error');
    }
};

return (
    <AdminLayout>
        {/* Toast Notification */}
        {toast && (
            <div className={`fixed bottom-10 right-10 z-[100] px-8 py-4 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.3)] transition-all flex items-center gap-4 border border-white/10 backdrop-blur-md scale-110
                    ${toast.type === 'success' ? 'bg-green-600/90 text-white' : 'bg-red-600/90 text-white'}`}>
                <div className="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-xl">
                    {toast.type === 'success' ? '✓' : '✕'}
                </div>
                <div>
                    <div className="font-black text-lg uppercase tracking-tight">
                        {toast.type === 'success' ? 'Success' : 'Error'}
                    </div>
                    <div className="text-sm opacity-90">{toast.msg}</div>
                </div>
            </div>
        )}

        <div className="flex justify-between items-center mb-6">
            <div>
                <h1 className="text-2xl font-bold text-gray-800">Hero Slides</h1>
                <p className="text-sm text-gray-500 mt-1">Manage the homepage banner slideshow</p>
            </div>
            <button
                onClick={openAdd}
                className="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-blue-700 transition"
            >
                <FaPlus /> Add Slide
            </button>
        </div>

        {/* Slides Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            {loading ? (
                Array.from({ length: 3 }).map((_, i) => (
                    <div key={i} className="bg-white rounded-xl border border-gray-100 overflow-hidden animate-pulse">
                        <div className="h-40 bg-gray-200" />
                        <div className="p-4 space-y-2">
                            <div className="h-4 bg-gray-200 rounded w-2/3" />
                            <div className="h-3 bg-gray-100 rounded w-full" />
                        </div>
                    </div>
                ))
            ) : slides.length === 0 ? (
                <div className="col-span-3 bg-white rounded-xl border border-dashed border-gray-300 p-12 text-center">
                    <FaImage className="text-4xl text-gray-300 mx-auto mb-3" />
                    <p className="text-gray-400 font-medium">No slides yet</p>
                    <p className="text-sm text-gray-400 mt-1">Click "Add Slide" to create your first hero banner</p>
                </div>
            ) : slides.map((slide) => (
                <div key={slide.id} className="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden group">
                    {/* Media Preview */}
                    <div className="relative h-40 bg-gray-100">
                        {slide.media_type === 'video' ? (
                            <video
                                src={`${BACKEND_URL}${slide.media_url}`}
                                className="w-full h-full object-cover"
                                muted
                            />
                        ) : (
                            // eslint-disable-next-line @next/next/no-img-element
                            <img
                                src={`${BACKEND_URL}${slide.media_url}`}
                                alt={slide.title_en}
                                className="w-full h-full object-cover"
                            />
                        )}
                        {/* Overlay badges */}
                        <div className="absolute top-2 left-2 flex gap-2">
                            <span className={`text-xs font-medium px-2 py-0.5 rounded-full ${slide.is_active ? 'bg-green-500 text-white' : 'bg-gray-400 text-white'}`}>
                                {slide.is_active ? 'Active' : 'Hidden'}
                            </span>
                            <span className="text-xs font-medium px-2 py-0.5 rounded-full bg-gray-900/70 text-white flex items-center gap-1">
                                {slide.media_type === 'video' ? <FaVideo /> : <FaImage />}
                                {slide.media_type}
                            </span>
                        </div>
                        <span className="absolute top-2 right-2 text-xs bg-white/80 text-gray-600 px-2 py-0.5 rounded-full">
                            #{slide.sort_order}
                        </span>
                    </div>
                    {/* Card Body */}
                    <div className="p-4">
                        <h3 className="font-semibold text-gray-900 truncate">
                            {slide.title_en || <span className="text-gray-400 italic">No title</span>}
                        </h3>
                        <p className="text-sm text-gray-500 truncate mt-0.5">
                            {slide.subtitle_en || <span className="italic">No subtitle</span>}
                        </p>
                        {slide.cta_text && (
                            <div className="mt-2 text-xs text-blue-600 bg-blue-50 rounded px-2 py-1 inline-block">
                                CTA: {slide.cta_text} → {slide.cta_url}
                            </div>
                        )}
                        <div className="flex gap-2 mt-3">
                            <button
                                onClick={() => openEdit(slide)}
                                className="flex-1 text-sm flex items-center justify-center gap-2 py-1.5 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition"
                            >
                                <FaEdit /> Edit
                            </button>
                            <button
                                onClick={() => handleDelete(slide.id)}
                                className="flex-1 text-sm flex items-center justify-center gap-2 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition"
                            >
                                <FaTrash /> Delete
                            </button>
                        </div>
                    </div>
                </div>
            ))}
        </div>

        {/* Add / Edit Modal */}
        {showModal && (
            <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                <div className="bg-white rounded-xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
                    <div className="flex items-center justify-between p-6 border-b sticky top-0 bg-white z-10">
                        <h2 className="text-xl font-bold text-gray-800">
                            {editingSlide ? 'Edit Slide' : 'Add New Slide'}
                        </h2>
                        <button onClick={() => setShowModal(false)} className="text-gray-400 hover:text-gray-600">
                            <FaTimes size={20} />
                        </button>
                    </div>
                    <div className="p-6">
                        {error && (
                            <p className="mb-4 text-red-600 bg-red-50 border border-red-200 p-3 rounded-lg text-sm">{error}</p>
                        )}

                        {/* Tabs Navigation */}
                        <div className="flex border-b mb-6 overflow-x-auto">
                            {[
                                { id: 'media', label: '1. Media' },
                                { id: 'en', label: '2. English' },
                                { id: 'am', label: '3. አማርኛ' },
                                { id: 'or', label: '4. Oromo' }
                            ].map(tab => (
                                <button
                                    key={tab.id}
                                    type="button"
                                    onClick={() => setActiveTab(tab.id as any)}
                                    className={`px-4 py-2 text-sm font-bold whitespace-nowrap border-b-2 transition-colors ${activeTab === tab.id ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'}`}
                                >
                                    {tab.label}
                                </button>
                            ))}
                        </div>

                        <div className="space-y-6">
                            {/* Media Tab */}
                            {activeTab === 'media' && (
                                <div className="space-y-6">
                                    <div>
                                        <label className="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-tight">File Selection</label>
                                        <input
                                            type="file"
                                            accept="image/*,video/*"
                                            onChange={(e) => {
                                                const file = e.target.files?.[0] || null;
                                                setMediaFile(file);
                                                if (file) {
                                                    const isVid = file.type.startsWith('video');
                                                    setForm(prev => ({ ...prev, media_type: isVid ? 'video' : 'image' }));
                                                }
                                            }}
                                            className="w-full border-2 border-dashed border-gray-200 rounded-xl p-8 text-center cursor-pointer hover:border-blue-400 transition"
                                        />
                                        {mediaFile && (
                                            <div className="mt-4 p-3 bg-blue-50 rounded-lg flex items-center justify-between">
                                                <span className="text-sm font-medium text-blue-700">{mediaFile.name}</span>
                                                <span className="text-xs text-blue-500">{(mediaFile.size / 1024 / 1024).toFixed(1)} MB</span>
                                            </div>
                                        )}
                                    </div>
                                    <div>
                                        <label className="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-tight">Display Mode</label>
                                        <div className="flex gap-6">
                                            {['image', 'video'].map((t) => (
                                                <label key={t} className="flex items-center gap-3 cursor-pointer group">
                                                    <div className="relative">
                                                        <input
                                                            type="radio"
                                                            name="media_type"
                                                            value={t}
                                                            checked={form.media_type === t}
                                                            onChange={() => setForm({ ...form, media_type: t as 'image' | 'video' })}
                                                            className="sr-only"
                                                        />
                                                        <div className={`w-5 h-5 rounded-full border-2 transition-all ${form.media_type === t ? 'border-blue-600 bg-blue-600' : 'border-gray-300'}`} />
                                                        {form.media_type === t && <div className="absolute inset-0 flex items-center justify-center text-white text-[10px]">✓</div>}
                                                    </div>
                                                    <span className={`text-sm font-bold capitalize ${form.media_type === t ? 'text-blue-600' : 'text-gray-500'}`}>{t}</span>
                                                </label>
                                            ))}
                                        </div>
                                        <p className="text-[10px] text-gray-400 mt-2 italic font-medium">Select "Video" manually if you upload an mp4 file and it doesn't auto-detect.</p>
                                    </div>
                                    <div>
                                        <label className="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-tight">Navigation & Order</label>
                                        <div className="grid grid-cols-2 gap-4">
                                            <input
                                                type="text"
                                                placeholder="Target URL (e.g. /news)"
                                                value={form.cta_url}
                                                onChange={(e) => setForm({ ...form, cta_url: e.target.value })}
                                                className="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                                            />
                                            <input
                                                type="number"
                                                placeholder="Sort Order (0-99)"
                                                value={form.sort_order}
                                                onChange={(e) => setForm({ ...form, sort_order: Number(e.target.value) })}
                                                className="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                                            />
                                        </div>
                                    </div>
                                </div>
                            )}

                            {/* Other Tabs */}
                            {['en', 'am', 'or'].map(lang => activeTab === lang && (
                                <div key={lang} className="space-y-4 animate-fade-in">
                                    <div className="p-4 bg-gray-50 rounded-xl border border-gray-100">
                                        <label className="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Slide Title</label>
                                        <input
                                            type="text"
                                            value={(form as any)[`title_${lang}`]}
                                            onChange={(e) => setForm({ ...form, [`title_${lang}`]: e.target.value } as any)}
                                            className="w-full border-b-2 border-gray-200 bg-transparent py-2 text-xl font-bold focus:border-blue-600 focus:outline-none transition-colors"
                                            placeholder={`Enter title in ${lang === 'en' ? 'English' : lang === 'am' ? 'Amharic' : 'Oromo'}...`}
                                        />
                                    </div>
                                    <div className="p-4">
                                        <label className="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Description / Subtitle</label>
                                        <textarea
                                            rows={3}
                                            value={(form as any)[`subtitle_${lang}`]}
                                            onChange={(e) => setForm({ ...form, [`subtitle_${lang}`]: e.target.value } as any)}
                                            className="w-full border-2 border-gray-100 rounded-xl p-3 text-sm focus:border-blue-400 focus:outline-none"
                                            placeholder="Short impact text for this slide..."
                                        />
                                    </div>
                                    <div className="p-4 bg-blue-50/30 rounded-xl border border-blue-100/30">
                                        <label className="block text-xs font-black text-blue-400 uppercase tracking-widest mb-2">Button Label (CTA)</label>
                                        <input
                                            type="text"
                                            value={(form as any)[`cta_text_${lang}`] || (lang === 'en' ? form.cta_text : '')}
                                            onChange={(e) => {
                                                if (lang === 'en') setForm({ ...form, cta_text: e.target.value });
                                                else setForm({ ...form, [`cta_text_${lang}`]: e.target.value } as any);
                                            }}
                                            className="w-full border-b border-blue-200 bg-transparent py-1 font-bold text-blue-900 focus:border-blue-600 focus:outline-none transition-colors"
                                            placeholder="e.g. Read News"
                                        />
                                    </div>
                                </div>
                            ))}
                        </div>

                        <div className="flex justify-between items-center mt-10 pt-6 border-t">
                            <label className="flex items-center gap-3 cursor-pointer group">
                                <input
                                    type="checkbox"
                                    checked={form.is_active}
                                    onChange={(e) => setForm({ ...form, is_active: e.target.checked })}
                                    className="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                />
                                <span className="text-sm font-bold text-gray-700">Publish Immediately</span>
                            </label>
                            <div className="flex gap-3">
                                <button type="button" onClick={() => setShowModal(false)}
                                    className="px-6 py-2 text-sm font-bold text-gray-500 hover:text-gray-700 transition">
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    disabled={saving}
                                    className="px-8 py-2 bg-blue-600 text-white rounded-full font-black text-sm uppercase tracking-widest hover:bg-blue-700 transition disabled:opacity-50 shadow-xl shadow-blue-500/20"
                                >
                                    {saving ? 'Processing...' : editingSlide ? 'Update Slide' : 'Launch Slide'}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )}
    </AdminLayout>
);
}

