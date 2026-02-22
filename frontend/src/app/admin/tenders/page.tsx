'use client';

import { useEffect, useState } from 'react';
import AdminLayout from '@/components/admin/AdminLayout';
import { fetchTenders, createTender, updateTender, deleteTender, Tender, getFileUrl } from '@/lib/api';
import { FaPlus, FaEdit, FaTrash, FaSpinner, FaFilePdf } from 'react-icons/fa';

export default function AdminTendersPage() {
    const [tenders, setTenders] = useState<Tender[]>([]);
    const [loading, setLoading] = useState(true);
    const [showModal, setShowModal] = useState(false);
    const [editingTender, setEditingTender] = useState<Tender | null>(null);
    const [token, setToken] = useState('');
    const [file, setFile] = useState<File | null>(null);

    const [formData, setFormData] = useState({
        title_en: '',
        ref_number: '',
        deadline: '',
        status: 'Open' as const,
        description_en: '',
    });

    useEffect(() => {
        const t = localStorage.getItem('adminToken') || '';
        setToken(t);
        loadTenders();
    }, []);

    async function loadTenders() {
        setLoading(true);
        const data = await fetchTenders();
        setTenders(data);
        setLoading(false);
    }

    const handleOpenModal = (tender?: Tender) => {
        setFile(null);
        if (tender) {
            setEditingTender(tender);
            setFormData({
                title_en: tender.title_en,
                ref_number: tender.ref_number || '',
                deadline: tender.deadline.split('T')[0],
                status: tender.status as any,
                description_en: tender.description_en || '',
            });
        } else {
            setEditingTender(null);
            setFormData({
                title_en: '',
                ref_number: '',
                deadline: '',
                status: 'Open' as any,
                description_en: '',
            });
        }
        setShowModal(true);
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        const data = new FormData();
        Object.entries(formData).forEach(([key, value]) => {
            data.append(key, value);
        });
        if (file) data.append('document', file);

        try {
            if (editingTender) {
                await updateTender(editingTender.id, data, token);
            } else {
                await createTender(data, token);
            }
            setShowModal(false);
            loadTenders();
        } catch (error: any) {
            alert(error.message);
        }
    };

    const handleDelete = async (id: number) => {
        if (confirm('Are you sure you want to delete this tender?')) {
            try {
                await deleteTender(id, token);
                loadTenders();
            } catch (error: any) {
                alert(error.message);
            }
        }
    };

    return (
        <AdminLayout>
            <div className="flex justify-between items-center mb-6">
                <div>
                    <h1 className="text-2xl font-bold text-gray-800">Tenders Management</h1>
                    <p className="text-gray-500 text-sm">Post and manage procurement notices.</p>
                </div>
                <button
                    onClick={() => handleOpenModal()}
                    className="bg-primary text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-opacity-90 transition"
                >
                    <FaPlus /> Add Tender
                </button>
            </div>

            <div className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                {loading ? (
                    <div className="p-12 text-center text-gray-400">
                        <FaSpinner className="animate-spin text-4xl mx-auto mb-4" />
                        <p>Loading tenders...</p>
                    </div>
                ) : (
                    <table className="w-full text-left">
                        <thead className="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th className="px-6 py-4 text-sm font-semibold text-gray-600">Reference No.</th>
                                <th className="px-6 py-4 text-sm font-semibold text-gray-600">Title</th>
                                <th className="px-6 py-4 text-sm font-semibold text-gray-600">Deadline</th>
                                <th className="px-6 py-4 text-sm font-semibold text-gray-600">Status</th>
                                <th className="px-6 py-4 text-sm font-semibold text-gray-600 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {tenders.map((t) => (
                                <tr key={t.id} className="hover:bg-gray-50 transition-colors">
                                    <td className="px-6 py-4 font-mono text-sm text-gray-600">{t.ref_number || 'N/A'}</td>
                                    <td className="px-6 py-4">
                                        <div className="font-medium text-gray-800">{t.title_en}</div>
                                        {t.file_url && (
                                            <a
                                                href={getFileUrl(t.file_url)}
                                                target="_blank"
                                                className="text-xs text-blue-500 hover:underline flex items-center gap-1 mt-1"
                                            >
                                                <FaFilePdf /> View Document
                                            </a>
                                        )}
                                    </td>
                                    <td className="px-6 py-4 text-gray-600">
                                        {new Date(t.deadline).toLocaleDateString()}
                                    </td>
                                    <td className="px-6 py-4">
                                        <span className={`px-2 py-1 rounded-full text-xs font-medium ${t.status === 'Open' ? 'bg-green-100 text-green-700' :
                                            t.status === 'Closed' ? 'bg-red-100 text-red-700' :
                                                'bg-gray-100 text-gray-700'
                                            }`}>
                                            {t.status}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4 text-right space-x-2">
                                        <button onClick={() => handleOpenModal(t)} className="text-blue-500 hover:text-blue-700">
                                            <FaEdit />
                                        </button>
                                        <button onClick={() => handleDelete(t.id)} className="text-red-500 hover:text-red-700">
                                            <FaTrash />
                                        </button>
                                    </td>
                                </tr>
                            ))}
                            {tenders.length === 0 && (
                                <tr>
                                    <td colSpan={5} className="px-6 py-12 text-center text-gray-400">No tenders found.</td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                )}
            </div>

            {/* Modal */}
            {showModal && (
                <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                    <div className="bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                        <div className="p-6 border-b flex justify-between items-center">
                            <h2 className="text-xl font-bold">{editingTender ? 'Edit Tender' : 'New Tender'}</h2>
                            <button onClick={() => setShowModal(false)} className="text-gray-400 hover:text-gray-600">×</button>
                        </div>
                        <form onSubmit={handleSubmit} className="p-6 space-y-4">
                            <div className="grid grid-cols-2 gap-4">
                                <div className="col-span-2">
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Tender Title (EN)</label>
                                    <input
                                        type="text"
                                        required
                                        className="w-full p-2 border rounded-md"
                                        value={formData.title_en}
                                        onChange={(e) => setFormData({ ...formData, title_en: e.target.value })}
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Reference Number</label>
                                    <input
                                        type="text"
                                        className="w-full p-2 border rounded-md"
                                        value={formData.ref_number}
                                        onChange={(e) => setFormData({ ...formData, ref_number: e.target.value })}
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select
                                        className="w-full p-2 border rounded-md"
                                        value={formData.status}
                                        onChange={(e) => setFormData({ ...formData, status: e.target.value as any })}
                                    >
                                        <option value="Open">Open</option>
                                        <option value="Closed">Closed</option>
                                        <option value="Awarded">Awarded</option>
                                        <option value="Cancelled">Cancelled</option>
                                    </select>
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                                    <input
                                        type="date"
                                        required
                                        className="w-full p-2 border rounded-md"
                                        value={formData.deadline}
                                        onChange={(e) => setFormData({ ...formData, deadline: e.target.value })}
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Document (PDF/Word)</label>
                                    <input
                                        type="file"
                                        className="w-full p-1.5 border rounded-md text-sm"
                                        onChange={(e) => setFile(e.target.files?.[0] || null)}
                                    />
                                </div>
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Description (EN)</label>
                                <textarea
                                    className="w-full p-2 border rounded-md h-32"
                                    value={formData.description_en}
                                    onChange={(e) => setFormData({ ...formData, description_en: e.target.value })}
                                />
                            </div>
                            <div className="pt-4 border-t flex justify-end gap-3">
                                <button
                                    type="button"
                                    onClick={() => setShowModal(false)}
                                    className="px-4 py-2 text-gray-600 hover:text-gray-800"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    className="px-6 py-2 bg-primary text-white rounded-md hover:bg-opacity-90"
                                >
                                    {editingTender ? 'Update' : 'Create'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}
