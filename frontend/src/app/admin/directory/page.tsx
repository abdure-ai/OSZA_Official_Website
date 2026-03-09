'use client';

import AdminLayout from '@/components/admin/AdminLayout';
import { useState, useEffect, useCallback, useRef } from 'react';
import {
    fetchDirectory, createContact, updateContact, deleteContact,
    DirectoryItem, getFileUrl
} from '@/lib/api';
import { FaUserPlus, FaTrash, FaEdit, FaPhone, FaEnvelope, FaMapMarkerAlt, FaImage, FaSave, FaTimes, FaSearch, FaFilter, FaIdCard, FaBuilding, FaUserTie, FaCheckCircle, FaTimesCircle, FaInfoCircle, FaSortAmountDown } from 'react-icons/fa';
import { AnimatePresence, motion } from 'framer-motion';

const CATEGORIES = ['General', 'Leadership', 'Department', 'Woreda Head', 'Security', 'Health', 'Education', 'Finance'];

export default function AdminDirectoryPage() {
    const [contacts, setContacts] = useState<DirectoryItem[]>([]);
    const [loading, setLoading] = useState(true);
    const [uploading, setUploading] = useState(false);
    const [error, setError] = useState('');
    const [success, setSuccess] = useState('');
    const [searchQuery, setSearchQuery] = useState('');
    const [selectedCategory, setSelectedCategory] = useState('All');

    // Editing state
    const [editingId, setEditingId] = useState<number | null>(null);
    const [showModal, setShowModal] = useState(false);

    // Form state
    const [formData, setFormData] = useState({
        name_en: '',
        name_am: '',
        name_or: '',
        position_en: '',
        position_am: '',
        position_or: '',
        department_en: '',
        department_am: '',
        department_or: '',
        phone: '',
        email: '',
        office_location: '',
        category: 'General',
        sort_order: '0'
    });
    const [photo, setPhoto] = useState<File | null>(null);
    const [previewUrl, setPreviewUrl] = useState('');
    const fileInputRef = useRef<HTMLInputElement>(null);

    const loadContacts = useCallback(async () => {
        setLoading(true);
        try {
            const data = await fetchDirectory();
            setContacts(data);
        } finally {
            setLoading(false);
        }
    }, []);

    useEffect(() => { loadContacts(); }, [loadContacts]);

    const openAdd = () => {
        resetForm();
        setShowModal(true);
    };

    const handleEdit = (contact: DirectoryItem) => {
        setEditingId(contact.id);
        setFormData({
            name_en: contact.name_en || '',
            name_am: contact.name_am || '',
            name_or: contact.name_or || '',
            position_en: contact.position_en || '',
            position_am: contact.position_am || '',
            position_or: contact.position_or || '',
            department_en: contact.department_en || '',
            department_am: contact.department_am || '',
            department_or: contact.department_or || '',
            phone: contact.phone || '',
            email: contact.email || '',
            office_location: contact.office_location || '',
            category: contact.category || 'General',
            sort_order: String(contact.sort_order || 0)
        });
        setPhoto(null);
        setPreviewUrl(contact.photo_url ? getFileUrl(contact.photo_url) : '');
        setShowModal(true);
    };

    const resetForm = () => {
        setEditingId(null);
        setFormData({
            name_en: '',
            name_am: '',
            name_or: '',
            position_en: '',
            position_am: '',
            position_or: '',
            department_en: '',
            department_am: '',
            department_or: '',
            phone: '',
            email: '',
            office_location: '',
            category: 'General',
            sort_order: '0'
        });
        setPhoto(null);
        setPreviewUrl('');
        if (fileInputRef.current) fileInputRef.current.value = '';
        setError('');
        setSuccess('');
    };

    const handlePhotoChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (file) {
            setPhoto(file);
            setPreviewUrl(URL.createObjectURL(file));
        }
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
            if (photo) data.append('photo', photo);

            const token = localStorage.getItem('adminToken') || '';

            if (editingId) {
                await updateContact(editingId, data, token);
                setSuccess('Contact updated successfully!');
            } else {
                await createContact(data, token);
                setSuccess('Contact added successfully!');
            }

            setShowModal(false);
            loadContacts();
        } catch (err: any) {
            setError(err.message || 'Operation failed');
        } finally {
            setUploading(false);
        }
    };

    const handleDelete = async (id: number) => {
        if (!confirm('Delete this contact permanently?')) return;
        try {
            const token = localStorage.getItem('adminToken') || '';
            await deleteContact(id, token);
            setContacts(prev => prev.filter(c => c.id !== id));
        } catch (err: any) {
            alert(err.message || 'Delete failed');
        }
    };

    const filteredContacts = contacts.filter(c => {
        const matchesSearch = c.name_en.toLowerCase().includes(searchQuery.toLowerCase()) ||
            (c.department_en || '').toLowerCase().includes(searchQuery.toLowerCase()) ||
            (c.position_en || '').toLowerCase().includes(searchQuery.toLowerCase());
        const matchesCategory = selectedCategory === 'All' || c.category === selectedCategory;
        return matchesSearch && matchesCategory;
    });

    return (
        <AdminLayout>
            <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h1 className="text-3xl font-bold text-gray-900 tracking-tight">Official Directory</h1>
                    <p className="text-gray-500 mt-1">Manage personnel records, departments, and leadership hierarchy.</p>
                </div>
                <button
                    onClick={openAdd}
                    className="bg-primary text-white px-6 py-2.5 rounded-xl flex items-center gap-2 hover:bg-opacity-90 transition-all shadow-lg shadow-primary/20 font-medium whitespace-nowrap"
                >
                    <FaUserPlus className="text-sm" /> Add New Official
                </button>
            </div>

            {/* Quick Filters & Stats */}
            <div className="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
                <div className="lg:col-span-3 bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-4">
                    <div className="relative flex-1">
                        <FaSearch className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" />
                        <input
                            type="text"
                            placeholder="Search by name, position, or department..."
                            className="w-full pl-12 pr-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all text-gray-700 font-medium"
                            value={searchQuery}
                            onChange={(e) => setSearchQuery(e.target.value)}
                        />
                    </div>
                    <div className="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0 scrollbar-hide">
                        {['All', ...CATEGORIES.slice(0, 4)].map(cat => (
                            <button
                                key={cat}
                                onClick={() => setSelectedCategory(cat)}
                                className={`px-4 py-2 rounded-lg text-sm font-bold whitespace-nowrap transition-all ${selectedCategory === cat
                                    ? 'bg-primary text-white shadow-md shadow-primary/20'
                                    : 'bg-gray-50 text-gray-600 hover:bg-gray-100'
                                    }`}
                            >
                                {cat}
                            </button>
                        ))}
                    </div>
                </div>
                <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p className="text-gray-400 text-[10px] font-black uppercase tracking-widest">Total Staff</p>
                        <h3 className="text-3xl font-black text-gray-900 mt-1">{contacts.length}</h3>
                    </div>
                    <div className="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-primary border border-gray-100">
                        <FaIdCard className="text-xl" />
                    </div>
                </div>
            </div>

            {/* List Section */}
            <div className="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead className="bg-gray-50/50 border-b border-gray-100">
                            <tr>
                                <th className="px-8 py-5 font-bold text-gray-400 text-[10px] uppercase tracking-widest">Full Name & Identity</th>
                                <th className="px-6 py-5 font-bold text-gray-400 text-[10px] uppercase tracking-widest">Designation & Sector</th>
                                <th className="px-6 py-5 font-bold text-gray-400 text-[10px] uppercase tracking-widest">Contact Details</th>
                                <th className="px-6 py-5 font-bold text-gray-400 text-[10px] uppercase tracking-widest">Category</th>
                                <th className="px-8 py-5 font-bold text-gray-400 text-[10px] uppercase tracking-widest text-right">Operations</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-50">
                            <AnimatePresence mode="popLayout">
                                {loading ? (
                                    [...Array(5)].map((_, i) => (
                                        <tr key={`skeleton-${i}`} className="animate-pulse">
                                            <td className="px-8 py-6">
                                                <div className="flex items-center gap-4">
                                                    <div className="w-12 h-12 bg-gray-100 rounded-full" />
                                                    <div className="space-y-2">
                                                        <div className="h-4 bg-gray-100 rounded w-32" />
                                                        <div className="h-3 bg-gray-50 rounded w-20" />
                                                    </div>
                                                </div>
                                            </td>
                                            <td colSpan={4}><div className="h-4 bg-gray-50 rounded mx-6 w-1/2" /></td>
                                        </tr>
                                    ))
                                ) : filteredContacts.length === 0 ? (
                                    <tr>
                                        <td colSpan={5} className="px-8 py-24 text-center">
                                            <div className="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                                <FaSearch className="text-3xl text-gray-200" />
                                            </div>
                                            <h3 className="text-lg font-bold text-gray-900 mb-1">No Personnel Found</h3>
                                            <p className="text-gray-500 max-w-xs mx-auto">Try refining your search or add a new official to the registry.</p>
                                        </td>
                                    </tr>
                                ) : filteredContacts.map(contact => (
                                    <motion.tr
                                        key={contact.id}
                                        layout
                                        initial={{ opacity: 0, y: 10 }}
                                        animate={{ opacity: 1, y: 0 }}
                                        exit={{ opacity: 0, scale: 0.95 }}
                                        className="group hover:bg-gray-50/70 transition-all border-l-4 border-l-transparent hover:border-l-primary"
                                    >
                                        <td className="px-8 py-6">
                                            <div className="flex items-center gap-4">
                                                <div className="shrink-0">
                                                    {contact.photo_url ? (
                                                        <img src={getFileUrl(contact.photo_url)} className="w-12 h-12 rounded-2xl object-cover shadow-sm ring-2 ring-white group-hover:ring-primary/20 transition-all" alt="" />
                                                    ) : (
                                                        <div className="w-12 h-12 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-gray-500 font-black text-xs border border-gray-100">
                                                            {contact.name_en.substring(0, 2).toUpperCase()}
                                                        </div>
                                                    )}
                                                </div>
                                                <div>
                                                    <span className="font-bold text-gray-900 block leading-tight group-hover:text-primary transition-colors">{contact.name_en}</span>
                                                    <span className="text-[10px] text-gray-400 font-bold uppercase tracking-tighter mt-0.5 block">{contact.name_am ? 'Localized (AM)' : 'EN Only'}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td className="px-6 py-6">
                                            <div className="flex flex-col">
                                                <span className="text-sm font-bold text-gray-800">{contact.position_en}</span>
                                                <span className="text-[10px] text-primary font-black uppercase tracking-widest mt-0.5 flex items-center gap-1">
                                                    <FaBuilding className="opacity-40" /> {contact.department_en || 'Central Admin'}
                                                </span>
                                            </div>
                                        </td>
                                        <td className="px-6 py-6 font-medium">
                                            <div className="flex flex-col gap-1.5">
                                                {contact.phone && (
                                                    <div className="text-[11px] text-gray-600 flex items-center gap-2">
                                                        <div className="w-5 h-5 bg-emerald-50 rounded-md flex items-center justify-center text-emerald-600"><FaPhone size={8} /></div>
                                                        {contact.phone}
                                                    </div>
                                                )}
                                                {contact.email && (
                                                    <div className="text-[11px] text-gray-600 flex items-center gap-2">
                                                        <div className="w-5 h-5 bg-blue-50 rounded-md flex items-center justify-center text-blue-600"><FaEnvelope size={8} /></div>
                                                        {contact.email}
                                                    </div>
                                                )}
                                            </div>
                                        </td>
                                        <td className="px-6 py-6">
                                            <span className="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-gray-200">
                                                {contact.category}
                                            </span>
                                        </td>
                                        <td className="px-8 py-6 text-right">
                                            <div className="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all translate-x-2 group-hover:translate-x-0">
                                                <button
                                                    onClick={() => handleEdit(contact)}
                                                    className="w-10 h-10 flex items-center justify-center bg-white text-blue-600 border border-gray-100 rounded-xl shadow-sm hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all active:scale-95"
                                                    title="Edit Record"
                                                >
                                                    <FaEdit size={14} />
                                                </button>
                                                <button
                                                    onClick={() => handleDelete(contact.id)}
                                                    className="w-10 h-10 flex items-center justify-center bg-white text-rose-600 border border-gray-100 rounded-xl shadow-sm hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all active:scale-95"
                                                    title="Expel Record"
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

            {/* Modal Overlay */}
            <AnimatePresence>
                {showModal && (
                    <div className="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto pt-10">
                        <motion.div
                            initial={{ opacity: 0, scale: 0.95, y: 20 }}
                            animate={{ opacity: 1, scale: 1, y: 0 }}
                            exit={{ opacity: 0, scale: 0.95, y: 20 }}
                            className="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-4xl my-auto overflow-hidden border border-gray-100 flex flex-col max-h-[90vh]"
                        >
                            <div className="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50 shrink-0">
                                <div className="flex items-center gap-5">
                                    <div className="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary border border-primary/5 shadow-inner">
                                        {editingId ? <FaUserTie className="text-2xl" /> : <FaUserPlus className="text-2xl" />}
                                    </div>
                                    <div>
                                        <h2 className="text-2xl font-black text-gray-900 tracking-tight">
                                            {editingId ? 'Refine Personnel Record' : 'Registry Enrollment'}
                                        </h2>
                                        <p className="text-gray-500 text-sm font-medium">Capture comprehensive official credentials.</p>
                                    </div>
                                </div>
                                <button onClick={() => setShowModal(false)} className="w-12 h-12 flex items-center justify-center rounded-2xl text-gray-400 hover:bg-white hover:text-rose-500 transition-all border border-transparent hover:border-gray-200 text-2xl group">
                                    <FaTimes className="group-hover:rotate-90 transition-transform" />
                                </button>
                            </div>

                            <form onSubmit={handleSubmit} className="p-8 space-y-10 overflow-y-auto custom-scrollbar">
                                {error && (
                                    <div className="text-rose-600 bg-rose-50 border border-rose-100 p-4 rounded-2xl text-sm font-bold flex items-center gap-3">
                                        <FaTimesCircle className="shrink-0 text-lg" /> {error}
                                    </div>
                                )}

                                {/* Profile Photo & Quick Stats */}
                                <div className="flex flex-col lg:flex-row gap-8 items-start">
                                    <div className="w-full lg:w-1/3 flex flex-col items-center gap-4">
                                        <div className="relative group w-48 h-48">
                                            <div className="absolute inset-0 bg-gradient-to-tr from-primary to-blue-600 rounded-[2.5rem] rotate-6 group-hover:rotate-12 transition-transform opacity-10" />
                                            <div className="relative w-48 h-48 rounded-[2.5rem] border-2 border-dashed border-gray-200 bg-white group-hover:border-primary/50 transition-all flex items-center justify-center overflow-hidden">
                                                {previewUrl ? (
                                                    <img src={previewUrl} className="w-full h-full object-cover" alt="Preview" />
                                                ) : (
                                                    <div className="text-center">
                                                        <FaImage className="text-4xl text-gray-200 mx-auto mb-2" />
                                                        <p className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Enroll Photo</p>
                                                    </div>
                                                )}
                                                <input
                                                    ref={fileInputRef}
                                                    type="file"
                                                    accept="image/*"
                                                    onChange={handlePhotoChange}
                                                    className="absolute inset-0 opacity-0 cursor-pointer z-10"
                                                />
                                            </div>
                                            {previewUrl && (
                                                <div className="absolute inset-0 rounded-[2.5rem] bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm pointer-events-none">
                                                    <span className="text-white text-[10px] font-black uppercase tracking-widest">Swap Identity</span>
                                                </div>
                                            )}
                                        </div>
                                        <p className="text-[10px] text-gray-400 font-bold uppercase text-center max-w-[150px]">Recommended: Professional portrait (1:1 aspect)</p>
                                    </div>

                                    <div className="flex-1 space-y-8 w-full">
                                        {/* Multilingual Identity */}
                                        <div className="space-y-4">
                                            <div className="flex items-center gap-2 text-primary font-black text-[10px] uppercase tracking-[0.2em]">
                                                <FaInfoCircle /> Identification Core
                                            </div>
                                            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                                <div className="md:col-span-3 space-y-2">
                                                    <label className="text-[10px] font-black text-gray-400 ml-1 uppercase">Official Name (English)</label>
                                                    <input
                                                        type="text"
                                                        required
                                                        value={formData.name_en}
                                                        onChange={e => setFormData({ ...formData, name_en: e.target.value })}
                                                        className="w-full px-6 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-primary/20 focus:ring-4 focus:ring-primary/5 transition-all font-black"
                                                        placeholder="Full Legal Name"
                                                    />
                                                </div>
                                                <div className="space-y-2">
                                                    <label className="text-[10px] font-black text-gray-400 ml-1 uppercase">ሙሉ ስም (አማርኛ)</label>
                                                    <input
                                                        type="text"
                                                        value={formData.name_am}
                                                        onChange={e => setFormData({ ...formData, name_am: e.target.value })}
                                                        className="w-full px-6 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-primary/20 focus:ring-4 focus:ring-primary/5 transition-all font-amharic font-bold"
                                                        placeholder="የሙሉ ስም በፊደል..."
                                                    />
                                                </div>
                                                <div className="space-y-2">
                                                    <label className="text-[10px] font-black text-gray-400 ml-1 uppercase">Maqaa Guutuu (OR)</label>
                                                    <input
                                                        type="text"
                                                        value={formData.name_or}
                                                        onChange={e => setFormData({ ...formData, name_or: e.target.value })}
                                                        className="w-full px-6 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-primary/20 focus:ring-4 focus:ring-primary/5 transition-all font-bold"
                                                        placeholder="Maqaa Guutuu..."
                                                    />
                                                </div>
                                                <div className="space-y-2">
                                                    <label className="text-[10px] font-black text-gray-400 ml-1 uppercase">Global Category</label>
                                                    <select
                                                        value={formData.category}
                                                        onChange={e => setFormData({ ...formData, category: e.target.value })}
                                                        className="w-full px-6 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-primary/20 focus:ring-4 focus:ring-primary/5 transition-all font-black appearance-none"
                                                    >
                                                        {CATEGORIES.map(cat => <option key={cat}>{cat}</option>)}
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {/* Professional Mapping */}
                                <div className="space-y-6">
                                    <div className="flex items-center gap-2 text-primary font-black text-[10px] uppercase tracking-[0.2em]">
                                        <FaUserTie /> Professional Designation
                                    </div>
                                    <div className="bg-slate-50 p-8 rounded-[2.5rem] border border-slate-100 flex flex-col gap-6">
                                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                            <div className="space-y-2">
                                                <label className="text-[10px] font-black text-slate-400 ml-1 uppercase">Position Title (EN)</label>
                                                <input
                                                    type="text"
                                                    required
                                                    value={formData.position_en}
                                                    onChange={e => setFormData({ ...formData, position_en: e.target.value })}
                                                    className="w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all font-bold text-sm"
                                                    placeholder="Office Position..."
                                                />
                                            </div>
                                            <div className="space-y-2">
                                                <label className="text-[10px] font-black text-slate-400 ml-1 uppercase">የሥራ ኃላፊነት (AM)</label>
                                                <input
                                                    type="text"
                                                    value={formData.position_am}
                                                    onChange={e => setFormData({ ...formData, position_am: e.target.value })}
                                                    className="w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all font-amharic font-bold text-sm"
                                                    placeholder="የሥራ መደብ..."
                                                />
                                            </div>
                                            <div className="space-y-2">
                                                <label className="text-[10px] font-black text-slate-400 ml-1 uppercase">Itti Gaafatamummaa (OR)</label>
                                                <input
                                                    type="text"
                                                    value={formData.position_or}
                                                    onChange={e => setFormData({ ...formData, position_or: e.target.value })}
                                                    className="w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all font-bold text-sm"
                                                    placeholder="Gahee hojii..."
                                                />
                                            </div>
                                        </div>
                                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                            <div className="space-y-2">
                                                <label className="text-[10px] font-black text-slate-400 ml-1 uppercase">Primary Department (EN)</label>
                                                <input
                                                    type="text"
                                                    value={formData.department_en}
                                                    onChange={e => setFormData({ ...formData, department_en: e.target.value })}
                                                    className="w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all font-bold text-sm"
                                                    placeholder="Bureau / Department..."
                                                />
                                            </div>
                                            <div className="space-y-2">
                                                <label className="text-[10px] font-black text-slate-400 ml-1 uppercase">ዋና መምሪያ (AM)</label>
                                                <input
                                                    type="text"
                                                    value={formData.department_am}
                                                    onChange={e => setFormData({ ...formData, department_am: e.target.value })}
                                                    className="w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all font-amharic font-bold text-sm"
                                                    placeholder="መምሪያ..."
                                                />
                                            </div>
                                            <div className="space-y-2">
                                                <label className="text-[10px] font-black text-slate-400 ml-1 uppercase">Kutaa Hojii (OR)</label>
                                                <input
                                                    type="text"
                                                    value={formData.department_or}
                                                    onChange={e => setFormData({ ...formData, department_or: e.target.value })}
                                                    className="w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all font-bold text-sm"
                                                    placeholder="Kutaa Hojii..."
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {/* Contact & Logistics */}
                                <div className="space-y-6">
                                    <div className="flex items-center gap-2 text-primary font-black text-[10px] uppercase tracking-[0.2em]">
                                        <FaPhone /> Contact Reach & Logistics
                                    </div>
                                    <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
                                        <div className="space-y-2">
                                            <label className="text-[10px] font-black text-gray-400 ml-1 uppercase">Official Phone</label>
                                            <input
                                                type="text"
                                                value={formData.phone}
                                                onChange={e => setFormData({ ...formData, phone: e.target.value })}
                                                className="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-primary/20 focus:ring-4 focus:ring-primary/5 transition-all font-bold text-sm"
                                                placeholder="+251 ..."
                                            />
                                        </div>
                                        <div className="space-y-2">
                                            <label className="text-[10px] font-black text-gray-400 ml-1 uppercase">Government Email</label>
                                            <input
                                                type="email"
                                                value={formData.email}
                                                onChange={e => setFormData({ ...formData, email: e.target.value })}
                                                className="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-primary/20 focus:ring-4 focus:ring-primary/5 transition-all font-bold text-sm"
                                                placeholder="official@zone.gov.et"
                                            />
                                        </div>
                                        <div className="space-y-2 md:col-span-2">
                                            <label className="text-[10px] font-black text-gray-400 ml-1 uppercase">HQ / Office Location</label>
                                            <input
                                                type="text"
                                                value={formData.office_location}
                                                onChange={e => setFormData({ ...formData, office_location: e.target.value })}
                                                className="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-primary/20 focus:ring-4 focus:ring-primary/5 transition-all font-bold text-sm"
                                                placeholder="e.g. Building B, 3rd Floor, Room 302"
                                            />
                                        </div>
                                    </div>
                                    <div className="w-1/4">
                                        <label className="text-[10px] font-black text-gray-400 ml-1 uppercase flex items-center gap-2"><FaSortAmountDown /> Priority Order</label>
                                        <input
                                            type="number"
                                            value={formData.sort_order}
                                            onChange={e => setFormData({ ...formData, sort_order: e.target.value })}
                                            className="w-full px-5 py-3 mt-1 bg-gray-100 border border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 transition-all font-black text-center"
                                        />
                                    </div>
                                </div>

                                {/* Form Footer */}
                                <div className="flex items-center justify-between pt-10 border-t border-gray-100 shrink-0">
                                    <button
                                        type="button"
                                        onClick={() => setShowModal(false)}
                                        className="text-gray-400 hover:text-gray-600 font-bold uppercase tracking-widest text-[10px] flex items-center gap-2 group"
                                    >
                                        <FaTimesCircle className="group-hover:rotate-90 transition-transform" /> Discard Modifications
                                    </button>
                                    <button
                                        type="submit"
                                        disabled={uploading}
                                        className="px-12 py-5 bg-gradient-to-r from-primary to-blue-700 text-white rounded-[1.5rem] font-black uppercase tracking-widest text-[10px] shadow-2xl shadow-primary/40 hover:shadow-primary/60 transition-all active:scale-95 disabled:opacity-50 flex items-center gap-3"
                                    >
                                        {uploading ? 'Synchronizing Registry...' : editingId ? (
                                            <><FaSave /> Commit Credentials</>
                                        ) : (
                                            <><FaCheckCircle /> Authorize Enrollment</>
                                        )}
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
