'use client';

import AdminLayout from '@/components/admin/AdminLayout';
import { useState, useEffect, useCallback, useRef } from 'react';
import {
    fetchInvestments, createInvestment, updateInvestment, deleteInvestment,
    InvestmentOpportunity, getFileUrl
} from '@/lib/api';
import { FaPlus, FaTrash, FaEdit, FaSave, FaTimes, FaCoins, FaMapMarkerAlt, FaCalendarAlt, FaBriefcase } from 'react-icons/fa';

const CATEGORIES = ['Agriculture', 'Industry', 'Infrastructure', 'Tourism', 'Health', 'Education', 'Technology', 'Livestock'];
const STATUSES = ['Open', 'In Progress', 'Closed'];

export default function AdminInvestmentsPage() {
    const [investments, setInvestments] = useState<InvestmentOpportunity[]>([]);
    const [loading, setLoading] = useState(true);
    const [uploading, setUploading] = useState(false);
    const [error, setError] = useState('');
    const [success, setSuccess] = useState('');

    // Editing state
    const [editingId, setEditingId] = useState<number | null>(null);

    // Form state
    const [formData, setFormData] = useState({
        title_en: '',
        description_en: '',
        category: 'Agriculture',
        location: '',
        budget: '',
        incentives_en: '',
        contact_name: '',
        contact_phone: '',
        contact_email: '',
        status: 'Open' as 'Open' | 'In Progress' | 'Closed'
    });
    const [thumbnail, setThumbnail] = useState<File | null>(null);
    const fileInputRef = useRef<HTMLInputElement>(null);

    const loadInvestments = useCallback(async () => {
        setLoading(true);
        const data = await fetchInvestments();
        setInvestments(data);
        setLoading(false);
    }, []);

    useEffect(() => { loadInvestments(); }, [loadInvestments]);

    const handleEdit = (inv: InvestmentOpportunity) => {
        setEditingId(inv.id);
        setFormData({
            title_en: inv.title_en,
            description_en: inv.description_en || '',
            category: inv.category || 'Agriculture',
            location: inv.location || '',
            budget: inv.budget || '',
            incentives_en: inv.incentives_en || '',
            contact_name: inv.contact_name || '',
            contact_phone: inv.contact_phone || '',
            contact_email: inv.contact_email || '',
            status: inv.status
        });
        setThumbnail(null);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    const resetForm = () => {
        setEditingId(null);
        setFormData({
            title_en: '',
            description_en: '',
            category: 'Agriculture',
            location: '',
            budget: '',
            incentives_en: '',
            contact_name: '',
            contact_phone: '',
            contact_email: '',
            status: 'Open'
        });
        setThumbnail(null);
        if (fileInputRef.current) fileInputRef.current.value = '';
        setError('');
        setSuccess('');
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
                setSuccess('Investment opportunity updated successfully!');
            } else {
                await createInvestment(data, token);
                setSuccess('Investment opportunity created successfully!');
            }

            resetForm();
            loadInvestments();
        } catch (err: any) {
            setError(err.message || 'Operation failed');
        } finally {
            setUploading(false);
        }
    };

    const handleDelete = async (id: number) => {
        if (!confirm('Delete this investment opportunity?')) return;
        try {
            const token = localStorage.getItem('adminToken') || '';
            await deleteInvestment(id, token);
            setInvestments(prev => prev.filter(i => i.id !== id));
        } catch (err: any) {
            alert(err.message || 'Delete failed');
        }
    };

    return (
        <AdminLayout>
            <div className="mb-6">
                <h1 className="text-2xl font-bold text-gray-800">Investment Opportunities Management</h1>
                <p className="text-gray-500 text-sm mt-1">Manage investment projects and sector opportunities for the zone.</p>
            </div>

            {/* Form Section */}
            <div className="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                <h2 className="font-bold text-lg text-gray-800 mb-4 flex items-center gap-2">
                    {editingId ? <><FaEdit className="text-blue-500" /> Edit Opportunity</> : <><FaPlus className="text-green-500" /> New Opportunity</>}
                </h2>
                <form onSubmit={handleSubmit} className="space-y-4">
                    {error && <p className="text-red-600 bg-red-50 border border-red-200 p-3 rounded-lg text-sm">{error}</p>}
                    {success && <p className="text-green-700 bg-green-50 border border-green-200 p-3 rounded-lg text-sm">{success}</p>}

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div className="lg:col-span-2">
                            <label className="block text-sm font-medium text-gray-700 mb-1">Title (English)</label>
                            <input
                                type="text"
                                required
                                value={formData.title_en}
                                onChange={e => setFormData({ ...formData, title_en: e.target.value })}
                                className="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/20 outline-none"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select
                                value={formData.category}
                                onChange={e => setFormData({ ...formData, category: e.target.value })}
                                className="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/20 outline-none"
                            >
                                {CATEGORIES.map(cat => <option key={cat}>{cat}</option>)}
                            </select>
                        </div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div className="md:col-span-2">
                            <label className="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea
                                value={formData.description_en}
                                onChange={e => setFormData({ ...formData, description_en: e.target.value })}
                                className="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/20 outline-none h-24"
                            />
                        </div>
                        <div className="space-y-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Location</label>
                                <input
                                    type="text"
                                    value={formData.location}
                                    onChange={e => setFormData({ ...formData, location: e.target.value })}
                                    className="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/20 outline-none"
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Budget / Estimated Cost</label>
                                <input
                                    type="text"
                                    value={formData.budget}
                                    onChange={e => setFormData({ ...formData, budget: e.target.value })}
                                    className="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/20 outline-none"
                                />
                            </div>
                        </div>
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">Incentives (Tax breaks, Land, etc.)</label>
                        <textarea
                            value={formData.incentives_en}
                            onChange={e => setFormData({ ...formData, incentives_en: e.target.value })}
                            className="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/20 outline-none h-20"
                        />
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Contact Name</label>
                            <input
                                type="text"
                                value={formData.contact_name}
                                onChange={e => setFormData({ ...formData, contact_name: e.target.value })}
                                className="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/20 outline-none"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select
                                value={formData.status}
                                onChange={e => setFormData({ ...formData, status: e.target.value as any })}
                                className="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/20 outline-none"
                            >
                                {STATUSES.map(s => <option key={s}>{s}</option>)}
                            </select>
                        </div>
                        <div className="flex items-end gap-3">
                            <div className="flex-1">
                                <label className="block text-sm font-medium text-gray-700 mb-1">Thumbnail Image</label>
                                <input
                                    ref={fileInputRef}
                                    type="file"
                                    accept="image/*"
                                    onChange={e => setThumbnail(e.target.files?.[0] || null)}
                                    className="w-full border rounded-lg px-3 py-2 text-sm text-gray-600 file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:bg-primary/10 file:text-primary hover:file:bg-primary/20"
                                />
                            </div>
                            {thumbnail && (
                                <img src={URL.createObjectURL(thumbnail)} className="w-10 h-10 rounded object-cover border" alt="Preview" />
                            )}
                        </div>
                    </div>

                    <div className="flex justify-end gap-3 pt-2">
                        {editingId && (
                            <button
                                type="button"
                                onClick={resetForm}
                                className="px-6 py-2 border rounded-lg text-gray-600 hover:bg-gray-50 flex items-center gap-2"
                            >
                                <FaTimes /> Cancel
                            </button>
                        )}
                        <button
                            type="submit"
                            disabled={uploading}
                            className="bg-primary text-white px-8 py-2 rounded-lg font-bold hover:bg-primary/90 disabled:opacity-50 flex items-center gap-2"
                        >
                            <FaSave /> {uploading ? 'Processing...' : editingId ? 'Update Opportunity' : 'Save Opportunity'}
                        </button>
                    </div>
                </form>
            </div>

            {/* List Section */}
            <div className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table className="w-full text-left">
                    <thead className="bg-gray-50 border-b">
                        <tr>
                            <th className="px-6 py-4 font-semibold text-gray-600 text-sm">Title & Category</th>
                            <th className="px-6 py-4 font-semibold text-gray-600 text-sm">Location</th>
                            <th className="px-6 py-4 font-semibold text-gray-600 text-sm">Budget</th>
                            <th className="px-6 py-4 font-semibold text-gray-600 text-sm">Status</th>
                            <th className="px-6 py-4 font-semibold text-gray-600 text-sm text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-gray-100">
                        {loading ? (
                            <tr><td colSpan={5} className="px-6 py-8 text-center text-gray-400">Loading investments...</td></tr>
                        ) : investments.length === 0 ? (
                            <tr><td colSpan={5} className="px-6 py-8 text-center text-gray-400">No opportunities found.</td></tr>
                        ) : investments.map(inv => (
                            <tr key={inv.id} className="hover:bg-gray-50 transition">
                                <td className="px-6 py-4">
                                    <div className="flex items-center gap-3">
                                        {inv.thumbnail_url ? (
                                            <img src={getFileUrl(inv.thumbnail_url)} className="w-12 h-12 rounded object-cover shadow-sm" alt="" />
                                        ) : (
                                            <div className="w-12 h-12 rounded bg-gray-100 flex items-center justify-center text-gray-400">
                                                <FaCoins />
                                            </div>
                                        )}
                                        <div>
                                            <span className="font-medium text-gray-900 block line-clamp-1">{inv.title_en}</span>
                                            <span className="text-[10px] font-bold text-primary uppercase tracking-wider">{inv.category}</span>
                                        </div>
                                    </div>
                                </td>
                                <td className="px-6 py-4 text-sm text-gray-600">
                                    <span className="flex items-center gap-1"><FaMapMarkerAlt className="text-gray-400" size={12} /> {inv.location || '-'}</span>
                                </td>
                                <td className="px-6 py-4 text-sm font-bold text-green-600 uppercase">
                                    {inv.budget || '-'}
                                </td>
                                <td className="px-6 py-4">
                                    <span className={`px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider ${inv.status === 'Open' ? 'bg-green-100 text-green-700' :
                                            inv.status === 'In Progress' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700'
                                        }`}>
                                        {inv.status}
                                    </span>
                                </td>
                                <td className="px-6 py-4 text-right">
                                    <div className="flex justify-end gap-2">
                                        <button
                                            onClick={() => handleEdit(inv)}
                                            className="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                        >
                                            <FaEdit />
                                        </button>
                                        <button
                                            onClick={() => handleDelete(inv.id)}
                                            className="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                        >
                                            <FaTrash />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </AdminLayout>
    );
}
