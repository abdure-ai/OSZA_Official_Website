'use client';

import { useEffect, useState } from 'react';
import AdminLayout from '@/components/admin/AdminLayout';
import { fetchVacancies, createVacancy, updateVacancy, deleteVacancy, Vacancy } from '@/lib/api';
import { FaPlus, FaEdit, FaTrash, FaSpinner, FaSearch, FaCalendarAlt, FaMapMarkerAlt, FaBriefcase, FaBuilding, FaInfoCircle, FaListUl, FaCheckCircle, FaTimesCircle } from 'react-icons/fa';
import { AnimatePresence, motion } from 'framer-motion';

const TYPE_COLORS = {
    'Full-time': 'bg-blue-100 text-blue-700 border-blue-200',
    'Part-time': 'bg-indigo-100 text-indigo-700 border-indigo-200',
    'Contract': 'bg-amber-100 text-amber-700 border-amber-200',
    'Internship': 'bg-purple-100 text-purple-700 border-purple-200',
};

export default function AdminVacanciesPage() {
    const [vacancies, setVacancies] = useState<Vacancy[]>([]);
    const [loading, setLoading] = useState(true);
    const [showModal, setShowModal] = useState(false);
    const [editingVacancy, setEditingVacancy] = useState<Vacancy | null>(null);
    const [token, setToken] = useState('');
    const [searchQuery, setSearchQuery] = useState('');

    const [formData, setFormData] = useState({
        title_en: '',
        title_am: '',
        title_or: '',
        department: '',
        vacancy_type: 'Full-time' as const,
        location_en: 'Kemise',
        location_am: '',
        location_or: '',
        deadline: '',
        description_en: '',
        description_am: '',
        description_or: '',
        requirements_en: '',
        requirements_am: '',
        requirements_or: '',
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
                title_en: vacancy.title_en || '',
                title_am: vacancy.title_am || '',
                title_or: vacancy.title_or || '',
                department: vacancy.department || '',
                vacancy_type: (vacancy.vacancy_type as any) || 'Full-time',
                location_en: vacancy.location_en || 'Kemise',
                location_am: vacancy.location_am || '',
                location_or: vacancy.location_or || '',
                deadline: vacancy.deadline ? vacancy.deadline.split('T')[0] : '',
                description_en: vacancy.description_en || '',
                description_am: vacancy.description_am || '',
                description_or: vacancy.description_or || '',
                requirements_en: vacancy.requirements_en || '',
                requirements_am: vacancy.requirements_am || '',
                requirements_or: vacancy.requirements_or || '',
                is_active: !!vacancy.is_active
            });
        } else {
            setEditingVacancy(null);
            setFormData({
                title_en: '',
                title_am: '',
                title_or: '',
                department: '',
                vacancy_type: 'Full-time' as any,
                location_en: 'Kemise',
                location_am: '',
                location_or: '',
                deadline: '',
                description_en: '',
                description_am: '',
                description_or: '',
                requirements_en: '',
                requirements_am: '',
                requirements_or: '',
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

    const filteredVacancies = vacancies.filter(v =>
        v.title_en.toLowerCase().includes(searchQuery.toLowerCase()) ||
        v.department?.toLowerCase().includes(searchQuery.toLowerCase())
    );

    return (
        <AdminLayout>
            <div className="mb-10">
                <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div className="flex items-center gap-3 mb-2">
                            <span className="h-1 w-12 bg-emerald-600 rounded-full"></span>
                            <span className="text-[10px] font-black uppercase tracking-[0.3em] text-emerald-600">Human Resources</span>
                        </div>
                        <h1 className="text-4xl font-black text-slate-900 tracking-tight italic">
                            Career <span className="text-emerald-600">Registry</span>
                        </h1>
                        <p className="text-slate-500 mt-2 text-sm font-medium">Manage institutional talent acquisition and job openings.</p>
                    </div>
                    <button
                        onClick={() => handleOpenModal()}
                        className="group bg-slate-900 text-white px-8 py-4 rounded-2xl flex items-center gap-3 hover:bg-slate-800 transition-all shadow-2xl shadow-slate-200 active:scale-95"
                    >
                        <div className="w-6 h-6 bg-white/10 rounded-lg flex items-center justify-center group-hover:rotate-90 transition-transform">
                            <FaPlus className="text-xs" />
                        </div>
                        <span className="font-black text-[10px] uppercase tracking-widest">Post New Opening</span>
                    </button>
                </div>
            </div>

            <div className="space-y-8">
                {/* Search Bar Refined */}
                <div className="bg-white/60 backdrop-blur-md p-2 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col md:flex-row gap-2 items-center">
                    <div className="relative flex-1 w-full group">
                        <FaSearch className="absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-600 transition-colors" />
                        <input
                            type="text"
                            placeholder="Search by job title, department or keyword..."
                            className="w-full pl-14 pr-6 py-4 bg-transparent border-none rounded-2xl focus:ring-0 text-sm font-bold text-slate-700 placeholder:text-slate-300"
                            value={searchQuery}
                            onChange={(e) => setSearchQuery(e.target.value)}
                        />
                    </div>
                </div>

                {/* Registry Table */}
                <div className="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="w-full text-left border-collapse">
                            <thead>
                                <tr className="bg-slate-900">
                                    <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Position Details</th>
                                    <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Assignment</th>
                                    <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Type</th>
                                    <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Deadline</th>
                                    <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Status</th>
                                    <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Control</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-50">
                                <AnimatePresence mode="popLayout">
                                    {loading ? (
                                        [...Array(6)].map((_, i) => (
                                            <tr key={`skeleton-${i}`} className="animate-pulse">
                                                <td className="px-8 py-6">
                                                    <div className="h-4 bg-slate-100 rounded w-48 mb-2"></div>
                                                    <div className="h-3 bg-slate-50 rounded w-32"></div>
                                                </td>
                                                <td className="px-8 py-6">
                                                    <div className="h-4 bg-slate-100 rounded w-32 mb-2"></div>
                                                    <div className="h-3 bg-slate-50 rounded w-24"></div>
                                                </td>
                                                <td className="px-8 py-6"><div className="h-6 bg-slate-100 rounded-full w-20"></div></td>
                                                <td className="px-8 py-6"><div className="h-4 bg-slate-100 rounded w-24"></div></td>
                                                <td className="px-8 py-6"><div className="h-6 bg-slate-100 rounded-full w-16"></div></td>
                                                <td className="px-8 py-6 flex justify-end gap-2"><div className="h-10 w-10 bg-slate-100 rounded-xl"></div></td>
                                            </tr>
                                        ))
                                    ) : filteredVacancies.map((v) => (
                                        <motion.tr
                                            key={v.id}
                                            layout
                                            initial={{ opacity: 0, y: 10 }}
                                            animate={{ opacity: 1, y: 0 }}
                                            exit={{ opacity: 0, scale: 0.95 }}
                                            className="hover:bg-emerald-50/30 transition-all group"
                                        >
                                            <td className="px-8 py-6">
                                                <div className="flex flex-col">
                                                    <span className="font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">
                                                        {v.title_en}
                                                    </span>
                                                    <span className="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1 flex items-center gap-2">
                                                        <FaCalendarAlt className="text-slate-300" /> Posted {new Date(v.created_at).toLocaleDateString()}
                                                    </span>
                                                </div>
                                            </td>
                                            <td className="px-8 py-6">
                                                <div className="flex flex-col gap-1">
                                                    <div className="text-xs font-bold text-slate-600 flex items-center gap-2">
                                                        <FaBuilding className="text-slate-300" /> {v.department}
                                                    </div>
                                                    <div className="text-[10px] font-bold text-slate-400 flex items-center gap-2">
                                                        <FaMapMarkerAlt className="text-slate-300" /> {v.location_en}
                                                    </div>
                                                </div>
                                            </td>
                                            <td className="px-8 py-6">
                                                <span className={`px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-[0.1em] border-2 shadow-sm ${TYPE_COLORS[v.vacancy_type as keyof typeof TYPE_COLORS] || 'bg-slate-100 text-slate-700 border-slate-200'}`}>
                                                    {v.vacancy_type}
                                                </span>
                                            </td>
                                            <td className="px-8 py-6">
                                                <div className={`text-xs font-bold italic ${new Date(v.deadline) < new Date() ? 'text-rose-500' : 'text-slate-500'}`}>
                                                    {new Date(v.deadline).toLocaleDateString(undefined, {
                                                        month: 'short',
                                                        day: 'numeric',
                                                        year: 'numeric'
                                                    })}
                                                    {new Date(v.deadline) < new Date() && (
                                                        <span className="block text-[8px] font-black uppercase not-italic tracking-widest mt-0.5">Expired</span>
                                                    )}
                                                </div>
                                            </td>
                                            <td className="px-8 py-6">
                                                <span className={`inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-sm ${v.is_active ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-rose-100 text-rose-700 border border-rose-200'}`}>
                                                    {v.is_active ? <FaCheckCircle size={8} /> : <FaTimesCircle size={8} />}
                                                    {v.is_active ? 'Active' : 'Closed'}
                                                </span>
                                            </td>
                                            <td className="px-8 py-6 text-right">
                                                <div className="flex justify-end gap-2">
                                                    <button
                                                        onClick={() => handleOpenModal(v)}
                                                        className="w-10 h-10 flex items-center justify-center bg-white text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all shadow-sm border border-slate-100"
                                                        title="Refine Listing"
                                                    >
                                                        <FaEdit size={14} />
                                                    </button>
                                                    <button
                                                        onClick={() => handleDelete(v.id)}
                                                        className="w-10 h-10 flex items-center justify-center bg-white text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all shadow-sm border border-slate-100"
                                                        title="Remove Listing"
                                                    >
                                                        <FaTrash size={14} />
                                                    </button>
                                                </div>
                                            </td>
                                        </motion.tr>
                                    ))}
                                </AnimatePresence>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {/* Modal */}
            <AnimatePresence>
                {showModal && (
                    <div className="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto">
                        <motion.div
                            initial={{ opacity: 0, scale: 0.95, y: 20 }}
                            animate={{ opacity: 1, scale: 1, y: 0 }}
                            exit={{ opacity: 0, scale: 0.95, y: 20 }}
                            className="bg-white rounded-3xl shadow-2xl w-full max-w-4xl my-8 overflow-hidden border border-gray-100"
                        >
                            <div className="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                                <div>
                                    <h2 className="text-2xl font-bold text-gray-900">{editingVacancy ? 'Edit Vacancy' : 'New Career Opportunity'}</h2>
                                    <p className="text-gray-500 text-sm mt-1">Provide full details for the job opening.</p>
                                </div>
                                <button onClick={() => setShowModal(false)} className="w-10 h-10 flex items-center justify-center rounded-full text-gray-400 hover:bg-white hover:text-gray-600 transition-all border border-transparent hover:border-gray-200">×</button>
                            </div>

                            <form onSubmit={handleSubmit} className="p-8 space-y-8">
                                {/* Localized Titles */}
                                <div className="space-y-4">
                                    <div className="flex items-center gap-2 text-primary font-bold text-sm uppercase tracking-wider mb-4">
                                        <FaInfoCircle /> Primary Details
                                    </div>
                                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div className="space-y-2">
                                            <label className="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Job Title (EN)</label>
                                            <input
                                                type="text"
                                                required
                                                className="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all font-medium"
                                                placeholder="e.g. Senior Software Engineer"
                                                value={formData.title_en}
                                                onChange={(e) => setFormData({ ...formData, title_en: e.target.value })}
                                            />
                                        </div>
                                        <div className="space-y-2">
                                            <label className="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">የሥራ መደብ (AM)</label>
                                            <input
                                                type="text"
                                                className="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all font-amharic"
                                                placeholder="የሲኒየር ሶፍትዌር መሃንዲስ"
                                                value={formData.title_am}
                                                onChange={(e) => setFormData({ ...formData, title_am: e.target.value })}
                                            />
                                        </div>
                                        <div className="space-y-2">
                                            <label className="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Mata-duree (OR)</label>
                                            <input
                                                type="text"
                                                className="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all"
                                                placeholder="..."
                                                value={formData.title_or}
                                                onChange={(e) => setFormData({ ...formData, title_or: e.target.value })}
                                            />
                                        </div>
                                    </div>
                                </div>

                                {/* Logistics */}
                                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div className="space-y-2">
                                        <label className="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Department</label>
                                        <input
                                            type="text"
                                            required
                                            className="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all"
                                            value={formData.department}
                                            onChange={(e) => setFormData({ ...formData, department: e.target.value })}
                                        />
                                    </div>
                                    <div className="space-y-2">
                                        <label className="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Employment Type</label>
                                        <select
                                            className="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all appearance-none"
                                            value={formData.vacancy_type}
                                            onChange={(e) => setFormData({ ...formData, vacancy_type: e.target.value as any })}
                                        >
                                            <option value="Full-time">Full-time</option>
                                            <option value="Part-time">Part-time</option>
                                            <option value="Contract">Contract</option>
                                            <option value="Internship">Internship</option>
                                        </select>
                                    </div>
                                    <div className="space-y-2">
                                        <label className="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Application Deadline</label>
                                        <input
                                            type="date"
                                            required
                                            className="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all underline-none"
                                            value={formData.deadline}
                                            onChange={(e) => setFormData({ ...formData, deadline: e.target.value })}
                                        />
                                    </div>
                                </div>

                                {/* Localized Locations */}
                                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div className="space-y-2">
                                        <label className="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Location (EN)</label>
                                        <input
                                            type="text"
                                            className="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all"
                                            value={formData.location_en}
                                            onChange={(e) => setFormData({ ...formData, location_en: e.target.value })}
                                        />
                                    </div>
                                    <div className="space-y-2">
                                        <label className="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">ቦታ (AM)</label>
                                        <input
                                            type="text"
                                            className="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all font-amharic"
                                            value={formData.location_am}
                                            onChange={(e) => setFormData({ ...formData, location_am: e.target.value })}
                                        />
                                    </div>
                                    <div className="space-y-2">
                                        <label className="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Bakka (OR)</label>
                                        <input
                                            type="text"
                                            className="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all"
                                            value={formData.location_or}
                                            onChange={(e) => setFormData({ ...formData, location_or: e.target.value })}
                                        />
                                    </div>
                                </div>

                                {/* Descriptions and Requirements Tabs-like */}
                                <div className="space-y-6">
                                    <div className="flex items-center gap-2 text-primary font-bold text-sm uppercase tracking-wider">
                                        <FaListUl /> Detailed narrations
                                    </div>

                                    <div className="grid grid-cols-1 gap-8">
                                        {/* Description EN */}
                                        <div className="space-y-2">
                                            <label className="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Job Description (EN)</label>
                                            <textarea
                                                className="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all h-24"
                                                required
                                                value={formData.description_en}
                                                onChange={(e) => setFormData({ ...formData, description_en: e.target.value })}
                                            />
                                        </div>

                                        {/* Requirements EN */}
                                        <div className="space-y-2">
                                            <label className="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Key Requirements (EN)</label>
                                            <textarea
                                                className="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all h-24"
                                                value={formData.requirements_en}
                                                onChange={(e) => setFormData({ ...formData, requirements_en: e.target.value })}
                                            />
                                        </div>

                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                                            <div className="space-y-4">
                                                <label className="text-xs font-bold text-gray-700 uppercase tracking-widest ml-1">Amharic Support</label>
                                                <textarea
                                                    placeholder="መግለጫ..."
                                                    className="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all h-20 font-amharic"
                                                    value={formData.description_am}
                                                    onChange={(e) => setFormData({ ...formData, description_am: e.target.value })}
                                                />
                                                <textarea
                                                    placeholder="መስፈርቶች..."
                                                    className="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all h-20 font-amharic"
                                                    value={formData.requirements_am}
                                                    onChange={(e) => setFormData({ ...formData, requirements_am: e.target.value })}
                                                />
                                            </div>
                                            <div className="space-y-4">
                                                <label className="text-xs font-bold text-gray-700 uppercase tracking-widest ml-1">Oromo Support</label>
                                                <textarea
                                                    placeholder="Ibsa..."
                                                    className="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all h-20"
                                                    value={formData.description_or}
                                                    onChange={(e) => setFormData({ ...formData, description_or: e.target.value })}
                                                />
                                                <textarea
                                                    placeholder="Ulaagaalee..."
                                                    className="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all h-20"
                                                    value={formData.requirements_or}
                                                    onChange={(e) => setFormData({ ...formData, requirements_or: e.target.value })}
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div className="flex items-center gap-3 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                    <div className="relative inline-flex items-center cursor-pointer">
                                        <input
                                            type="checkbox"
                                            id="is_active"
                                            className="sr-only peer"
                                            checked={formData.is_active}
                                            onChange={(e) => setFormData({ ...formData, is_active: e.target.checked })}
                                        />
                                        <div className="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                        <label htmlFor="is_active" className="ml-3 text-sm font-bold text-gray-700 uppercase tracking-wider">Publish immediately</label>
                                    </div>
                                </div>

                                <div className="pt-6 border-t border-gray-100 flex justify-end gap-3 sticky bottom-0 bg-white">
                                    <button
                                        type="button"
                                        onClick={() => setShowModal(false)}
                                        className="px-6 py-3 text-gray-600 font-bold hover:text-gray-900 transition-colors"
                                    >
                                        Discard
                                    </button>
                                    <button
                                        type="submit"
                                        className="px-10 py-3 bg-gradient-to-r from-primary to-blue-700 text-white rounded-xl font-bold shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all active:scale-95"
                                    >
                                        {editingVacancy ? 'Save Changes' : 'Post Opening'}
                                    </button>
                                </div>
                            </form>
                        </motion.div>
                    </div>
                )}
            </AnimatePresence>
        </AdminLayout>
    );
}
