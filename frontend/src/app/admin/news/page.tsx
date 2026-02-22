'use client';
import { useState, useEffect, useCallback } from 'react';
import AdminLayout from '@/components/admin/AdminLayout';
import { FaEdit, FaTrash, FaPlus, FaTimes, FaImage } from 'react-icons/fa';
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
    category: string;
    status: string;
    content_en: string;
};

const EMPTY_FORM: FormData = {
    title_en: '',
    category: 'news',
    status: 'draft',
    content_en: '',
};

export default function AdminNewsPage() {
    const [news, setNews] = useState<NewsItem[]>([]);
    const [loading, setLoading] = useState(true);
    const [showModal, setShowModal] = useState(false);
    const [editingItem, setEditingItem] = useState<NewsItem | null>(null);
    const [form, setForm] = useState<FormData>(EMPTY_FORM);
    const [image, setImage] = useState<File | null>(null);
    const [saving, setSaving] = useState(false);
    const [error, setError] = useState('');

    const loadNews = useCallback(async () => {
        setLoading(true);
        const data = await fetchAllNewsAdmin();
        setNews(data);
        setLoading(false);
    }, []);

    useEffect(() => { loadNews(); }, [loadNews]);

    const openAdd = () => {
        setEditingItem(null);
        setForm(EMPTY_FORM);
        setImage(null);
        setError('');
        setShowModal(true);
    };

    const openEdit = (item: NewsItem) => {
        setEditingItem(item);
        setForm({
            title_en: item.title_en,
            category: item.category,
            status: item.status,
            content_en: item.content_en,
        });
        setImage(null);
        setError('');
        setShowModal(true);
    };

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

    const handleDelete = async (id: number) => {
        if (!confirm('Are you sure you want to delete this news post?')) return;
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
            <div className="flex justify-between items-center mb-6">
                <div>
                    <h1 className="text-2xl font-bold text-gray-800">News Management</h1>
                    <p className="text-gray-500 text-sm">Create and manage official news posts and announcements.</p>
                </div>
                <button
                    onClick={openAdd}
                    className="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-blue-700 transition"
                >
                    <FaPlus /> Add New Post
                </button>
            </div>

            <div className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table className="w-full text-left">
                    <thead className="bg-gray-50 border-b">
                        <tr>
                            <th className="px-6 py-4 font-semibold text-gray-600">Thumbnail</th>
                            <th className="px-6 py-4 font-semibold text-gray-600">Title</th>
                            <th className="px-6 py-4 font-semibold text-gray-600">Category</th>
                            <th className="px-6 py-4 font-semibold text-gray-600">Status</th>
                            <th className="px-6 py-4 font-semibold text-gray-600 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-gray-100">
                        {loading ? (
                            <tr><td colSpan={5} className="px-6 py-8 text-center text-gray-400">Loading...</td></tr>
                        ) : news.length === 0 ? (
                            <tr><td colSpan={5} className="px-6 py-8 text-center text-gray-400">No news posts found.</td></tr>
                        ) : news.map((item) => (
                            <tr key={item.id} className="hover:bg-gray-50 transition">
                                <td className="px-6 py-4">
                                    {item.thumbnail_url ? (
                                        <img
                                            src={getFileUrl(item.thumbnail_url)}
                                            alt=""
                                            className="w-12 h-12 object-cover rounded-md border"
                                        />
                                    ) : (
                                        <div className="w-12 h-12 bg-gray-100 rounded-md flex items-center justify-center text-gray-400">
                                            <FaImage />
                                        </div>
                                    )}
                                </td>
                                <td className="px-6 py-4 font-medium text-gray-900 max-w-xs truncate">{item.title_en}</td>
                                <td className="px-6 py-4 text-gray-600 capitalize">{item.category}</td>
                                <td className="px-6 py-4 text-gray-500">
                                    <span className={`px-2 py-1 rounded-full text-xs font-medium ${item.status === 'published'
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-yellow-100 text-yellow-700'
                                        }`}>
                                        {item.status.charAt(0).toUpperCase() + item.status.slice(1)}
                                    </span>
                                </td>
                                <td className="px-6 py-4 text-right">
                                    <button
                                        onClick={() => openEdit(item)}
                                        className="text-blue-500 hover:text-blue-700 mx-2"
                                        title="Edit"
                                    >
                                        <FaEdit />
                                    </button>
                                    <button
                                        onClick={() => handleDelete(item.id)}
                                        className="text-red-500 hover:text-red-700 mx-2"
                                        title="Delete"
                                    >
                                        <FaTrash />
                                    </button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>

            {/* Modal */}
            {showModal && (
                <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                    <div className="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                        <div className="flex items-center justify-between p-6 border-b sticky top-0 bg-white z-10">
                            <h2 className="text-xl font-bold text-gray-800">
                                {editingItem ? 'Edit Post' : 'Add New Post'}
                            </h2>
                            <button onClick={() => setShowModal(false)} className="text-gray-400 hover:text-gray-600">
                                <FaTimes size={20} />
                            </button>
                        </div>
                        <form onSubmit={handleSubmit} className="p-6 space-y-4">
                            {error && (
                                <p className="text-red-600 bg-red-50 border border-red-200 p-3 rounded-lg text-sm">{error}</p>
                            )}

                            <div className="grid grid-cols-2 gap-6">
                                <div className="space-y-4 col-span-2 md:col-span-1">
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1">Title (English)</label>
                                        <input
                                            type="text"
                                            required
                                            value={form.title_en}
                                            onChange={(e) => setForm({ ...form, title_en: e.target.value })}
                                            className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Enter title..."
                                        />
                                    </div>
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                        <select
                                            value={form.category}
                                            onChange={(e) => setForm({ ...form, category: e.target.value })}
                                            className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        >
                                            <option value="news">News</option>
                                            <option value="press_release">Press Release</option>
                                            <option value="update">Update</option>
                                            <option value="announcement">Announcement</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <select
                                            value={form.status}
                                            onChange={(e) => setForm({ ...form, status: e.target.value })}
                                            className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        >
                                            <option value="draft">Draft</option>
                                            <option value="published">Published</option>
                                            <option value="archived">Archived</option>
                                        </select>
                                    </div>
                                </div>

                                <div className="space-y-4 col-span-2 md:col-span-1">
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Thumbnail Image</label>
                                    <div className="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center">
                                        {(image || editingItem?.thumbnail_url) ? (
                                            <div className="relative inline-block">
                                                <img
                                                    src={image ? URL.createObjectURL(image) : getFileUrl(editingItem!.thumbnail_url!)}
                                                    className="max-h-32 rounded-lg mx-auto"
                                                    alt="Preview"
                                                />
                                                <button
                                                    type="button"
                                                    onClick={() => setImage(null)}
                                                    className="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-lg"
                                                >
                                                    <FaTimes size={12} />
                                                </button>
                                            </div>
                                        ) : (
                                            <div className="py-4">
                                                <FaImage className="mx-auto text-4xl text-gray-300 mb-2" />
                                                <p className="text-xs text-gray-500">JPEG, PNG, WEBP (Max 5MB)</p>
                                            </div>
                                        )}
                                        <input
                                            type="file"
                                            accept="image/*"
                                            onChange={(e) => setImage(e.target.files?.[0] || null)}
                                            className="mt-3 block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Content (English)</label>
                                <textarea
                                    required
                                    rows={8}
                                    value={form.content_en}
                                    onChange={(e) => setForm({ ...form, content_en: e.target.value })}
                                    className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                    placeholder="Write content here..."
                                />
                            </div>

                            <div className="flex justify-end gap-3 pt-6 border-t mt-6">
                                <button
                                    type="button"
                                    onClick={() => setShowModal(false)}
                                    className="px-4 py-2 text-gray-600 border rounded-lg hover:bg-gray-50 transition"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    disabled={saving}
                                    className="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:opacity-50 font-bold"
                                >
                                    {saving ? 'Saving...' : editingItem ? 'Update Post' : 'Publish Post'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}
