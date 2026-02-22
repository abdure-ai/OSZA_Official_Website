'use client';

import { useEffect, useState } from 'react';
import AdminLayout from '@/components/admin/AdminLayout';
import { fetchVacancies, createVacancy, updateVacancy, deleteVacancy, Vacancy } from '@/lib/api';
import { FaPlus, FaEdit, FaTrash, FaSpinner } from 'react-icons/fa';

export default function AdminVacanciesPage() {
    const [vacancies, setVacancies] = useState<Vacancy[]>([]);
    const [loading, setLoading] = useState(true);
    const [showModal, setShowModal] = useState(false);
    const [editingVacancy, setEditingVacancy] = useState<Vacancy | null>(null);
    const [token, setToken] = useState('');

    const [formData, setFormData] = useState({
        title_en: '',
        department: '',
        vacancy_type: 'Full-time' as const,
        location_en: 'Kemise',
        deadline: '',
        description_en: '',
        requirements_en: '',
        is_active: true
    });

    useEffect(() => {
        const t = localStorage.getItem('adminToken') || '';
        setToken(t);
        loadVacancies();
    }, []);

    async function loadVacancies() {
        setLoading(true);
        const data = await fetchVacancies({ active: 'all' });
        setVacancies(data);
        setLoading(false);
    }

    const handleOpenModal = (vacancy?: Vacancy) => {
        if (vacancy) {
            setEditingVacancy(vacancy);
            setFormData({
                title_en: vacancy.title_en,
                department: vacancy.department,
                vacancy_type: vacancy.vacancy_type as any,
                location_en: vacancy.location_en,
                deadline: vacancy.deadline.split('T')[0],
                description_en: vacancy.description_en,
                requirements_en: vacancy.requirements_en || '',
                is_active: !!vacancy.is_active
            });
        } else {
            setEditingVacancy(null);
            setFormData({
                title_en: '',
                department: '',
                vacancy_type: 'Full-time' as any,
                location_en: 'Kemise',
                deadline: '',
                description_en: '',
                requirements_en: '',
                is_active: true
            });
        }
        setShowModal(true);
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        try {
            if (editingVacancy) {
                await updateVacancy(editingVacancy.id, formData, token);
            } else {
                await createVacancy(formData, token);
            }
            setShowModal(false);
            loadVacancies();
        } catch (error: any) {
            alert(error.message);
        }
    };

    const handleDelete = async (id: number) => {
        if (confirm('Are you sure you want to delete this vacancy?')) {
            try {
                await deleteVacancy(id, token);
                loadVacancies();
            } catch (error: any) {
                alert(error.message);
            }
        }
    };

    return (
        <AdminLayout>
            <div className="flex justify-between items-center mb-6">
                <div>
                    <h1 className="text-2xl font-bold text-gray-800">Vacancies Management</h1>
                    <p className="text-gray-500 text-sm">Post and manage job openings.</p>
                </div>
                <button
                    onClick={() => handleOpenModal()}
                    className="bg-primary text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-opacity-90 transition"
                >
                    <FaPlus /> Add Vacancy
                </button>
            </div>

            <div className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                {loading ? (
                    <div className="p-12 text-center text-gray-400">
                        <FaSpinner className="animate-spin text-4xl mx-auto mb-4" />
                        <p>Loading vacancies...</p>
                    </div>
                ) : (
                    <table className="w-full text-left">
                        <thead className="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th className="px-6 py-4 text-sm font-semibold text-gray-600">Title</th>
                                <th className="px-6 py-4 text-sm font-semibold text-gray-600">Department</th>
                                <th className="px-6 py-4 text-sm font-semibold text-gray-600">Type</th>
                                <th className="px-6 py-4 text-sm font-semibold text-gray-600">Deadline</th>
                                <th className="px-6 py-4 text-sm font-semibold text-gray-600">Status</th>
                                <th className="px-6 py-4 text-sm font-semibold text-gray-600 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {vacancies.map((v) => (
                                <tr key={v.id} className="hover:bg-gray-50 transition-colors">
                                    <td className="px-6 py-4 font-medium text-gray-800">{v.title_en}</td>
                                    <td className="px-6 py-4 text-gray-600">{v.department}</td>
                                    <td className="px-6 py-4 text-gray-600">{v.vacancy_type}</td>
                                    <td className="px-6 py-4 text-gray-600">
                                        {new Date(v.deadline).toLocaleDateString()}
                                    </td>
                                    <td className="px-6 py-4">
                                        <span className={`px-2 py-1 rounded-full text-xs font-medium ${v.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`}>
                                            {v.is_active ? 'Active' : 'Inactive'}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4 text-right space-x-2">
                                        <button onClick={() => handleOpenModal(v)} className="text-blue-500 hover:text-blue-700">
                                            <FaEdit />
                                        </button>
                                        <button onClick={() => handleDelete(v.id)} className="text-red-500 hover:text-red-700">
                                            <FaTrash />
                                        </button>
                                    </td>
                                </tr>
                            ))}
                            {vacancies.length === 0 && (
                                <tr>
                                    <td colSpan={6} className="px-6 py-12 text-center text-gray-400">No vacancies found.</td>
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
                            <h2 className="text-xl font-bold">{editingVacancy ? 'Edit Vacancy' : 'New Vacancy'}</h2>
                            <button onClick={() => setShowModal(false)} className="text-gray-400 hover:text-gray-600">×</button>
                        </div>
                        <form onSubmit={handleSubmit} className="p-6 space-y-4">
                            <div className="grid grid-cols-2 gap-4">
                                <div className="col-span-2">
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Job Title (EN)</label>
                                    <input
                                        type="text"
                                        required
                                        className="w-full p-2 border rounded-md"
                                        value={formData.title_en}
                                        onChange={(e) => setFormData({ ...formData, title_en: e.target.value })}
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Department</label>
                                    <input
                                        type="text"
                                        required
                                        className="w-full p-2 border rounded-md"
                                        value={formData.department}
                                        onChange={(e) => setFormData({ ...formData, department: e.target.value })}
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Job Type</label>
                                    <select
                                        className="w-full p-2 border rounded-md"
                                        value={formData.vacancy_type}
                                        onChange={(e) => setFormData({ ...formData, vacancy_type: e.target.value as any })}
                                    >
                                        <option value="Full-time">Full-time</option>
                                        <option value="Part-time">Part-time</option>
                                        <option value="Contract">Contract</option>
                                        <option value="Internship">Internship</option>
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
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Location</label>
                                    <input
                                        type="text"
                                        className="w-full p-2 border rounded-md"
                                        value={formData.location_en}
                                        onChange={(e) => setFormData({ ...formData, location_en: e.target.value })}
                                    />
                                </div>
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Description (EN)</label>
                                <textarea
                                    className="w-full p-2 border rounded-md h-32"
                                    required
                                    value={formData.description_en}
                                    onChange={(e) => setFormData({ ...formData, description_en: e.target.value })}
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Requirements (EN)</label>
                                <textarea
                                    className="w-full p-2 border rounded-md h-32"
                                    value={formData.requirements_en}
                                    onChange={(e) => setFormData({ ...formData, requirements_en: e.target.value })}
                                />
                            </div>
                            <div className="flex items-center gap-2">
                                <input
                                    type="checkbox"
                                    id="is_active"
                                    checked={formData.is_active}
                                    onChange={(e) => setFormData({ ...formData, is_active: e.target.checked })}
                                />
                                <label htmlFor="is_active" className="text-sm font-medium text-gray-700">Active / Published</label>
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
                                    {editingVacancy ? 'Update' : 'Create'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}

