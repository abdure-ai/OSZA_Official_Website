'use client';
import { useState, useEffect, useCallback } from 'react';
import AdminLayout from '@/components/admin/AdminLayout';
import { FaPlus, FaTrash, FaEdit, FaTimes, FaImage, FaVideo } from 'react-icons/fa';

interface HeroSlide {
    id: number;
    title_en: string;
    subtitle_en: string;
    media_url: string;
    media_type: 'image' | 'video';
    cta_text: string;
    cta_url: string;
    sort_order: number;
    is_active: boolean;
}

type FormData = {
    title_en: string;
    subtitle_en: string;
    cta_text: string;
    cta_url: string;
    sort_order: number;
    is_active: boolean;
};

const EMPTY_FORM: FormData = {
    title_en: '',
    subtitle_en: '',
    cta_text: '',
    cta_url: '',
    sort_order: 0,
    is_active: true,
};

const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:5000/api';
const BACKEND_URL = process.env.NEXT_PUBLIC_BACKEND_URL || 'http://localhost:5000';

export default function AdminHeroPage() {
    const [slides, setSlides] = useState<HeroSlide[]>([]);
    const [loading, setLoading] = useState(true);
    const [showModal, setShowModal] = useState(false);
    const [editingSlide, setEditingSlide] = useState<HeroSlide | null>(null);
    const [form, setForm] = useState<FormData>(EMPTY_FORM);
    const [mediaFile, setMediaFile] = useState<File | null>(null);
    const [saving, setSaving] = useState(false);
    const [error, setError] = useState('');

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
        setShowModal(true);
    };

    const openEdit = (slide: HeroSlide) => {
        setEditingSlide(slide);
        setForm({
            title_en: slide.title_en || '',
            subtitle_en: slide.subtitle_en || '',
            cta_text: slide.cta_text || '',
            cta_url: slide.cta_url || '',
            sort_order: slide.sort_order,
            is_active: slide.is_active,
        });
        setMediaFile(null);
        setError('');
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
                // Update — JSON only (no new file)
                const res = await fetch(`${API_URL}/hero/${editingSlide.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        Authorization: `Bearer ${token}`,
                    },
                    body: JSON.stringify({ ...form, is_active: form.is_active ? 1 : 0 }),
                });
                if (!res.ok) throw new Error((await res.json()).message || 'Failed to update');
            } else {
                // Create — multipart with media file
                const fd = new FormData();
                fd.append('media', mediaFile!);
                fd.append('title_en', form.title_en);
                fd.append('subtitle_en', form.subtitle_en);
                fd.append('cta_text', form.cta_text);
                fd.append('cta_url', form.cta_url);
                fd.append('sort_order', String(form.sort_order));
                fd.append('is_active', form.is_active ? '1' : '0');

                const res = await fetch(`${API_URL}/hero`, {
                    method: 'POST',
                    headers: { Authorization: `Bearer ${token}` },
                    body: fd,
                });
                if (!res.ok) throw new Error((await res.json()).message || 'Failed to create slide');
            }
            setShowModal(false);
            loadSlides();
        } catch (err: any) {
            setError(err.message || 'An error occurred.');
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
        } catch {
            alert('Failed to delete slide.');
        }
    };

    return (
        <AdminLayout>
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
                        <form onSubmit={handleSubmit} className="p-6 space-y-4">
                            {error && (
                                <p className="text-red-600 bg-red-50 border border-red-200 p-3 rounded-lg text-sm">{error}</p>
                            )}

                            {/* Media upload (only for new slide) */}
                            {!editingSlide && (
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">
                                        Media File <span className="text-red-500">*</span>
                                        <span className="text-gray-400 font-normal ml-1">(image or video, max 20MB)</span>
                                    </label>
                                    <input
                                        type="file"
                                        accept="image/*,video/*"
                                        onChange={(e) => setMediaFile(e.target.files?.[0] || null)}
                                        className="w-full border rounded-lg px-3 py-2 text-sm text-gray-600 file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                    />
                                    {mediaFile && (
                                        <p className="text-xs text-gray-500 mt-1">
                                            Selected: {mediaFile.name} ({(mediaFile.size / 1024 / 1024).toFixed(1)} MB)
                                        </p>
                                    )}
                                </div>
                            )}

                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Title (English)</label>
                                <input
                                    type="text"
                                    value={form.title_en}
                                    onChange={(e) => setForm({ ...form, title_en: e.target.value })}
                                    className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="e.g. Building a Prosperous Community"
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Subtitle (English)</label>
                                <input
                                    type="text"
                                    value={form.subtitle_en}
                                    onChange={(e) => setForm({ ...form, subtitle_en: e.target.value })}
                                    className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Short descriptive text below the title"
                                />
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">CTA Button Text</label>
                                    <input
                                        type="text"
                                        value={form.cta_text}
                                        onChange={(e) => setForm({ ...form, cta_text: e.target.value })}
                                        className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="e.g. Learn More"
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">CTA Button URL</label>
                                    <input
                                        type="text"
                                        value={form.cta_url}
                                        onChange={(e) => setForm({ ...form, cta_url: e.target.value })}
                                        className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="/about"
                                    />
                                </div>
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                                    <input
                                        type="number"
                                        min={0}
                                        value={form.sort_order}
                                        onChange={(e) => setForm({ ...form, sort_order: Number(e.target.value) })}
                                        className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    />
                                    <p className="text-xs text-gray-400 mt-0.5">Lower = displayed first</p>
                                </div>
                                <div className="flex flex-col justify-center">
                                    <label className="block text-sm font-medium text-gray-700 mb-2">Visibility</label>
                                    <label className="flex items-center gap-2 cursor-pointer">
                                        <input
                                            type="checkbox"
                                            checked={form.is_active}
                                            onChange={(e) => setForm({ ...form, is_active: e.target.checked })}
                                            className="w-4 h-4 rounded border-gray-300"
                                        />
                                        <span className="text-sm text-gray-700">Show on homepage</span>
                                    </label>
                                </div>
                            </div>

                            <div className="flex justify-end gap-3 pt-2">
                                <button type="button" onClick={() => setShowModal(false)}
                                    className="px-4 py-2 text-gray-600 border rounded-lg hover:bg-gray-50 transition">
                                    Cancel
                                </button>
                                <button type="submit" disabled={saving}
                                    className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:opacity-50">
                                    {saving ? 'Saving...' : editingSlide ? 'Save Changes' : 'Add Slide'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}
