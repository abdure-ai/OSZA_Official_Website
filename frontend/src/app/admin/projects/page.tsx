'use client';
import { useEffect, useState, useCallback } from 'react';
import AdminLayout from '@/components/admin/AdminLayout';
import { fetchAllProjectsAdmin, createProject, updateProject, deleteProject, Project, getFileUrl } from '@/lib/api';
import { FaPlus, FaEdit, FaTrash, FaSpinner, FaImage, FaSearch, FaTimes, FaProjectDiagram, FaCalendarAlt, FaMoneyBillWave, FaMapMarkerAlt, FaFileAlt } from 'react-icons/fa';

const STATUS_COLORS: Record<string, string> = {
    'Planning': 'bg-purple-50 text-purple-700 border-purple-100',
    'Ongoing': 'bg-blue-50 text-blue-700 border-blue-100',
    'On Hold': 'bg-amber-50 text-amber-700 border-amber-100',
    'Completed': 'bg-emerald-50 text-emerald-700 border-emerald-100',
    'Cancelled': 'bg-red-50 text-red-700 border-red-100',
};

export default function AdminProjectsPage() {
    const [projects, setProjects] = useState<Project[]>([]);
    const [loading, setLoading] = useState(true);
    const [showModal, setShowModal] = useState(false);
    const [editingProject, setEditingProject] = useState<Project | null>(null);
    const [token, setToken] = useState('');
    const [image, setImage] = useState<File | null>(null);
    const [imagePreview, setImagePreview] = useState('');
    const [searchQuery, setSearchQuery] = useState('');
    const [submitting, setSubmitting] = useState(false);

    const emptyForm = {
        title_en: '', title_am: '', title_or: '',
        description_en: '', description_am: '', description_or: '',
        location_en: '', start_date: '', end_date: '', status: 'Planning',
        budget: '', progress: '0', contractor: '', funding_source: '', is_published: true
    };
    const [formData, setFormData] = useState({ ...emptyForm });

    const loadProjects = useCallback(async (t: string) => {
        setLoading(true);
        try {
            const data = await fetchAllProjectsAdmin(t);
            setProjects(data);
        } finally {
            setLoading(false);
        }
    }, []);

    useEffect(() => {
        const t = localStorage.getItem('adminToken') || '';
        setToken(t);
        loadProjects(t);
    }, [loadProjects]);

    const handleOpen = (p?: Project) => {
        setImage(null);
        setImagePreview('');
        if (p) {
            setEditingProject(p);
            setFormData({
                title_en: p.title_en,
                title_am: p.title_am || '',
                title_or: p.title_or || '',
                description_en: p.description_en || '',
                description_am: p.description_am || '',
                description_or: p.description_or || '',
                location_en: p.location_en || '',
                start_date: p.start_date?.split('T')[0] || '',
                end_date: p.end_date?.split('T')[0] || '',
                status: p.status,
                budget: p.budget?.toString() || '',
                progress: p.progress?.toString() || '0',
                contractor: p.contractor || '',
                funding_source: p.funding_source || '',
                is_published: !!p.is_published
            });
            if (p.cover_image_url) setImagePreview(getFileUrl(p.cover_image_url));
        } else {
            setEditingProject(null);
            setFormData({ ...emptyForm });
        }
        setShowModal(true);
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setSubmitting(true);
        const fd = new FormData();
        Object.entries(formData).forEach(([k, v]) => fd.append(k, String(v)));
        if (image) fd.append('cover_image', image);
        try {
            if (editingProject) await updateProject(editingProject.id, fd, token);
            else await createProject(fd, token);
            setShowModal(false);
            loadProjects(token);
        } catch (err: any) {
            alert(err.message);
        } finally {
            setSubmitting(false);
        }
    };

    const handleDelete = async (id: number, title: string) => {
        if (!confirm(`Delete project "${title}"?`)) return;
        try {
            await deleteProject(id, token);
            loadProjects(token);
        } catch (err: any) {
            alert(err.message);
        }
    };

    const filteredProjects = projects.filter(p =>
        p.title_en.toLowerCase().includes(searchQuery.toLowerCase()) ||
        p.location_en?.toLowerCase().includes(searchQuery.toLowerCase())
    );

    return (
        <AdminLayout>
            <div className="mb-10">
                <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div className="flex items-center gap-3 mb-2">
                            <span className="h-1 w-12 bg-blue-600 rounded-full"></span>
                            <span className="text-[10px] font-black uppercase tracking-[0.3em] text-blue-600">Strategic Infrastructure</span>
                        </div>
                        <h1 className="text-4xl font-black text-slate-900 tracking-tight italic">
                            Project <span className="text-blue-600">Portfolio</span>
                        </h1>
                        <p className="text-slate-500 mt-2 text-sm font-medium">Monitor and manage regional development initiatives and pipeline progress.</p>
                    </div>
                    <button
                        onClick={() => handleOpen()}
                        className="group bg-slate-900 text-white px-8 py-4 rounded-2xl flex items-center gap-3 hover:bg-slate-800 transition-all shadow-2xl shadow-slate-200 active:scale-95"
                    >
                        <div className="w-6 h-6 bg-white/10 rounded-lg flex items-center justify-center group-hover:rotate-90 transition-transform">
                            <FaPlus className="text-xs" />
                        </div>
                        <span className="font-black text-[10px] uppercase tracking-widest">Register New Initiative</span>
                    </button>
                </div>
            </div>

            {/* Search Bar - Premium Style */}
            <div className="mb-8 relative max-w-2xl">
                <FaSearch className="absolute left-6 top-1/2 -translate-y-1/2 text-slate-300" />
                <input
                    type="text"
                    placeholder="Search initiatives by nomenclature or geography..."
                    value={searchQuery}
                    onChange={(e) => setSearchQuery(e.target.value)}
                    className="w-full pl-14 pr-6 py-5 bg-white border-2 border-slate-100 rounded-[2rem] shadow-sm focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 outline-none transition-all font-bold text-slate-700 placeholder:text-slate-300 placeholder:font-medium"
                />
            </div>

            <div className="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="bg-slate-900">
                                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Initiative Detail</th>
                                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Status & Progress</th>
                                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hidden lg:table-cell">Timeline</th>
                                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hidden md:table-cell">Finance</th>
                                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Operations</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-50">
                            {loading ? (
                                Array.from({ length: 5 }).map((_, i) => (
                                    <tr key={i} className="animate-pulse">
                                        <td className="px-6 py-4 flex items-center gap-4">
                                            <div className="w-12 h-12 bg-gray-200 rounded-xl" />
                                            <div className="space-y-2">
                                                <div className="h-4 bg-gray-200 rounded w-48" />
                                                <div className="h-3 bg-gray-100 rounded w-32" />
                                            </div>
                                        </td>
                                        <td className="px-6 py-4"><div className="h-4 bg-gray-100 rounded w-32" /></td>
                                        <td className="px-6 py-4"><div className="h-4 bg-gray-100 rounded w-24" /></td>
                                        <td className="px-6 py-4"><div className="h-4 bg-gray-100 rounded w-24" /></td>
                                        <td className="px-6 py-4" />
                                    </tr>
                                ))
                            ) : filteredProjects.length === 0 ? (
                                <tr><td colSpan={5} className="px-6 py-20 text-center">
                                    <FaProjectDiagram className="text-5xl text-gray-100 mx-auto mb-4" />
                                    <h3 className="text-gray-400 font-black uppercase tracking-tighter text-lg">No Projects Found</h3>
                                    <p className="text-gray-400 text-sm italic">Initialize a new project to start tracking.</p>
                                </td></tr>
                            ) : filteredProjects.map(p => (
                                <tr key={p.id} className="hover:bg-blue-50/30 transition-all group">
                                    <td className="px-8 py-6">
                                        <div className="flex items-center gap-4">
                                            {p.cover_image_url ? (
                                                <img
                                                    src={getFileUrl(p.cover_image_url)}
                                                    className="w-14 h-14 object-cover rounded-2xl shadow-sm group-hover:scale-110 transition-transform duration-500"
                                                    alt="Cover"
                                                />
                                            ) : (
                                                <div className="w-14 h-14 bg-blue-50 rounded-2xl border border-blue-100 flex items-center justify-center text-blue-400 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                                                    <FaProjectDiagram size={20} />
                                                </div>
                                            )}
                                            <div className="max-w-xs xl:max-w-md">
                                                <span className="font-black text-slate-900 block truncate group-hover:text-blue-600 transition-colors uppercase tracking-tight">{p.title_en}</span>
                                                <span className="text-[10px] text-slate-400 font-black uppercase tracking-widest flex items-center gap-2 mt-1 italic">
                                                    <FaMapMarkerAlt className="text-blue-500" /> {p.location_en || 'Regional Jurisdiction'}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-8 py-6">
                                        <div className="space-y-3">
                                            <span className={`inline-flex px-3 py-1 rounded-xl text-[9px] font-black uppercase tracking-[0.1em] border shadow-sm ${STATUS_COLORS[p.status] || 'bg-slate-50 text-slate-600'}`}>
                                                {p.status}
                                            </span>
                                            <div className="flex items-center gap-3">
                                                <div className="flex-1 bg-slate-100 rounded-full h-2 w-28 overflow-hidden shadow-inner">
                                                    <div
                                                        className="bg-blue-600 h-2 rounded-full transition-all duration-1000 shadow-[0_0_10px_rgba(37,99,235,0.3)]"
                                                        style={{ width: `${p.progress}%` }}
                                                    />
                                                </div>
                                                <span className="text-[10px] font-black text-slate-500 tabular-nums italic">{p.progress}%</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-8 py-6 hidden lg:table-cell">
                                        <div className="flex flex-col gap-1">
                                            <span className="flex items-center gap-2 text-xs font-bold text-slate-900">
                                                <FaCalendarAlt className="text-blue-500" size={12} />
                                                {p.start_date ? new Date(p.start_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : 'Pending'}
                                            </span>
                                            <span className="text-[9px] font-black uppercase tracking-widest text-slate-400 italic mt-0.5">Commencement</span>
                                        </div>
                                    </td>
                                    <td className="px-8 py-6 hidden md:table-cell">
                                        <div className="flex flex-col">
                                            <span className="font-black text-slate-900 tabular-nums text-sm">
                                                {p.budget ? `ETB ${Number(p.budget).toLocaleString()}` : 'Unallocated'}
                                            </span>
                                            <span className="text-[9px] font-black uppercase text-slate-400 tracking-widest italic mt-0.5">{p.funding_source || 'Treasury Funded'}</span>
                                        </div>
                                    </td>
                                    <td className="px-8 py-6">
                                        <div className="flex justify-end gap-2">
                                            <button
                                                onClick={() => handleOpen(p)}
                                                className="w-10 h-10 flex items-center justify-center bg-white text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all shadow-sm border border-slate-100"
                                                title="Edit Initiative"
                                            >
                                                <FaEdit size={14} />
                                            </button>
                                            <button
                                                onClick={() => handleDelete(p.id, p.title_en)}
                                                className="w-10 h-10 flex items-center justify-center bg-white text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all shadow-sm border border-slate-100"
                                                title="Terminate"
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
                <div className="fixed inset-0 bg-black/60 backdrop-blur-md flex items-center justify-center z-50 p-4">
                    <div className="bg-white rounded-[2rem] shadow-[0_32px_64px_rgba(0,0,0,0.4)] w-full max-w-5xl max-h-[90vh] overflow-hidden flex flex-col border border-white/20">
                        <div className="flex items-center justify-between p-8 border-b bg-gray-50/50">
                            <div>
                                <h2 className="text-2xl font-black text-gray-900 tracking-tighter italic">
                                    {editingProject ? 'Modify Initiative' : 'Initialize Project'}
                                </h2>
                                <p className="text-[10px] text-gray-500 font-bold uppercase tracking-[0.2em] mt-1">Strategic Development Registry</p>
                            </div>
                            <button onClick={() => setShowModal(false)} className="bg-white p-3 rounded-full border shadow-sm text-gray-400 hover:text-red-500 hover:border-red-500 transition-all transform hover:rotate-90">
                                <FaTimes size={20} />
                            </button>
                        </div>

                        <form onSubmit={handleSubmit} className="flex-grow overflow-y-auto p-10 space-y-12 scrollbar-thin scrollbar-thumb-gray-200">

                            {/* Section 1: Localization */}
                            <div className="space-y-8">
                                <div className="pb-2 border-b-2 border-blue-600 w-fit">
                                    <h3 className="text-xs font-black uppercase tracking-widest text-blue-600">Localized Nomenclature</h3>
                                </div>
                                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Project Title (EN) <span className="text-red-500">*</span></label>
                                        <input required value={formData.title_en} onChange={e => setFormData({ ...formData, title_en: e.target.value })}
                                            className="w-full border-2 border-gray-100 bg-gray-50/30 rounded-2xl px-5 py-3 focus:outline-none focus:border-blue-500 focus:bg-white transition-all font-bold"
                                            placeholder="Descriptive Title" />
                                    </div>
                                    <div>
                                        <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Project Title (AM)</label>
                                        <input value={formData.title_am} onChange={e => setFormData({ ...formData, title_am: e.target.value })}
                                            className="w-full border-2 border-gray-100 bg-gray-50/30 rounded-2xl px-5 py-3 focus:outline-none focus:border-blue-500 focus:bg-white transition-all font-medium text-sm"
                                            placeholder="የፕሮጀክት ስም..." />
                                    </div>
                                    <div>
                                        <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Project Title (OR)</label>
                                        <input value={formData.title_or} onChange={e => setFormData({ ...formData, title_or: e.target.value })}
                                            className="w-full border-2 border-gray-100 bg-gray-50/30 rounded-2xl px-5 py-3 focus:outline-none focus:border-blue-500 focus:bg-white transition-all font-medium text-sm"
                                            placeholder="Maqaa Piroojaktii..." />
                                    </div>
                                </div>
                            </div>

                            {/* Section 2: Core Logistics */}
                            <div className="space-y-8">
                                <div className="pb-2 border-b-2 border-amber-500 w-fit">
                                    <h3 className="text-xs font-black uppercase tracking-widest text-amber-600">Strategic Logistics</h3>
                                </div>
                                <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
                                    <div className="md:col-span-2">
                                        <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Location / Woreda</label>
                                        <div className="relative">
                                            <FaMapMarkerAlt className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-300" />
                                            <input value={formData.location_en} onChange={e => setFormData({ ...formData, location_en: e.target.value })}
                                                className="w-full border-2 border-gray-100 bg-gray-50/30 rounded-2xl pl-12 pr-5 py-3 focus:outline-none focus:border-amber-500 focus:bg-white transition-all font-bold"
                                                placeholder="Geographic Focus" />
                                        </div>
                                    </div>
                                    <div>
                                        <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Operational Status</label>
                                        <select value={formData.status} onChange={e => setFormData({ ...formData, status: e.target.value })}
                                            className="w-full border-2 border-gray-100 bg-gray-50/30 rounded-2xl px-5 py-3 focus:outline-none focus:border-amber-500 focus:bg-white transition-all font-black uppercase text-[10px] tracking-widest appearance-none">
                                            {['Planning', 'Ongoing', 'On Hold', 'Completed', 'Cancelled'].map(s => <option key={s} value={s}>{s}</option>)}
                                        </select>
                                    </div>
                                    <div>
                                        <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Real-time Progress (%)</label>
                                        <input type="number" min="0" max="100" value={formData.progress} onChange={e => setFormData({ ...formData, progress: e.target.value })}
                                            className="w-full border-2 border-gray-100 bg-gray-50/30 rounded-2xl px-5 py-3 focus:outline-none focus:border-amber-500 focus:bg-white transition-all font-black text-center" />
                                    </div>
                                    <div>
                                        <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Commencement Date</label>
                                        <input type="date" required value={formData.start_date} onChange={e => setFormData({ ...formData, start_date: e.target.value })}
                                            className="w-full border-2 border-gray-100 bg-gray-50/30 rounded-2xl px-5 py-3 focus:outline-none focus:border-amber-500 focus:bg-white transition-all font-bold text-xs" />
                                    </div>
                                    <div>
                                        <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Target Completion</label>
                                        <input type="date" value={formData.end_date} onChange={e => setFormData({ ...formData, end_date: e.target.value })}
                                            className="w-full border-2 border-gray-100 bg-gray-50/30 rounded-2xl px-5 py-3 focus:outline-none focus:border-amber-500 focus:bg-white transition-all font-bold text-xs" />
                                    </div>
                                    <div className="md:col-span-2">
                                        <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Lead Contractor / Executor</label>
                                        <input value={formData.contractor} onChange={e => setFormData({ ...formData, contractor: e.target.value })}
                                            className="w-full border-2 border-gray-100 bg-gray-50/30 rounded-2xl px-5 py-3 focus:outline-none focus:border-amber-500 focus:bg-white transition-all font-bold"
                                            placeholder="Responsible Entity" />
                                    </div>
                                </div>
                            </div>

                            {/* Section 3: Financials & Narrative */}
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-12">
                                <div className="space-y-8">
                                    <div className="pb-2 border-b-2 border-emerald-500 w-fit">
                                        <h3 className="text-xs font-black uppercase tracking-widest text-emerald-600">Fiscal Framework</h3>
                                    </div>
                                    <div className="grid grid-cols-1 gap-6">
                                        <div>
                                            <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Approved Budget (ETB)</label>
                                            <div className="relative">
                                                <FaMoneyBillWave className="absolute left-4 top-1/2 -translate-y-1/2 text-emerald-500" />
                                                <input type="number" value={formData.budget} onChange={e => setFormData({ ...formData, budget: e.target.value })}
                                                    className="w-full border-2 border-gray-100 bg-gray-50/30 rounded-2xl pl-12 pr-5 py-3.5 focus:outline-none focus:border-emerald-500 focus:bg-white transition-all font-black text-lg tabular-nums"
                                                    placeholder="0.00" />
                                            </div>
                                        </div>
                                        <div>
                                            <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Funding Source</label>
                                            <input value={formData.funding_source} onChange={e => setFormData({ ...formData, funding_source: e.target.value })}
                                                className="w-full border-2 border-gray-100 bg-gray-50/30 rounded-2xl px-5 py-3.5 focus:outline-none focus:border-emerald-500 focus:bg-white transition-all font-bold"
                                                placeholder="e.g. World Bank, Regional Govt" />
                                        </div>
                                        <div className="flex items-center gap-3 pt-2">
                                            <label className="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" checked={formData.is_published} onChange={e => setFormData({ ...formData, is_published: e.target.checked })} className="sr-only peer" />
                                                <div className="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                                <span className="ml-3 text-[10px] font-black uppercase tracking-widest text-gray-500">Public Visibility</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div className="space-y-8">
                                    <div className="pb-2 border-b-2 border-purple-500 w-fit">
                                        <h3 className="text-xs font-black uppercase tracking-widest text-purple-600">Visual Identity</h3>
                                    </div>
                                    <div className="flex gap-8 items-start">
                                        <div className="w-40 h-40 bg-gray-100 rounded-[2rem] border-2 border-dashed border-gray-200 flex items-center justify-center text-gray-300 relative overflow-hidden group shadow-inner">
                                            {imagePreview ? (
                                                <img src={imagePreview} className="w-full h-full object-cover" alt="Preview" />
                                            ) : (
                                                <FaImage size={32} />
                                            )}
                                        </div>
                                        <div className="flex-1 space-y-4">
                                            <h4 className="font-black text-gray-800 tracking-tight">Project Cover</h4>
                                            <p className="text-[10px] text-gray-500 font-medium leading-relaxed uppercase tracking-wider">A high-impact visual representing the initiative's outcome.</p>
                                            <label className="inline-block bg-white border-2 border-gray-100 px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest cursor-pointer hover:border-purple-500 transition-all shadow-sm">
                                                Browse Gallery
                                                <input type="file" accept="image/*" className="hidden"
                                                    onChange={e => {
                                                        const f = e.target.files?.[0];
                                                        if (f) { setImage(f); setImagePreview(URL.createObjectURL(f)); }
                                                    }} />
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Section 4: Project Narratives */}
                            <div className="space-y-8">
                                <div className="pb-2 border-b-2 border-rose-500 w-fit">
                                    <h3 className="text-xs font-black uppercase tracking-widest text-rose-600">Project Narratives</h3>
                                </div>
                                <div className="grid grid-cols-1 gap-8">
                                    <div>
                                        <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Scope & Objectives (EN)</label>
                                        <textarea value={formData.description_en} onChange={e => setFormData({ ...formData, description_en: e.target.value })} rows={4}
                                            className="w-full border-2 border-gray-100 bg-gray-50/30 rounded-[2rem] px-8 py-6 focus:outline-none focus:border-rose-500 focus:bg-white transition-all font-medium leading-relaxed"
                                            placeholder="Detailed description of the project goals..." />
                                    </div>
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div>
                                            <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Summary (AM)</label>
                                            <textarea value={formData.description_am} onChange={e => setFormData({ ...formData, description_am: e.target.value })} rows={3}
                                                className="w-full border-2 border-gray-100 bg-gray-50/30 rounded-[2rem] px-6 py-5 focus:outline-none focus:border-rose-500 focus:bg-white transition-all font-medium text-sm"
                                                placeholder="..." />
                                        </div>
                                        <div>
                                            <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Summary (OR)</label>
                                            <textarea value={formData.description_or} onChange={e => setFormData({ ...formData, description_or: e.target.value })} rows={3}
                                                className="w-full border-2 border-gray-100 bg-gray-50/30 rounded-[2rem] px-6 py-5 focus:outline-none focus:border-rose-500 focus:bg-white transition-all font-medium text-sm"
                                                placeholder="..." />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div className="flex justify-end gap-4 pt-10 border-t sticky bottom-0 bg-white/90 backdrop-blur-xl pb-2">
                                <button type="button" onClick={() => setShowModal(false)} className="px-10 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 bg-gray-50 rounded-2xl hover:bg-gray-100 transition-all">Discard</button>
                                <button type="submit" disabled={submitting} className="px-12 py-4 bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-blue-700 transition-all disabled:opacity-50 shadow-[0_10px_30px_rgba(26,86,219,0.3)]">
                                    {submitting ? 'Synchronizing...' : (editingProject ? 'Update Pipeline' : 'Initiate Pipeline')}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}
