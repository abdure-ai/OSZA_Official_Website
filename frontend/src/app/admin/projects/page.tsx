'use client';

import { useEffect, useState } from 'react';
import AdminLayout from '@/components/admin/AdminLayout';
import { fetchAllProjectsAdmin, createProject, updateProject, deleteProject, Project, getFileUrl } from '@/lib/api';
import { FaPlus, FaEdit, FaTrash, FaSpinner, FaImage } from 'react-icons/fa';

const STATUS_COLORS: Record<string, string> = {
    'Planning': 'bg-purple-100 text-purple-700',
    'In Progress': 'bg-blue-100 text-blue-700',
    'On Hold': 'bg-yellow-100 text-yellow-700',
    'Completed': 'bg-green-100 text-green-700',
    'Cancelled': 'bg-red-100 text-red-700',
};

export default function AdminProjectsPage() {
    const [projects, setProjects] = useState<Project[]>([]);
    const [loading, setLoading] = useState(true);
    const [showModal, setShowModal] = useState(false);
    const [editingProject, setEditingProject] = useState<Project | null>(null);
    const [token, setToken] = useState('');
    const [image, setImage] = useState<File | null>(null);

    const emptyForm = {
        title_en: '', description_en: '', location_en: '',
        start_date: '', end_date: '', status: 'Planning',
        budget: '', progress: '0', contractor: '', funding_source: '', is_published: true
    };
    const [formData, setFormData] = useState({ ...emptyForm });

    useEffect(() => {
        const t = localStorage.getItem('adminToken') || '';
        setToken(t);
        loadProjects(t);
    }, []);

    async function loadProjects(t = token) {
        setLoading(true);
        const data = await fetchAllProjectsAdmin(t);
        setProjects(data);
        setLoading(false);
    }

    const handleOpen = (p?: Project) => {
        setImage(null);
        if (p) {
            setEditingProject(p);
            setFormData({
                title_en: p.title_en, description_en: p.description_en || '',
                location_en: p.location_en || '', start_date: p.start_date?.split('T')[0] || '',
                end_date: p.end_date?.split('T')[0] || '', status: p.status,
                budget: p.budget?.toString() || '', progress: p.progress?.toString() || '0',
                contractor: p.contractor || '', funding_source: p.funding_source || '',
                is_published: !!p.is_published
            });
        } else {
            setEditingProject(null);
            setFormData({ ...emptyForm });
        }
        setShowModal(true);
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        const fd = new FormData();
        Object.entries(formData).forEach(([k, v]) => fd.append(k, String(v)));
        if (image) fd.append('cover_image', image);
        try {
            if (editingProject) await updateProject(editingProject.id, fd, token);
            else await createProject(fd, token);
            setShowModal(false);
            loadProjects();
        } catch (err: any) { alert(err.message); }
    };

    const handleDelete = async (id: number) => {
        if (!confirm('Delete this project?')) return;
        try { await deleteProject(id, token); loadProjects(); }
        catch (err: any) { alert(err.message); }
    };

    return (
        <AdminLayout>
            <div className="flex justify-between items-center mb-6">
                <div>
                    <h1 className="text-2xl font-bold text-gray-800">Projects Management</h1>
                    <p className="text-gray-500 text-sm">Manage development projects and their progress.</p>
                </div>
                <button onClick={() => handleOpen()} className="bg-primary text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-opacity-90">
                    <FaPlus /> Add Project
                </button>
            </div>

            <div className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
                {loading ? (
                    <div className="p-12 text-center text-gray-400"><FaSpinner className="animate-spin text-4xl mx-auto mb-4" /></div>
                ) : (
                    <table className="w-full text-left min-w-[900px]">
                        <thead className="bg-gray-50 border-b">
                            <tr>
                                {['Project', 'Location', 'Status', 'Progress', 'Start Date', 'Budget (ETB)', 'Actions'].map(h => (
                                    <th key={h} className="px-5 py-4 text-sm font-semibold text-gray-600">{h}</th>
                                ))}
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {projects.map(p => (
                                <tr key={p.id} className="hover:bg-gray-50">
                                    <td className="px-5 py-4">
                                        <div className="font-medium text-gray-800 max-w-xs truncate">{p.title_en}</div>
                                        <div className="text-xs text-gray-400">{p.funding_source}</div>
                                    </td>
                                    <td className="px-5 py-4 text-gray-600 text-sm">{p.location_en}</td>
                                    <td className="px-5 py-4">
                                        <span className={`px-2 py-1 rounded-full text-xs font-medium ${STATUS_COLORS[p.status]}`}>{p.status}</span>
                                    </td>
                                    <td className="px-5 py-4 w-36">
                                        <div className="flex items-center gap-2">
                                            <div className="flex-1 bg-gray-200 rounded-full h-2">
                                                <div className="bg-primary h-2 rounded-full" style={{ width: `${p.progress}%` }} />
                                            </div>
                                            <span className="text-xs font-bold text-gray-600">{p.progress}%</span>
                                        </div>
                                    </td>
                                    <td className="px-5 py-4 text-gray-600 text-sm">{new Date(p.start_date).toLocaleDateString()}</td>
                                    <td className="px-5 py-4 text-gray-600 text-sm font-mono">
                                        {p.budget ? Number(p.budget).toLocaleString() : '—'}
                                    </td>
                                    <td className="px-5 py-4 text-right space-x-2">
                                        <button onClick={() => handleOpen(p)} className="text-blue-500 hover:text-blue-700"><FaEdit /></button>
                                        <button onClick={() => handleDelete(p.id)} className="text-red-500 hover:text-red-700"><FaTrash /></button>
                                    </td>
                                </tr>
                            ))}
                            {!projects.length && <tr><td colSpan={7} className="px-6 py-12 text-center text-gray-400">No projects found.</td></tr>}
                        </tbody>
                    </table>
                )}
            </div>

            {showModal && (
                <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                    <div className="bg-white rounded-xl shadow-xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">
                        <div className="p-6 border-b flex justify-between items-center">
                            <h2 className="text-xl font-bold">{editingProject ? 'Edit Project' : 'New Project'}</h2>
                            <button onClick={() => setShowModal(false)} className="text-gray-400 hover:text-gray-600 text-2xl">×</button>
                        </div>
                        <form onSubmit={handleSubmit} className="p-6 space-y-4">
                            <div className="grid grid-cols-2 gap-4">
                                <div className="col-span-2">
                                    <label className="block text-sm font-medium mb-1">Project Title</label>
                                    <input required className="w-full p-2 border rounded-md" value={formData.title_en} onChange={e => setFormData({ ...formData, title_en: e.target.value })} />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium mb-1">Location</label>
                                    <input className="w-full p-2 border rounded-md" value={formData.location_en} onChange={e => setFormData({ ...formData, location_en: e.target.value })} />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium mb-1">Status</label>
                                    <select className="w-full p-2 border rounded-md" value={formData.status} onChange={e => setFormData({ ...formData, status: e.target.value })}>
                                        {['Planning', 'In Progress', 'On Hold', 'Completed', 'Cancelled'].map(s => <option key={s}>{s}</option>)}
                                    </select>
                                </div>
                                <div>
                                    <label className="block text-sm font-medium mb-1">Start Date</label>
                                    <input type="date" required className="w-full p-2 border rounded-md" value={formData.start_date} onChange={e => setFormData({ ...formData, start_date: e.target.value })} />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium mb-1">End Date</label>
                                    <input type="date" className="w-full p-2 border rounded-md" value={formData.end_date} onChange={e => setFormData({ ...formData, end_date: e.target.value })} />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium mb-1">Budget (ETB)</label>
                                    <input type="number" className="w-full p-2 border rounded-md" value={formData.budget} onChange={e => setFormData({ ...formData, budget: e.target.value })} />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium mb-1">Progress (%)</label>
                                    <input type="number" min="0" max="100" className="w-full p-2 border rounded-md" value={formData.progress} onChange={e => setFormData({ ...formData, progress: e.target.value })} />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium mb-1">Contractor</label>
                                    <input className="w-full p-2 border rounded-md" value={formData.contractor} onChange={e => setFormData({ ...formData, contractor: e.target.value })} />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium mb-1">Funding Source</label>
                                    <input className="w-full p-2 border rounded-md" value={formData.funding_source} onChange={e => setFormData({ ...formData, funding_source: e.target.value })} />
                                </div>
                            </div>
                            <div>
                                <label className="block text-sm font-medium mb-1">Description</label>
                                <textarea className="w-full p-2 border rounded-md h-28" value={formData.description_en} onChange={e => setFormData({ ...formData, description_en: e.target.value })} />
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-4">
                                    <label className="block text-sm font-medium mb-1">Cover Image</label>
                                    <div className="flex items-center gap-4">
                                        {(image || editingProject?.cover_image_url) ? (
                                            <img
                                                src={image ? URL.createObjectURL(image) : getFileUrl(editingProject!.cover_image_url!)}
                                                className="w-16 h-16 object-cover rounded-lg border"
                                                alt="Preview"
                                            />
                                        ) : (
                                            <div className="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 border">
                                                <FaImage className="text-xl" />
                                            </div>
                                        )}
                                        <input type="file" className="flex-1 p-1.5 border rounded-md text-sm" accept="image/*" onChange={e => setImage(e.target.files?.[0] || null)} />
                                    </div>
                                </div>
                                <div className="flex items-end pb-1">
                                    <label className="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" checked={formData.is_published} onChange={e => setFormData({ ...formData, is_published: e.target.checked })} />
                                        <span className="text-sm font-medium">Published</span>
                                    </label>
                                </div>
                            </div>
                            <div className="pt-4 border-t flex justify-end gap-3">
                                <button type="button" onClick={() => setShowModal(false)} className="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                                <button type="submit" className="px-6 py-2 bg-primary text-white rounded-md hover:bg-opacity-90">{editingProject ? 'Update' : 'Create'}</button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}
