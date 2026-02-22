'use client';
import { useState, useEffect, useCallback } from 'react';
import AdminLayout from '@/components/admin/AdminLayout';
import { FaPlus, FaEdit, FaTrash, FaTimes, FaImages } from 'react-icons/fa';
import { GalleryItem, WoredaItem } from '@/lib/api';

const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:5000/api';
const BACKEND_URL = process.env.NEXT_PUBLIC_BACKEND_URL || 'http://localhost:5000';

const PRESET_CATEGORIES = ['Events', 'Infrastructure', 'Agriculture', 'Health', 'Education', 'Culture', 'Other'];

interface FormState {
    title: string;
    category: string;
    woreda_id: string;
    sort_order: string;
    is_active: boolean;
}
const EMPTY: FormState = { title: '', category: '', woreda_id: '', sort_order: '0', is_active: true };

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
            title: item.title,
            category: item.category,
            woreda_id: item.woreda_id ? String(item.woreda_id) : '',
            sort_order: String(item.sort_order),
            is_active: item.is_active,
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
            fd.append('category', form.category);
            fd.append('sort_order', form.sort_order);
            fd.append('is_active', form.is_active ? '1' : '0');
            if (form.woreda_id) fd.append('woreda_id', form.woreda_id);
            if (imageFile) fd.append('image', imageFile);

            const url = editing ? `${API_URL}/gallery/${editing.id}` : `${API_URL}/gallery`;
            const method = editing ? 'PUT' : 'POST';
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

    return (
        <AdminLayout>
            <div className="flex justify-between items-center mb-6">
                <div>
                    <h1 className="text-2xl font-bold text-gray-800">Gallery</h1>
                    <p className="text-sm text-gray-500 mt-1">Manage photo gallery items and categories</p>
                </div>
                <button
                    onClick={openAdd}
                    className="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-blue-700 transition"
                >
                    <FaPlus /> Add Photo
                </button>
            </div>

            {/* Photo Grid */}
            {loading ? (
                <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    {Array.from({ length: 8 }).map((_, i) => (
                        <div key={i} className="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden animate-pulse">
                            <div className="h-40 bg-gray-200" />
                            <div className="p-3 space-y-2">
                                <div className="h-3 bg-gray-200 rounded w-3/4" />
                                <div className="h-3 bg-gray-100 rounded w-1/2" />
                            </div>
                        </div>
                    ))}
                </div>
            ) : items.length === 0 ? (
                <div className="bg-white rounded-xl border border-gray-100 shadow-sm p-16 text-center">
                    <FaImages className="text-4xl text-gray-300 mx-auto mb-3" />
                    <p className="text-gray-400 font-medium">No gallery items yet</p>
                    <button onClick={openAdd} className="mt-4 text-sm text-blue-600 hover:underline">Add the first photo</button>
                </div>
            ) : (
                <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    {items.map(item => (
                        <div key={item.id} className="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden group">
                            <div className="relative h-40 bg-gray-100 overflow-hidden">
                                {/* eslint-disable-next-line @next/next/no-img-element */}
                                <img
                                    src={`${BACKEND_URL}${item.image_url}`}
                                    alt={item.title}
                                    className="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                />
                                {!item.is_active && (
                                    <div className="absolute inset-0 bg-black/50 flex items-center justify-center">
                                        <span className="text-white text-xs font-medium bg-gray-800 px-2 py-1 rounded">Hidden</span>
                                    </div>
                                )}
                            </div>
                            <div className="p-3">
                                <p className="font-semibold text-gray-800 text-sm truncate">{item.title}</p>
                                <div className="flex items-center justify-between mt-1">
                                    <span className="text-xs text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">{item.category}</span>
                                    {item.woreda_name && (
                                        <span className="text-xs text-gray-400 truncate max-w-[80px]">{item.woreda_name}</span>
                                    )}
                                </div>
                                <div className="flex gap-2 mt-3">
                                    <button
                                        onClick={() => openEdit(item)}
                                        className="flex-1 text-xs px-2 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition flex items-center justify-center gap-1"
                                    >
                                        <FaEdit size={10} /> Edit
                                    </button>
                                    <button
                                        onClick={() => handleDelete(item.id, item.title)}
                                        className="flex-1 text-xs px-2 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition flex items-center justify-center gap-1"
                                    >
                                        <FaTrash size={10} /> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            )}

            {/* Modal */}
            {showModal && (
                <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                    <div className="bg-white rounded-xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
                        <div className="flex items-center justify-between p-6 border-b sticky top-0 bg-white z-10">
                            <h2 className="text-xl font-bold text-gray-800">
                                {editing ? 'Edit Gallery Item' : 'Add New Photo'}
                            </h2>
                            <button onClick={() => setShowModal(false)} className="text-gray-400 hover:text-gray-600">
                                <FaTimes size={20} />
                            </button>
                        </div>
                        <form onSubmit={handleSubmit} className="p-6 space-y-4">
                            {error && <p className="text-red-600 bg-red-50 border border-red-200 p-3 rounded-lg text-sm">{error}</p>}

                            {/* Image upload + preview */}
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    Photo {!editing && <span className="text-red-500">*</span>}
                                </label>
                                {previewUrl && (
                                    // eslint-disable-next-line @next/next/no-img-element
                                    <img src={previewUrl} alt="Preview" className="w-full h-48 object-cover rounded-lg mb-3 border" />
                                )}
                                <label className="flex flex-col items-center gap-2 border-2 border-dashed border-gray-300 rounded-lg p-4 cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition">
                                    <FaImages className="text-gray-400 text-2xl" />
                                    <span className="text-sm text-gray-500">{imageFile ? imageFile.name : 'Click to select an image'}</span>
                                    <input type="file" accept="image/*" className="sr-only" onChange={handleImageChange} />
                                </label>
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Title <span className="text-red-500">*</span></label>
                                <input
                                    value={form.title}
                                    onChange={e => set('title', e.target.value)}
                                    className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="e.g. Dawa Chefa Irrigation Project Opening"
                                    required
                                />
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Category <span className="text-red-500">*</span></label>
                                    <input
                                        list="category-options"
                                        value={form.category}
                                        onChange={e => set('category', e.target.value)}
                                        className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="e.g. Events"
                                        required
                                    />
                                    <datalist id="category-options">
                                        {PRESET_CATEGORIES.map(c => <option key={c} value={c} />)}
                                    </datalist>
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                                    <input
                                        type="number"
                                        value={form.sort_order}
                                        onChange={e => set('sort_order', e.target.value)}
                                        className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        min={0}
                                    />
                                </div>
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Woreda (optional — leave blank for zone-wide)</label>
                                <select
                                    value={form.woreda_id}
                                    onChange={e => set('woreda_id', e.target.value)}
                                    className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    <option value="">— Zone-wide (no woreda) —</option>
                                    {woredas.map(w => (
                                        <option key={w.id} value={String(w.id)}>{w.name_en}</option>
                                    ))}
                                </select>
                            </div>

                            <div className="flex items-center gap-2">
                                <input
                                    type="checkbox"
                                    id="gallery-active"
                                    checked={!!form.is_active}
                                    onChange={e => set('is_active', e.target.checked)}
                                    className="w-4 h-4 rounded border-gray-300"
                                />
                                <label htmlFor="gallery-active" className="text-sm text-gray-700 cursor-pointer">
                                    Active (visible on public website)
                                </label>
                            </div>

                            <div className="flex justify-end gap-3 pt-2">
                                <button type="button" onClick={() => setShowModal(false)}
                                    className="px-4 py-2 text-gray-600 border rounded-lg hover:bg-gray-50 transition">
                                    Cancel
                                </button>
                                <button type="submit" disabled={saving}
                                    className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:opacity-50">
                                    {saving ? 'Saving...' : editing ? 'Save Changes' : 'Add Photo'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}
