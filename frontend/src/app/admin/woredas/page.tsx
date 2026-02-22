'use client';
import { useState, useEffect, useCallback } from 'react';
import AdminLayout from '@/components/admin/AdminLayout';
import { FaPlus, FaEdit, FaTrash, FaTimes, FaMapMarkerAlt, FaEye, FaEyeSlash, FaUserTie } from 'react-icons/fa';

interface WoredaItem {
    id: number;
    name_en: string;
    name_am?: string;
    name_or?: string;
    slug: string;
    capital_en?: string;
    capital_am?: string;
    capital_or?: string;
    population?: string;
    area_km2?: string;
    administrator_name?: string;
    administrator_title?: string;
    administrator_photo_url?: string;
    contact_phone?: string;
    contact_email?: string;
    address_en?: string;
    address_am?: string;
    address_or?: string;
    description_en?: string;
    description_am?: string;
    description_or?: string;
    established_year?: string;
    banner_url?: string;
    logo_url?: string;
    is_active: boolean;
}

type FormState = Omit<WoredaItem, 'id'>;
const EMPTY: FormState = {
    name_en: '', name_am: '', name_or: '',
    slug: '',
    capital_en: '', capital_am: '', capital_or: '',
    population: '', area_km2: '',
    administrator_name: '', administrator_title: 'Woreda Administrator',
    contact_phone: '', contact_email: '',
    address_en: '', address_am: '', address_or: '',
    description_en: '', description_am: '', description_or: '',
    established_year: '', is_active: true,
};

const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:5000/api';

