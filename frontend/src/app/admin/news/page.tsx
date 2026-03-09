'use client';
import { useState, useEffect, useCallback } from 'react';
import AdminLayout from '@/components/admin/AdminLayout';
import { FaEdit, FaTrash, FaPlus, FaTimes, FaImage, FaEye, FaEyeSlash, FaNewspaper } from 'react-icons/fa';
import {
    fetchAllNewsAdmin,
    createNews,
    updateNews,
    deleteNews,
    NewsItem,
    getFileUrl,
} from '@/lib/api';

type FormData = {
    title_en: string;
    title_am: string;
    title_or: string;
    category: string;
    status: string;
    content_en: string;
    content_am: string;
    content_or: string;
};

const EMPTY_FORM: FormData = {
    title_en: '',
    title_am: '',
    title_or: '',
    category: 'news',
    status: 'draft',
    content_en: '',
    content_am: '',
    content_or: '',
};

export default function AdminNewsPage() {
    const [news, setNews] = useState<NewsItem[]>([]);
    const [loading, setLoading] = useState(true);
    const [showModal, setShowModal] = useState(false);
    const [editingItem, setEditingItem] = useState<NewsItem | null>(null);
    const [form, setForm] = useState<FormData>(EMPTY_FORM);
    const [image, setImage] = useState<File | null>(null);
    const [imagePreview, setImagePreview] = useState('');
    const [saving, setSaving] = useState(false);
    const [error, setError] = useState('');

    const loadNews = useCallback(async () => {
        setLoading(true);
        try {
            const data = await fetchAllNewsAdmin();
            setNews(data);
        } finally {
            setLoading(false);
        }
    }, []);

    useEffect(() => { loadNews(); }, [loadNews]);

    const openAdd = () => {
        setEditingItem(null);
        setForm(EMPTY_FORM);
        setImage(null);
        setImagePreview('');
        setError('');
        setShowModal(true);
    };

    const openEdit = (item: NewsItem) => {
        setEditingItem(item);
        setForm({
            title_en: item.title_en,
            title_am: item.title_am || '',
            title_or: item.title_or || '',
            category: item.category,
            status: item.status,
            content_en: item.content_en,
            content_am: item.content_am || '',
            content_or: item.content_or || '',
        });
        setImage(null);
        setImagePreview(item.thumbnail_url ? getFileUrl(item.thumbnail_url) : '');
        setError('');
        setShowModal(true);
    };

    const set = (k: keyof FormData, v: string) =>
        setForm(prev => ({ ...prev, [k]: v }));

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setSaving(true);
        setError('');
        const token = localStorage.getItem('adminToken') || '';

        const fd = new FormData();
        Object.entries(form).forEach(([k, v]) => fd.append(k, v));
        if (image) fd.append('thumbnail', image);
        if (!editingItem) fd.append('published_at', new Date().toISOString());

        try {
            if (editingItem) {
                await updateNews(editingItem.id, fd, token);
            } else {
                await createNews(fd, token);
            }
            setShowModal(false);
            loadNews();
        } catch (err: any) {
            setError(err.message || 'An error occurred.');
        } finally {
            setSaving(false);
        }
    };

    const handleDelete = async (id: number, title: string) => {
        if (!confirm(`Delete "${title}"? This cannot be undone.`)) return;
        const token = localStorage.getItem('adminToken') || '';
        try {
            await deleteNews(id, token);
            setNews((prev) => prev.filter((n) => n.id !== id));
        } catch (err: any) {
            alert(err.message || 'Failed to delete.');
        }
    };

    return (
        <AdminLayout>
            <div className="mb-10">
                <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div className="flex items-center gap-3 mb-2">
                            <span className="h-1 w-12 bg-blue-600 rounded-full"></span>
                            <span className="text-[10px] font-black uppercase tracking-[0.3em] text-blue-600">Press Center</span>
                        </div>
                        <h1 className="text-4xl font-black text-slate-900 tracking-tight italic">
                            Editorial <span className="text-blue-600">Archive</span>
                        </h1>
                        <p className="text-slate-500 mt-2 text-sm font-medium">Draft, publish, and manage official news stories and media releases.</p>
                    </div>
                    <button
                        onClick={openAdd}
                        className="group bg-slate-900 text-white px-8 py-4 rounded-2xl flex items-center gap-3 hover:bg-slate-800 transition-all shadow-2xl shadow-slate-200 active:scale-95"
                    >
                        <div className="w-6 h-6 bg-white/10 rounded-lg flex items-center justify-center group-hover:rotate-90 transition-transform">
                            <FaPlus className="text-xs" />
                        </div>
                        <span className="font-black text-[10px] uppercase tracking-widest">Compose New Story</span>
                    </button>
                </div>
            </div>

            <div className="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="bg-slate-900">
                                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Story Manifest</th>
                                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Classification</th>
                                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Visibility</th>
                                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Timestamp</th>
                                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Control</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-50">
                            {loading ? (
                                Array.from({ length: 6 }).map((_, i) => (
                                    <tr key={i} className="animate-pulse">
                                        <td className="px-8 py-6">
                                            <div className="flex items-center gap-4">
                                                <div className="w-16 h-12 bg-slate-100 rounded-xl" />
                                                <div className="space-y-2">
                                                    <div className="h-4 bg-slate-100 rounded w-48" />
                                                    <div className="h-3 bg-slate-50 rounded w-24" />
                                                </div>
                                            </div>
                                        </td>
                                        <td className="px-8 py-6"><div className="h-4 bg-slate-100 rounded w-20" /></td>
                                        <td className="px-8 py-6"><div className="h-6 bg-slate-100 rounded-full w-16" /></td>
                                        <td className="px-8 py-6"><div className="h-4 bg-slate-100 rounded w-24" /></td>
                                        <td className="px-8 py-6" />
                                    </tr>
                                ))
                            ) : news.length === 0 ? (
                                <tr>
                                    <td colSpan={5} className="px-8 py-24 text-center">
                                        <div className="flex flex-col items-center gap-4">
                                            <div className="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center">
                                                <FaNewspaper className="text-4xl text-slate-200" />
                                            </div>
                                            <p className="text-slate-400 font-bold tracking-tight">No editorial records found in the archive.</p>
                                        </div>
                                    </td>
                                </tr>
                            ) : news.map((item) => (
                                <tr key={item.id} className="hover:bg-blue-50/30 transition-all group">
                                    <td className="px-8 py-6">
                                        <div className="flex items-center gap-4">
                                            <div className="relative">
                                                {item.thumbnail_url ? (
                                                    <img
                                                        src={getFileUrl(item.thumbnail_url)}
                                                        alt=""
                                                        className="w-16 h-12 object-cover rounded-xl border-2 border-white shadow-md group-hover:scale-110 transition-transform"
                                                    />
                                                ) : (
                                                    <div className="w-16 h-12 bg-slate-100 rounded-xl flex items-center justify-center text-slate-300 border border-slate-200 italic text-[8px] font-black uppercase">
                                                        No Img
                                                    </div>
                                                )}
                                                <div className="absolute -bottom-1 -right-1 w-4 h-4 bg-white rounded-full flex items-center justify-center shadow-sm border border-slate-100 text-[8px] font-mono font-black text-slate-300">
                                                    {item.id}
                                                </div>
                                            </div>
                                            <div className="max-w-xs xl:max-w-md">
                                                <p className="font-bold text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-1">{item.title_en}</p>
                                                <p className="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1 italic">
                                                    {item.content_en.substring(0, 40)}...
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-8 py-6">
                                        <span className="bg-slate-100 text-slate-500 px-3 py-1 rounded-lg text-[9px] uppercase font-black tracking-widest border border-slate-200 shadow-sm">
                                            {item.category.replace('_', ' ')}
                                        </span>
                                    </td>
                                    <td className="px-8 py-6">
                                        <span className={`inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-sm ${item.status === 'published'
                                            ? 'bg-emerald-100 text-emerald-700 border border-emerald-200'
                                            : 'bg-amber-100 text-amber-700 border border-amber-200'
                                            }`}>
                                            {item.status === 'published' ? <FaEye size={8} /> : <FaEyeSlash size={8} />}
                                            {item.status}
                                        </span>
                                    </td>
                                    <td className="px-8 py-6 text-slate-500 font-bold text-xs italic">
                                        {new Date(item.published_at || item.created_at).toLocaleDateString(undefined, {
                                            month: 'short',
                                            day: 'numeric',
                                            year: 'numeric'
                                        })}
                                    </td>
                                    <td className="px-8 py-6">
                                        <div className="flex justify-end gap-2">
                                            <button
                                                onClick={() => openEdit(item)}
                                                className="w-10 h-10 flex items-center justify-center bg-white text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all shadow-sm border border-slate-100"
                                                title="Refine Story"
                                            >
                                                <FaEdit size={14} />
                                            </button>
                                            <button
                                                onClick={() => handleDelete(item.id, item.title_en)}
                                                className="w-10 h-10 flex items-center justify-center bg-white text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all shadow-sm border border-slate-100"
                                                title="Remove Story"
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
                <div className="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
                    <div className="bg-white rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.3)] w-full max-w-3xl max-h-[90vh] overflow-hidden flex flex-col">
                        <div className="flex items-center justify-between p-6 border-b bg-gray-50/50">
                            <div>
                                <h2 className="text-xl font-black text-gray-900 tracking-tight">
                                    {editingItem ? `Editing: ${editingItem.title_en}` : 'Compose New Post'}
                                </h2>
                                <p className="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">Editorial Dashboard</p>
                            </div>
                            <button onClick={() => setShowModal(false)} className="bg-white p-2 rounded-full border shadow-sm text-gray-400 hover:text-red-500 hover:border-red-500 transition-all">
                                <FaTimes size={18} />
                            </button>
                        </div>

                        <form onSubmit={handleSubmit} className="flex-grow overflow-y-auto p-8 space-y-8 scrollbar-thin scrollbar-thumb-gray-200">
                            {error && (
                                <div className="text-red-600 bg-red-50 border-2 border-red-100 p-4 rounded-xl text-sm font-bold flex items-center gap-3">
                                    <FaTimes className="bg-red-600 text-white p-1 rounded-full" />
                                    {error}
                                </div>
                            )}

                            {/* Section 1: Titles & Meta */}
                            <div className="space-y-6">
                                <div className="flex items-center gap-4 mb-2">
                                    <div className="h-4 w-1 bg-blue-600 rounded-full" />
                                    <h3 className="text-sm font-black uppercase tracking-widest text-gray-400">Basic Information</h3>
                                </div>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div className="col-span-2">
                                        <label className="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Title (English) <span className="text-red-500">*</span></label>
                                        <input
                                            type="text"
                                            required
                                            value={form.title_en}
                                            onChange={(e) => set('title_en', e.target.value)}
                                            className="w-full border-2 border-gray-100 bg-gray-50/30 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:bg-white transition-all font-bold text-gray-900"
                                            placeholder="Enter compelling headline..."
                                        />
                                    </div>
                                    <div>
                                        <label className="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Category</label>
                                        <select
                                            value={form.category}
                                            onChange={(e) => set('category', e.target.value)}
                                            className="w-full border-2 border-gray-100 bg-gray-50/30 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:bg-white transition-all font-bold text-gray-900 appearance-none"
                                        >
                                            <option value="news">Local News</option>
                                            <option value="press_release">Press Release</option>
                                            <option value="update">Project Update</option>
                                            <option value="announcement">Official Announcement</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label className="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Publication Status</label>
                                        <div className="flex gap-2">
                                            {['draft', 'published', 'archived'].map((s) => (
                                                <button
                                                    key={s}
                                                    type="button"
                                                    onClick={() => set('status', s)}
                                                    className={`flex-1 py-3 px-4 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all ${form.status === s
                                                        ? 'bg-blue-600 text-white shadow-lg shadow-blue-200'
                                                        : 'bg-gray-100 text-gray-500 hover:bg-gray-200'
                                                        }`}
                                                >
                                                    {s}
                                                </button>
                                            ))}
                                        </div>
                                    </div>
                                </div>

                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label className="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Title (Amharic)</label>
                                        <input
                                            type="text"
                                            value={form.title_am}
                                            onChange={(e) => set('title_am', e.target.value)}
                                            className="w-full border-2 border-gray-100 bg-gray-50/30 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:bg-white transition-all font-medium"
                                            placeholder="አርዕስት በዐማርኛ..."
                                        />
                                    </div>
                                    <div>
                                        <label className="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Title (Afaan Oromo)</label>
                                        <input
                                            type="text"
                                            value={form.title_or}
                                            onChange={(e) => set('title_or', e.target.value)}
                                            className="w-full border-2 border-gray-100 bg-gray-50/30 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:bg-white transition-all font-medium"
                                            placeholder="Mataduree..."
                                        />
                                    </div>
                                </div>
                            </div>

                            {/* Section 2: Media */}
                            <div className="space-y-6">
                                <div className="flex items-center gap-4 mb-2">
                                    <div className="h-4 w-1 bg-[#f5a623] rounded-full" />
                                    <h3 className="text-sm font-black uppercase tracking-widest text-gray-400">Featured Media</h3>
                                </div>
                                <div className="border-2 border-dashed border-gray-200 rounded-3xl p-8 bg-gray-50/50 group hover:border-[#f5a623] transition-all">
                                    <div className="flex flex-col md:flex-row items-center gap-8">
                                        <div className="w-full md:w-1/3">
                                            {imagePreview ? (
                                                <div className="relative aspect-video rounded-2xl overflow-hidden shadow-2xl border-4 border-white">
                                                    <img src={imagePreview} className="w-full h-full object-cover" alt="Preview" />
                                                    <button
                                                        type="button"
                                                        onClick={() => { setImage(null); setImagePreview(''); }}
                                                        className="absolute top-2 right-2 bg-red-600 text-white rounded-full p-2 shadow-lg hover:scale-110 transition-transform"
                                                    >
                                                        <FaTimes size={14} />
                                                    </button>
                                                </div>
                                            ) : (
                                                <div className="aspect-video bg-white rounded-2xl flex flex-col items-center justify-center border-2 border-dashed border-gray-200 text-gray-300">
                                                    <FaImage size={40} className="mb-3 opacity-20" />
                                                    <p className="text-[10px] uppercase font-black tracking-widest">No Image selected</p>
                                                </div>
                                            )}
                                        </div>
                                        <div className="flex-1">
                                            <h4 className="text-lg font-black text-gray-800 mb-2">Upload Cover</h4>
                                            <p className="text-sm text-gray-500 mb-4 font-medium italic">High-resolution cinematic images work best for premium government storytelling.</p>
                                            <label className="inline-block bg-white border-2 border-gray-100 px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest cursor-pointer hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                                                Select Image
                                                <input
                                                    type="file"
                                                    accept="image/*"
                                                    className="hidden"
                                                    onChange={(e) => {
                                                        const f = e.target.files?.[0];
                                                        if (f) {
                                                            setImage(f);
                                                            setImagePreview(URL.createObjectURL(f));
                                                        }
                                                    }}
                                                />
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Section 3: Content */}
                            <div className="space-y-6">
                                <div className="flex items-center gap-4 mb-2">
                                    <div className="h-4 w-1 bg-green-500 rounded-full" />
                                    <h3 className="text-sm font-black uppercase tracking-widest text-gray-400">Narrative Content</h3>
                                </div>
                                <div className="space-y-6">
                                    <div>
                                        <label className="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Main Content (English) <span className="text-red-500">*</span></label>
                                        <textarea
                                            required
                                            rows={8}
                                            value={form.content_en}
                                            onChange={(e) => set('content_en', e.target.value)}
                                            className="w-full border-2 border-gray-100 bg-gray-50/30 rounded-2xl px-6 py-4 focus:outline-none focus:border-blue-500 focus:bg-white transition-all font-medium leading-relaxed"
                                            placeholder="Write the full story here..."
                                        />
                                    </div>
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label className="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Content (Amharic)</label>
                                            <textarea
                                                rows={6}
                                                value={form.content_am}
                                                onChange={(e) => set('content_am', e.target.value)}
                                                className="w-full border-2 border-gray-100 bg-gray-50/30 rounded-2xl px-6 py-4 focus:outline-none focus:border-blue-500 focus:bg-white transition-all font-medium leading-relaxed"
                                                placeholder="የዜና ይዘት..."
                                            />
                                        </div>
                                        <div>
                                            <label className="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Content (Afaan Oromo)</label>
                                            <textarea
                                                rows={6}
                                                value={form.content_or}
                                                onChange={(e) => set('content_or', e.target.value)}
                                                className="w-full border-2 border-gray-100 bg-gray-50/30 rounded-2xl px-6 py-4 focus:outline-none focus:border-blue-500 focus:bg-white transition-all font-medium leading-relaxed"
                                                placeholder="Qabiyyee..."
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div className="flex justify-end gap-3 pt-8 border-t sticky bottom-0 bg-white/80 backdrop-blur-md pb-4">
                                <button
                                    type="button"
                                    onClick={() => setShowModal(false)}
                                    className="px-8 py-3 text-[10px] font-black uppercase tracking-widest text-gray-500 border-2 border-gray-100 rounded-xl hover:bg-gray-50 transition-all"
                                >
                                    Discard
                                </button>
                                <button
                                    type="submit"
                                    disabled={saving}
                                    className="px-10 py-3 bg-blue-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all disabled:opacity-50 shadow-xl shadow-blue-200"
                                >
                                    {saving ? 'Processing...' : editingItem ? 'Save Updates' : 'Publish Story'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}
