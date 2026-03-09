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

const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api';

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
        const baseUrl = process.env.NEXT_PUBLIC_BACKEND_URL || 'http://localhost:8000';
        setAdminPhotoPreview(w.administrator_photo_url ? `${baseUrl}${w.administrator_photo_url}` : '');
        setBannerFile(null);
        setBannerPreview(w.banner_url ? `${baseUrl}${w.banner_url}` : '');
        setLogoFile(null);
        setLogoPreview(w.logo_url ? `${baseUrl}${w.logo_url}` : '');
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
            const method = editing ? 'POST' : 'POST'; // Laravel usually expects POST for file uploads with spoofing
            if (editing) fd.append('_method', 'PUT');

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
            <div className="mb-10">
                <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div className="flex items-center gap-3 mb-2">
                            <span className="h-1 w-12 bg-indigo-600 rounded-full"></span>
                            <span className="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-600">Regional Administration</span>
                        </div>
                        <h1 className="text-4xl font-black text-slate-900 tracking-tight italic">
                            Governance <span className="text-indigo-600">Hub</span>
                        </h1>
                        <p className="text-slate-500 mt-2 text-sm font-medium">Manage jurisdictional profiles, administrative leadership, and public portals.</p>
                    </div>
                    <button
                        onClick={openAdd}
                        className="group bg-slate-900 text-white px-8 py-4 rounded-2xl flex items-center gap-3 hover:bg-slate-800 transition-all shadow-2xl shadow-slate-200 active:scale-95"
                    >
                        <div className="w-6 h-6 bg-white/10 rounded-lg flex items-center justify-center group-hover:rotate-90 transition-transform">
                            <FaPlus className="text-xs" />
                        </div>
                        <span className="font-black text-[10px] uppercase tracking-widest">Register New Woreda</span>
                    </button>
                </div>
            </div>

            <div className="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="bg-slate-900">
                                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Administrative Unit</th>
                                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Regional Capital</th>
                                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Demographics</th>
                                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Portal Health</th>
                                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Operations</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-50">
                            {loading ? (
                                Array.from({ length: 5 }).map((_, i) => (
                                    <tr key={i} className="animate-pulse">
                                        <td className="px-8 py-6">
                                            <div className="flex items-center gap-4">
                                                <div className="w-12 h-12 bg-slate-100 rounded-xl" />
                                                <div className="space-y-2">
                                                    <div className="h-4 bg-slate-100 rounded w-40" />
                                                    <div className="h-3 bg-slate-50 rounded w-24" />
                                                </div>
                                            </div>
                                        </td>
                                        <td className="px-8 py-6"><div className="h-4 bg-slate-100 rounded w-24" /></td>
                                        <td className="px-8 py-6"><div className="h-4 bg-slate-100 rounded w-16" /></td>
                                        <td className="px-8 py-6"><div className="h-6 bg-slate-100 rounded-full w-20" /></td>
                                        <td className="px-8 py-6" />
                                    </tr>
                                ))
                            ) : woredas.length === 0 ? (
                                <tr>
                                    <td colSpan={5} className="px-8 py-24 text-center">
                                        <div className="flex flex-col items-center gap-4 opacity-30">
                                            <FaMapMarkerAlt className="text-6xl text-slate-300" />
                                            <p className="text-slate-400 font-bold tracking-tight">Governance registry is empty.</p>
                                        </div>
                                    </td>
                                </tr>
                            ) : woredas.map(w => (
                                <tr key={w.id} className="hover:bg-indigo-50/30 transition-all group">
                                    <td className="px-8 py-6">
                                        <div className="flex items-center gap-4">
                                            <div className="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center text-slate-400 border border-slate-200 group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-sm">
                                                {w.logo_url ? (
                                                    <img src={`${process.env.NEXT_PUBLIC_BACKEND_URL || 'http://localhost:8000'}${w.logo_url}`} alt="" className="w-8 h-8 object-contain" />
                                                ) : <FaMapMarkerAlt size={20} />}
                                            </div>
                                            <div>
                                                <p className="font-bold text-slate-900 group-hover:text-indigo-600 transition-colors uppercase tracking-tight">{w.name_en}</p>
                                                <p className="text-[10px] text-slate-400 font-black uppercase tracking-[0.1em] mt-0.5 italic">/{w.slug}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-8 py-6 text-slate-500 font-bold text-xs italic">
                                        {w.capital_en || '—'}
                                    </td>
                                    <td className="px-8 py-6">
                                        <div className="flex flex-col gap-1">
                                            <span className="text-xs font-bold text-slate-900">{w.population || 'N/A'}</span>
                                            <span className="text-[9px] font-black text-slate-400 uppercase tracking-widest">{w.area_km2 || '0'} km²</span>
                                        </div>
                                    </td>
                                    <td className="px-8 py-6">
                                        <span className={`inline-flex items-center gap-2 px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-sm ${w.is_active
                                            ? 'bg-emerald-100 text-emerald-700 border border-emerald-200'
                                            : 'bg-slate-100 text-slate-500 border border-slate-200'
                                            }`}>
                                            {w.is_active ? <div className="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-ping" /> : <div className="w-1.5 h-1.5 bg-slate-400 rounded-full" />}
                                            {w.is_active ? 'Public' : 'Hidden'}
                                        </span>
                                    </td>
                                    <td className="px-8 py-6 text-right">
                                        <div className="flex justify-end gap-2">
                                            <a
                                                href={`/woreda/${w.slug}`}
                                                target="_blank"
                                                className="w-10 h-10 flex items-center justify-center bg-white text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all shadow-sm border border-slate-100"
                                                title="View Portal"
                                            >
                                                <FaEye size={14} />
                                            </a>
                                            <button
                                                onClick={() => openEdit(w)}
                                                className="w-10 h-10 flex items-center justify-center bg-white text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all shadow-sm border border-slate-100"
                                                title="Modify Profile"
                                            >
                                                <FaEdit size={14} />
                                            </button>
                                            <button
                                                onClick={() => handleDelete(w.id, w.name_en)}
                                                className="w-10 h-10 flex items-center justify-center bg-white text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all shadow-sm border border-slate-100"
                                                title="Decommission"
                                            >
                                                <FaTrash size={14} />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>

            {/* Modal */}
            {showModal && (
                <div className="fixed inset-0 bg-slate-900/60 backdrop-blur-md flex items-center justify-center z-50 p-4">
                    <div className="bg-white rounded-[2.5rem] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.3)] w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col border border-slate-100">
                        <div className="flex items-center justify-between p-8 border-b bg-slate-50/50">
                            <div>
                                <h2 className="text-3xl font-black text-slate-900 tracking-tight italic">
                                    Jurisdiction <span className="text-indigo-600">Composer</span>
                                </h2>
                                <p className="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] mt-1">Configure Woreda Sub-site & Metadata</p>
                            </div>
                            <button onClick={() => setShowModal(false)} className="w-10 h-10 bg-white border border-slate-200 rounded-full flex items-center justify-center text-slate-400 hover:text-rose-600 hover:border-rose-200 transition-all shadow-sm">
                                <FaTimes size={18} />
                            </button>
                        </div>

                        <form onSubmit={handleSubmit} className="flex-1 overflow-y-auto p-8 space-y-10 scrollbar-thin scrollbar-thumb-slate-200">
                            {error && (
                                <div className="bg-rose-50 border-2 border-rose-100 p-4 rounded-2xl text-rose-600 text-xs font-black uppercase tracking-widest flex items-center gap-3 animate-pulse">
                                    <div className="w-6 h-6 bg-rose-600 text-white rounded-full flex items-center justify-center text-[10px]">!</div>
                                    {error}
                                </div>
                            )}

                            {/* Section: Primary Identity */}
                            <section className="space-y-6">
                                <div className="flex items-center gap-4 mb-4">
                                    <div className="h-6 w-1.5 bg-indigo-600 rounded-full" />
                                    <h3 className="text-sm font-black text-slate-900 uppercase tracking-widest">Primary Identity</h3>
                                </div>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div className="space-y-2">
                                        <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Woreda Name (EN) <span className="text-rose-500">*</span></label>
                                        <input
                                            value={form.name_en}
                                            onChange={e => { set('name_en', e.target.value); if (!editing) set('slug', autoSlug(e.target.value)); }}
                                            className="w-full border-2 border-slate-100 bg-slate-50/50 rounded-2xl px-6 py-4 focus:outline-none focus:border-indigo-500 focus:bg-white transition-all text-sm font-bold text-slate-900"
                                            placeholder="e.g. Dawa Chefa"
                                            required
                                        />
                                    </div>
                                    <div className="space-y-2">
                                        <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Universal Slug <span className="text-rose-500">*</span></label>
                                        <input
                                            value={form.slug}
                                            onChange={e => set('slug', autoSlug(e.target.value))}
                                            className="w-full border-2 border-slate-100 bg-slate-50/50 rounded-2xl px-6 py-4 focus:outline-none focus:border-indigo-500 focus:bg-white transition-all text-sm font-mono text-slate-400"
                                            placeholder="dawa-chefa"
                                            required
                                        />
                                    </div>
                                </div>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div className="space-y-2">
                                        <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Name (Amharic)</label>
                                        <input value={form.name_am} onChange={e => set('name_am', e.target.value)} className="w-full border-2 border-slate-100 bg-slate-50/50 rounded-2xl px-6 py-4 focus:outline-none focus:border-indigo-500 focus:bg-white transition-all text-sm font-bold text-slate-900" placeholder="ዳዋ ጨፋ" />
                                    </div>
                                    <div className="space-y-2">
                                        <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Name (Oromo)</label>
                                        <input value={form.name_or} onChange={e => set('name_or', e.target.value)} className="w-full border-2 border-slate-100 bg-slate-50/50 rounded-2xl px-6 py-4 focus:outline-none focus:border-indigo-500 focus:bg-white transition-all text-sm font-bold text-slate-900" placeholder="Dawaa Caffee" />
                                    </div>
                                </div>
                            </section>

                            {/* Section: Visual Assets */}
                            <section className="space-y-6">
                                <div className="flex items-center gap-4 mb-4">
                                    <div className="h-6 w-1.5 bg-indigo-600 rounded-full" />
                                    <h3 className="text-sm font-black text-slate-900 uppercase tracking-widest">Visual Assets</h3>
                                </div>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div className="bg-slate-50/80 rounded-[2rem] p-6 border-2 border-dashed border-slate-100 hover:border-indigo-200 transition-all group">
                                        <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 block">Hero Banner Reveal</label>
                                        <div className="relative aspect-video rounded-2xl overflow-hidden bg-white border border-slate-100 shadow-inner group-hover:shadow-md transition-all">
                                            {bannerPreview ? (
                                                <img src={bannerPreview} alt="" className="w-full h-full object-cover" />
                                            ) : (
                                                <div className="absolute inset-0 flex flex-col items-center justify-center text-slate-300">
                                                    <FaPlus size={32} className="mb-2" />
                                                    <span className="text-[8px] font-black uppercase tracking-widest">Upload Banner</span>
                                                </div>
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
                                                className="absolute inset-0 opacity-0 cursor-pointer"
                                            />
                                        </div>
                                    </div>
                                    <div className="bg-slate-50/80 rounded-[2rem] p-6 border-2 border-dashed border-slate-100 hover:border-indigo-200 transition-all group">
                                        <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 block">Woreda Emblem (Logo)</label>
                                        <div className="relative w-32 h-32 mx-auto rounded-3xl overflow-hidden bg-white border border-slate-100 shadow-inner group-hover:shadow-md transition-all flex items-center justify-center">
                                            {logoPreview ? (
                                                <img src={logoPreview} alt="" className="w-24 h-24 object-contain" />
                                            ) : (
                                                <div className="flex flex-col items-center justify-center text-slate-300 text-center p-4">
                                                    <FaPlus size={24} className="mb-1" />
                                                    <span className="text-[8px] font-black uppercase tracking-widest leading-tight">Drop Logo</span>
                                                </div>
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
                                                className="absolute inset-0 opacity-0 cursor-pointer"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </section>

                            {/* Section: Regional Data */}
                            <section className="space-y-6">
                                <div className="flex items-center gap-4 mb-4">
                                    <div className="h-6 w-1.5 bg-indigo-600 rounded-full" />
                                    <h3 className="text-sm font-black text-slate-900 uppercase tracking-widest">Regional Metadata</h3>
                                </div>
                                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div className="space-y-2">
                                        <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Capital City</label>
                                        <input value={form.capital_en} onChange={e => set('capital_en', e.target.value)} className="w-full border-2 border-slate-100 bg-slate-50/50 rounded-2xl px-6 py-4 focus:outline-none focus:border-indigo-500" placeholder="e.g. Kemise" />
                                    </div>
                                    <div className="space-y-2">
                                        <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Population</label>
                                        <input value={form.population} onChange={e => set('population', e.target.value)} className="w-full border-2 border-slate-100 bg-slate-50/50 rounded-2xl px-6 py-4 focus:outline-none focus:border-indigo-500" placeholder="e.g. 150,000" />
                                    </div>
                                    <div className="space-y-2">
                                        <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Area (km²)</label>
                                        <input value={form.area_km2} onChange={e => set('area_km2', e.target.value)} className="w-full border-2 border-slate-100 bg-slate-50/50 rounded-2xl px-6 py-4 focus:outline-none focus:border-indigo-500" placeholder="e.g. 1,200" />
                                    </div>
                                </div>
                            </section>

                            {/* Section: Administration */}
                            <section className="space-y-6">
                                <div className="flex items-center gap-4 mb-4">
                                    <div className="h-6 w-1.5 bg-indigo-600 rounded-full" />
                                    <h3 className="text-sm font-black text-slate-900 uppercase tracking-widest">Leadership Profile</h3>
                                </div>
                                <div className="bg-indigo-50/50 rounded-[2.5rem] p-8 space-y-8 border-2 border-indigo-100/30">
                                    <div className="flex flex-col md:flex-row gap-8 items-center md:items-start">
                                        <div className="relative w-32 h-44 rounded-3xl overflow-hidden bg-white shadow-xl shadow-indigo-200/50 group border-4 border-white">
                                            {adminPhotoPreview ? (
                                                <img src={adminPhotoPreview} alt="" className="w-full h-full object-cover" />
                                            ) : (
                                                <div className="absolute inset-0 flex flex-col items-center justify-center text-indigo-200">
                                                    <FaUserTie size={40} />
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
                                                className="absolute inset-0 opacity-0 cursor-pointer"
                                            />
                                        </div>
                                        <div className="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
                                            <div className="space-y-2">
                                                <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Administrator Name</label>
                                                <input value={form.administrator_name} onChange={e => set('administrator_name', e.target.value)} className="w-full border-2 border-white bg-white/80 rounded-2xl px-6 py-4 focus:outline-none focus:border-indigo-500 text-sm font-bold shadow-sm" />
                                            </div>
                                            <div className="space-y-2">
                                                <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Official Title</label>
                                                <input value={form.administrator_title} onChange={e => set('administrator_title', e.target.value)} className="w-full border-2 border-white bg-white/80 rounded-2xl px-6 py-4 focus:outline-none focus:border-indigo-500 text-sm font-bold shadow-sm" />
                                            </div>
                                            <div className="space-y-2">
                                                <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Protocol Phone</label>
                                                <input value={form.contact_phone} onChange={e => set('contact_phone', e.target.value)} className="w-full border-2 border-white bg-white/80 rounded-2xl px-6 py-4 focus:outline-none focus:border-indigo-500 text-sm font-bold shadow-sm" />
                                            </div>
                                            <div className="space-y-2">
                                                <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Domain Email</label>
                                                <input value={form.contact_email} onChange={e => set('contact_email', e.target.value)} className="w-full border-2 border-white bg-white/80 rounded-2xl px-6 py-4 focus:outline-none focus:border-indigo-500 text-sm font-bold shadow-sm" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <div className="flex items-center gap-4 bg-slate-900 p-8 rounded-[2rem] border border-slate-800 shadow-2xl shadow-indigo-100">
                                <div className="relative inline-flex items-center cursor-pointer">
                                    <input
                                        type="checkbox"
                                        className="sr-only peer"
                                        checked={form.is_active}
                                        onChange={(e) => set('is_active', e.target.checked)}
                                    />
                                    <div className="w-14 h-8 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-indigo-500 shadow-inner"></div>
                                </div>
                                <div>
                                    <p className="text-xs font-black text-white uppercase tracking-widest italic">Live Governance Portal</p>
                                    <p className="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Activate public-facing sub-site and API routes.</p>
                                </div>
                            </div>

                            <div className="flex justify-end gap-3 pt-6">
                                <button
                                    type="button"
                                    onClick={() => setShowModal(false)}
                                    className="px-10 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 border-2 border-slate-100 rounded-[1.5rem] hover:bg-slate-50 transition-all font-bold"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    disabled={saving}
                                    className="px-12 py-5 bg-indigo-600 text-white rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 transition-all disabled:opacity-50 shadow-2xl shadow-indigo-200 italic"
                                >
                                    {saving ? 'Synchronizing Intelligence...' : editing ? 'Commit Changes' : 'Initialize Jurisdisction'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}