export default function AdminWoredas() {
    const [woredas, setWoredas] = useState<WoredaItem[]>([]);
    const [loading, setLoading] = useState(true);
    const [showModal, setShowModal] = useState(false);
    const [editing, setEditing] = useState<WoredaItem | null>(null);
    const [form, setForm] = useState<FormState>(EMPTY);
    const [adminPhotoFile, setAdminPhotoFile] = useState<File | null>(null);
    const [adminPhotoPreview, setAdminPhotoPreview] = useState('');
    const [bannerFile, setBannerFile] = useState<File | null>(null);
    const [bannerPreview, setBannerPreview] = useState('');
    const [logoFile, setLogoFile] = useState<File | null>(null);
    const [logoPreview, setLogoPreview] = useState('');
    const [saving, setSaving] = useState(false);
    const [error, setError] = useState('');

    const token = typeof window !== 'undefined' ? localStorage.getItem('adminToken') || '' : '';

    const load = useCallback(async () => {
        setLoading(true);
        try {
            const res = await fetch(`${API_URL}/woredas/all`, {
                headers: { Authorization: `Bearer ${token}` },
                cache: 'no-store',
            });
            if (res.ok) setWoredas(await res.json());
        } finally {
            setLoading(false);
        }
    }, [token]);

    useEffect(() => { load(); }, [load]);

    const openAdd = () => {
        setEditing(null);
        setForm(EMPTY);
        setAdminPhotoFile(null);
        setAdminPhotoPreview('');
        setBannerFile(null);
        setBannerPreview('');
        setLogoFile(null);
        setLogoPreview('');
        setError('');
        setShowModal(true);
    };
    const openEdit = (w: WoredaItem) => {
        setEditing(w);
        setForm({ ...w });
        setAdminPhotoFile(null);
        setAdminPhotoPreview(w.administrator_photo_url ? `${process.env.NEXT_PUBLIC_BACKEND_URL || 'http://localhost:5000'}${w.administrator_photo_url}` : '');
        setBannerFile(null);
        setBannerPreview(w.banner_url ? `${process.env.NEXT_PUBLIC_BACKEND_URL || 'http://localhost:5000'}${w.banner_url}` : '');
        setLogoFile(null);
        setLogoPreview(w.logo_url ? `${process.env.NEXT_PUBLIC_BACKEND_URL || 'http://localhost:5000'}${w.logo_url}` : '');
        setError('');
        setShowModal(true);
    };

    const set = (k: keyof FormState, v: string | boolean) =>
        setForm(prev => ({ ...prev, [k]: v }));

    const autoSlug = (name: string) =>
        name.toLowerCase().trim().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!form.name_en || !form.slug) { setError('Name and slug are required.'); return; }
        setSaving(true); setError('');
        try {
            const fd = new FormData();
            (Object.entries(form) as [string, any][]).forEach(([k, v]) => {
                if (v !== undefined && v !== null) fd.append(k, String(v));
            });
            if (adminPhotoFile) fd.append('admin_photo', adminPhotoFile);
            if (bannerFile) fd.append('banner', bannerFile);
            if (logoFile) fd.append('logo', logoFile);

            const url = editing ? `${API_URL}/woredas/${editing.id}` : `${API_URL}/woredas`;
            const method = editing ? 'PUT' : 'POST';
            const res = await fetch(url, {
                method,
                headers: { Authorization: `Bearer ${token}` },
                body: fd,
            });
            if (!res.ok) throw new Error((await res.json()).message || 'Failed');
            setShowModal(false);
            load();
        } catch (err: any) {
            setError(err.message);
        } finally {
            setSaving(false);
        }
    };

    const handleDelete = async (id: number, name: string) => {
        if (!confirm(`Delete "${name}"? This cannot be undone.`)) return;
        await fetch(`${API_URL}/woredas/${id}`, {
            method: 'DELETE',
            headers: { Authorization: `Bearer ${token}` },
        });
        setWoredas(prev => prev.filter(w => w.id !== id));
    };

    return (
        <AdminLayout>
            <div className="flex justify-between items-center mb-6">
                <div>
                    <h1 className="text-2xl font-bold text-gray-800">Woredas</h1>
                    <p className="text-sm text-gray-500 mt-1">Manage woreda sub-sites and their information</p>
                </div>
                <button
                    onClick={openAdd}
                    className="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-blue-700 transition"
                >
                    <FaPlus /> Add Woreda
                </button>
            </div>

            {/* Table */}
            <div className="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <table className="w-full text-sm">
                    <thead className="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th className="text-left px-6 py-3 font-semibold text-gray-500 uppercase text-xs tracking-wide">Woreda</th>
                            <th className="text-left px-6 py-3 font-semibold text-gray-500 uppercase text-xs tracking-wide">Capital</th>
                            <th className="text-left px-6 py-3 font-semibold text-gray-500 uppercase text-xs tracking-wide">Population</th>
                            <th className="text-left px-6 py-3 font-semibold text-gray-500 uppercase text-xs tracking-wide">Administrator</th>
                            <th className="text-left px-6 py-3 font-semibold text-gray-500 uppercase text-xs tracking-wide">Status</th>
                            <th className="text-right px-6 py-3 font-semibold text-gray-500 uppercase text-xs tracking-wide">Actions</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-gray-50">
                        {loading
                            ? Array.from({ length: 4 }).map((_, i) => (
                                <tr key={i} className="animate-pulse">
                                    <td className="px-6 py-4"><div className="h-4 bg-gray-200 rounded w-32" /></td>
                                    <td className="px-6 py-4"><div className="h-4 bg-gray-100 rounded w-24" /></td>
                                    <td className="px-6 py-4"><div className="h-4 bg-gray-100 rounded w-16" /></td>
                                    <td className="px-6 py-4"><div className="h-4 bg-gray-100 rounded w-28" /></td>
                                    <td className="px-6 py-4"><div className="h-4 bg-gray-100 rounded w-12" /></td>
                                    <td className="px-6 py-4" />
                                </tr>
                            ))
                            : woredas.map(w => (
                                <tr key={w.id} className="hover:bg-gray-50 transition">
                                    <td className="px-6 py-4">
                                        <div className="flex items-center gap-2">
                                            <FaMapMarkerAlt className="text-blue-400 flex-shrink-0" />
                                            <div>
                                                <p className="font-semibold text-gray-900">{w.name_en}</p>
                                                <p className="text-xs text-gray-400 font-mono">/woreda/{w.slug}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-6 py-4 text-gray-700">{w.capital_en || '—'}</td>
                                    <td className="px-6 py-4 text-gray-700">{w.population || '—'}</td>
                                    <td className="px-6 py-4 text-gray-700">{w.administrator_name || '—'}</td>
                                    <td className="px-6 py-4">
                                        <span className={`inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full ${w.is_active
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-gray-100 text-gray-500'}`}>
                                            {w.is_active ? <FaEye size={10} /> : <FaEyeSlash size={10} />}
                                            {w.is_active ? 'Active' : 'Hidden'}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className="flex justify-end gap-2">
                                            <a
                                                href={`/woreda/${w.slug}`}
                                                target="_blank"
                                                className="text-xs px-3 py-1.5 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition"
                                            >
                                                Preview
                                            </a>
                                            <button
                                                onClick={() => openEdit(w)}
                                                className="text-xs px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition flex items-center gap-1"
                                            >
                                                <FaEdit size={11} /> Edit
                                            </button>
                                            <button
                                                onClick={() => handleDelete(w.id, w.name_en)}
                                                className="text-xs px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition flex items-center gap-1"
                                            >
                                                <FaTrash size={11} /> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ))
                        }
                    </tbody>
                </table>
                {!loading && woredas.length === 0 && (
                    <div className="p-12 text-center">
                        <FaMapMarkerAlt className="text-4xl text-gray-300 mx-auto mb-3" />
                        <p className="text-gray-400 font-medium">No woredas yet</p>
                    </div>
                )}
            </div>

            {/* Modal */}
            {showModal && (
                <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                    <div className="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                        <div className="flex items-center justify-between p-6 border-b sticky top-0 bg-white z-10">
                            <h2 className="text-xl font-bold text-gray-800">
                                {editing ? `Edit: ${editing.name_en}` : 'Add New Woreda'}
                            </h2>
                            <button onClick={() => setShowModal(false)} className="text-gray-400 hover:text-gray-600">
                                <FaTimes size={20} />
                            </button>
                        </div>
                        <form onSubmit={handleSubmit} className="p-6 space-y-4">
                            {error && <p className="text-red-600 bg-red-50 border border-red-200 p-3 rounded-lg text-sm">{error}</p>}

                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Woreda Name (EN) <span className="text-red-500">*</span></label>
                                    <input
                                        value={form.name_en}
                                        onChange={e => { set('name_en', e.target.value); if (!editing) set('slug', autoSlug(e.target.value)); }}
                                        className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="e.g. Dawa Chefa"
                                        required
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Slug (URL) <span className="text-red-500">*</span></label>
                                    <input
                                        value={form.slug}
                                        onChange={e => set('slug', autoSlug(e.target.value))}
                                        className="w-full border rounded-lg px-3 py-2 font-mono text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="dawa-chefa"
                                        required
                                    />
                                </div>
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Name (Amharic)</label>
                                    <input value={form.name_am} onChange={e => set('name_am', e.target.value)} className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="ዳዋ ጨፋ" />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Name (Afaan Oromo)</label>
                                    <input value={form.name_or} onChange={e => set('name_or', e.target.value)} className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Dawaa Caffee" />
                                </div>
                            </div>

                            <div className="space-y-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Description (EN)</label>
                                    <textarea value={form.description_en} onChange={e => set('description_en', e.target.value)} rows={2} className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                </div>
                                <div className="grid grid-cols-2 gap-4">
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1">Description (AM)</label>
                                        <textarea value={form.description_am} onChange={e => set('description_am', e.target.value)} rows={2} className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    </div>
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1">Description (OR)</label>
                                        <textarea value={form.description_or} onChange={e => set('description_or', e.target.value)} rows={2} className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    </div>
                                </div>
                            </div>

                            {/* Images Section */}
                            <div className="grid grid-cols-2 gap-6 p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div>
                                    <label className="block text-sm font-bold text-gray-700 mb-2">Hero Banner (Required for Unique Look)</label>
                                    {bannerPreview && (
                                        // eslint-disable-next-line @next/next/no-img-element
                                        <img src={bannerPreview} alt="Banner" className="w-full h-24 object-cover rounded-lg mb-2 border border-gray-200" />
                                    )}
                                    <input
                                        type="file"
                                        accept="image/*"
                                        onChange={e => {
                                            const f = e.target.files?.[0];
                                            if (!f) return;
                                            setBannerFile(f);
                                            setBannerPreview(URL.createObjectURL(f));
                                        }}
                                        className="text-xs w-full"
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-bold text-gray-700 mb-2">Woreda Logo</label>
                                    {logoPreview && (
                                        // eslint-disable-next-line @next/next/no-img-element
                                        <img src={logoPreview} alt="Logo" className="w-16 h-16 object-cover rounded-lg mb-2 border border-gray-200" />
                                    )}
                                    <input
                                        type="file"
                                        accept="image/*"
                                        onChange={e => {
                                            const f = e.target.files?.[0];
                                            if (!f) return;
                                            setLogoFile(f);
                                            setLogoPreview(URL.createObjectURL(f));
                                        }}
                                        className="text-xs w-full"
                                    />
                                </div>
                            </div>

                            <div className="grid grid-cols-3 gap-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Capital (EN)</label>
                                    <input value={form.capital_en} onChange={e => set('capital_en', e.target.value)} className="w-full border rounded-lg px-3 py-2" />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Capital (AM)</label>
                                    <input value={form.capital_am} onChange={e => set('capital_am', e.target.value)} className="w-full border rounded-lg px-3 py-2" />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Capital (OR)</label>
                                    <input value={form.capital_or} onChange={e => set('capital_or', e.target.value)} className="w-full border rounded-lg px-3 py-2" />
                                </div>
                            </div>

                            <div className="grid grid-cols-3 gap-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Population</label>
                                    <input value={form.population} onChange={e => set('population', e.target.value)} className="w-full border rounded-lg px-3 py-2" />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Area (km²)</label>
                                    <input value={form.area_km2} onChange={e => set('area_km2', e.target.value)} className="w-full border rounded-lg px-3 py-2" />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Established</label>
                                    <input value={form.established_year} onChange={e => set('established_year', e.target.value)} className="w-full border rounded-lg px-3 py-2" />
                                </div>
                            </div>

                            {/* Administrator Photo & Details */}
                            <div className="flex gap-6 p-4 bg-blue-50/50 rounded-xl border border-blue-100">
                                <div className="space-y-4 flex-1">
                                    <div className="grid grid-cols-2 gap-4">
                                        <div>
                                            <label className="block text-sm font-medium text-gray-700 mb-1">Admin Name</label>
                                            <input value={form.administrator_name} onChange={e => set('administrator_name', e.target.value)} className="w-full border rounded-lg px-3 py-2" />
                                        </div>
                                        <div>
                                            <label className="block text-sm font-medium text-gray-700 mb-1">Admin Title</label>
                                            <input value={form.administrator_title} onChange={e => set('administrator_title', e.target.value)} className="w-full border rounded-lg px-3 py-2" />
                                        </div>
                                    </div>
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-2">Admin Photo</label>
                                        <div className="flex items-center gap-4">
                                            {adminPhotoPreview ? (
                                                // eslint-disable-next-line @next/next/no-img-element
                                                <img src={adminPhotoPreview} alt="Admin" className="w-20 h-28 rounded-xl object-cover border-2 border-white shadow-sm" />
                                            ) : (
                                                <div className="w-20 h-28 rounded-xl bg-blue-100 flex items-center justify-center border-2 border-dashed border-blue-200">
                                                    <FaUserTie className="text-blue-300 text-xl" />
                                                </div>
                                            )}
                                            <input
                                                type="file"
                                                accept="image/*"
                                                onChange={e => {
                                                    const f = e.target.files?.[0];
                                                    if (!f) return;
                                                    setAdminPhotoFile(f);
                                                    setAdminPhotoPreview(URL.createObjectURL(f));
                                                }}
                                                className="text-xs"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Contact Phone</label>
                                    <input value={form.contact_phone} onChange={e => set('contact_phone', e.target.value)} className="w-full border rounded-lg px-3 py-2" />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Contact Email</label>
                                    <input type="email" value={form.contact_email} onChange={e => set('contact_email', e.target.value)} className="w-full border rounded-lg px-3 py-2" />
                                </div>
                            </div>

                            <div className="space-y-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Address (EN)</label>
                                    <input value={form.address_en} onChange={e => set('address_en', e.target.value)} className="w-full border rounded-lg px-3 py-2" />
                                </div>
                                <div className="grid grid-cols-2 gap-4">
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1">Address (AM)</label>
                                        <input value={form.address_am} onChange={e => set('address_am', e.target.value)} className="w-full border rounded-lg px-3 py-2" />
                                    </div>
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1">Address (OR)</label>
                                        <input value={form.address_or} onChange={e => set('address_or', e.target.value)} className="w-full border rounded-lg px-3 py-2" />
                                    </div>
                                </div>
                            </div>

                            <div className="flex items-center gap-2 pt-1 font-bold text-gray-700">
                                <input type="checkbox" id="woreda-active" checked={!!form.is_active} onChange={e => set('is_active', e.target.checked)} className="w-4 h-4 rounded border-gray-300" />
                                <label htmlFor="woreda-active" className="cursor-pointer">Active (Visible)</label>
                            </div>

                            <div className="flex justify-end gap-3 pt-4 border-t sticky bottom-0 bg-white">
                                <button type="button" onClick={() => setShowModal(false)} className="px-6 py-2 text-gray-600 border rounded-lg hover:bg-gray-50 transition">Cancel</button>
                                <button type="submit" disabled={saving} className="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:opacity-50">
                                    {saving ? 'Saving...' : editing ? 'Save Changes' : 'Add Woreda'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}
