'use client';

import AdminLayout from '@/components/admin/AdminLayout';
import { useState, useEffect, useCallback, useRef } from 'react';
import {
    fetchDirectory, createContact, updateContact, deleteContact,
    DirectoryContact, getFileUrl
} from '@/lib/api';
import { FaUserPlus, FaTrash, FaEdit, FaPhone, FaEnvelope, FaMapMarkerAlt, FaImage, FaSave, FaTimes } from 'react-icons/fa';

const CATEGORIES = ['General', 'Leadership', 'Department', 'Woreda Head', 'Security', 'Health', 'Education', 'Finance'];

export default function AdminDirectoryPage() {
    const [contacts, setContacts] = useState<DirectoryContact[]>([]);
    const [loading, setLoading] = useState(true);
    const [uploading, setUploading] = useState(false);
    const [error, setError] = useState('');
    const [success, setSuccess] = useState('');

    // Editing state
    const [editingId, setEditingId] = useState<number | null>(null);

    // Form state
    const [formData, setFormData] = useState({
        name_en: '',
        position_en: '',
        department_en: '',
        phone: '',
        email: '',
        office_location: '',
        category: 'General',
        sort_order: '0'
    });
    const [photo, setPhoto] = useState<File | null>(null);
    const fileInputRef = useRef<HTMLInputElement>(null);

    const loadContacts = useCallback(async () => {
        setLoading(true);
        const data = await fetchDirectory();
        setContacts(data);
        setLoading(false);
    }, []);

    useEffect(() => { loadContacts(); }, [loadContacts]);

    const handleEdit = (contact: DirectoryContact) => {
        setEditingId(contact.id);
        setFormData({
            name_en: contact.name_en,
            position_en: contact.position_en,
            department_en: contact.department_en || '',
            phone: contact.phone || '',
            email: contact.email || '',
            office_location: contact.office_location || '',
            category: contact.category,
            sort_order: String(contact.sort_order)
        });
        setPhoto(null);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    const resetForm = () => {
        setEditingId(null);
        setFormData({
            name_en: '',
            position_en: '',
            department_en: '',
            phone: '',
            email: '',
            office_location: '',
            category: 'General',
            sort_order: '0'
        });
        setPhoto(null);
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
            if (photo) data.append('photo', photo);

            const token = localStorage.getItem('adminToken') || '';

            if (editingId) {
                await updateContact(editingId, data, token);
                setSuccess('Contact updated successfully!');
            } else {
                await createContact(data, token);
                setSuccess('Contact added successfully!');
            }

            resetForm();
            loadContacts();
        } catch (err: any) {
            setError(err.message || 'Operation failed');
        } finally {
            setUploading(false);
        }
    };

    const handleDelete = async (id: number) => {
        if (!confirm('Delete this contact?')) return;
        try {
            const token = localStorage.getItem('adminToken') || '';
            await deleteContact(id, token);
            setContacts(prev => prev.filter(c => c.id !== id));
        } catch (err: any) {
            alert(err.message || 'Delete failed');
        }
    };

    return (
        <AdminLayout>
            <div className="mb-6">
                <h1 className="text-2xl font-bold text-gray-800">Contact Directory Management</h1>
                <p className="text-gray-500 text-sm mt-1">Manage official contact information for leadership and departments.</p>
            </div>

            {/* Form Section */}
            <div className="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                <h2 className="font-bold text-lg text-gray-800 mb-4 flex items-center gap-2">
                    {editingId ? <><FaEdit className="text-blue-500" /> Edit Contact</> : <><FaUserPlus className="text-green-500" /> Add New Contact</>}
                </h2>
                <form onSubmit={handleSubmit} className="space-y-4">
                    {error && <p className="text-red-600 bg-red-50 border border-red-200 p-3 rounded-lg text-sm">{error}</p>}
                    {success && <p className="text-green-700 bg-green-50 border border-green-200 p-3 rounded-lg text-sm">{success}</p>}

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Full Name (English)</label>
                            <input
                                type="text"
                                required
                                value={formData.name_en}
                                onChange={e => setFormData({ ...formData, name_en: e.target.value })}
                                className="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/20 outline-none"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Position / Title</label>
                            <input
                                type="text"
                                required
                                value={formData.position_en}
                                onChange={e => setFormData({ ...formData, position_en: e.target.value })}
                                className="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/20 outline-none"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Department</label>
                            <input
                                type="text"
                                value={formData.department_en}
                                onChange={e => setFormData({ ...formData, department_en: e.target.value })}
                                className="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/20 outline-none"
                            />
                        </div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input
                                type="text"
                                value={formData.phone}
                                onChange={e => setFormData({ ...formData, phone: e.target.value })}
                                placeholder="+251 ..."
                                className="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/20 outline-none"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input
                                type="email"
                                value={formData.email}
                                onChange={e => setFormData({ ...formData, email: e.target.value })}
                                className="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/20 outline-none"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Office Location</label>
                            <input
                                type="text"
                                value={formData.office_location}
                                onChange={e => setFormData({ ...formData, office_location: e.target.value })}
                                className="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/20 outline-none"
                            />
                        </div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                            <input
                                type="number"
                                value={formData.sort_order}
                                onChange={e => setFormData({ ...formData, sort_order: e.target.value })}
                                className="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/20 outline-none"
                            />
                        </div>
                        <div className="flex items-end gap-3">
                            <div className="flex-1">
                                <label className="block text-sm font-medium text-gray-700 mb-1">Profile Photo</label>
                                <input
                                    ref={fileInputRef}
                                    type="file"
                                    accept="image/*"
                                    onChange={e => setPhoto(e.target.files?.[0] || null)}
                                    className="w-full border rounded-lg px-3 py-2 text-sm text-gray-600 file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:bg-primary/10 file:text-primary hover:file:bg-primary/20"
                                />
                            </div>
                            {photo && (
                                <img src={URL.createObjectURL(photo)} className="w-10 h-10 rounded-full object-cover border" alt="Preview" />
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
                            <FaSave /> {uploading ? 'Processing...' : editingId ? 'Update Contact' : 'Save Contact'}
                        </button>
                    </div>
                </form>
            </div>

            {/* List Section */}
            <div className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table className="w-full text-left">
                    <thead className="bg-gray-50 border-b">
                        <tr>
                            <th className="px-6 py-4 font-semibold text-gray-600 text-sm">Official</th>
                            <th className="px-6 py-4 font-semibold text-gray-600 text-sm">Position & Dept</th>
                            <th className="px-6 py-4 font-semibold text-gray-600 text-sm">Contact Info</th>
                            <th className="px-6 py-4 font-semibold text-gray-600 text-sm">Category</th>
                            <th className="px-6 py-4 font-semibold text-gray-600 text-sm text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-gray-100">
                        {loading ? (
                            <tr><td colSpan={5} className="px-6 py-8 text-center text-gray-400">Loading directory...</td></tr>
                        ) : contacts.length === 0 ? (
                            <tr><td colSpan={5} className="px-6 py-8 text-center text-gray-400">No contacts found.</td></tr>
                        ) : contacts.map(contact => (
                            <tr key={contact.id} className="hover:bg-gray-50 transition">
                                <td className="px-6 py-4">
                                    <div className="flex items-center gap-3">
                                        {contact.photo_url ? (
                                            <img src={getFileUrl(contact.photo_url)} className="w-10 h-10 rounded-full object-cover shadow-sm" alt="" />
                                        ) : (
                                            <div className="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 uppercase font-bold text-xs">
                                                {contact.name_en.substring(0, 2)}
                                            </div>
                                        )}
                                        <span className="font-medium text-gray-900">{contact.name_en}</span>
                                    </div>
                                </td>
                                <td className="px-6 py-4">
                                    <span className="text-sm font-medium text-gray-800 block">{contact.position_en}</span>
                                    <span className="text-xs text-gray-400">{contact.department_en || '-'}</span>
                                </td>
                                <td className="px-6 py-4 space-y-1">
                                    {contact.phone && <div className="text-xs text-gray-600 flex items-center gap-2"><FaPhone size={10} className="text-primary" /> {contact.phone}</div>}
                                    {contact.email && <div className="text-xs text-gray-600 flex items-center gap-2"><FaEnvelope size={10} className="text-primary" /> {contact.email}</div>}
                                </td>
                                <td className="px-6 py-4">
                                    <span className="bg-gray-100 text-gray-700 px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider">
                                        {contact.category}
                                    </span>
                                </td>
                                <td className="px-6 py-4 text-right">
                                    <div className="flex justify-end gap-2">
                                        <button
                                            onClick={() => handleEdit(contact)}
                                            className="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                            title="Edit"
                                        >
                                            <FaEdit />
                                        </button>
                                        <button
                                            onClick={() => handleDelete(contact.id)}
                                            className="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                            title="Delete"
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
