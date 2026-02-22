'use client';

import { useEffect, useState } from 'react';
import AdminLayout from '@/components/admin/AdminLayout';
import { fetchAdminMessage, updateAdminMessage, AdminMessage, getFileUrl } from '@/lib/api';
import { FaCamera, FaSpinner, FaSave } from 'react-icons/fa';

export default function AdminMessagePage() {
    const [msg, setMsg] = useState<AdminMessage | null>(null);
    const [loading, setLoading] = useState(true);
    const [saving, setSaving] = useState(false);
    const [token, setToken] = useState('');
    const [photo, setPhoto] = useState<File | null>(null);
    const [previewUrl, setPreviewUrl] = useState<string | null>(null);

    const [formData, setFormData] = useState({
        name: '',
        title_position: '',
        message_en: '',
        is_active: true,
    });

    useEffect(() => {
        const t = localStorage.getItem('adminToken') || '';
        setToken(t);
        loadMessage();
    }, []);

    async function loadMessage() {
        setLoading(true);
        const data = await fetchAdminMessage();
        if (data) {
            setMsg(data);
            setFormData({
                name: data.name,
                title_position: data.title_position,
                message_en: data.message_en,
                is_active: !!data.is_active,
            });
        }
        setLoading(false);
    }

    const handlePhotoChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (!file) return;
        setPhoto(file);
        const reader = new FileReader();
        reader.onload = (ev) => setPreviewUrl(ev.target?.result as string);
        reader.readAsDataURL(file);
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setSaving(true);
        const fd = new FormData();
        fd.append('name', formData.name);
        fd.append('title_position', formData.title_position);
        fd.append('message_en', formData.message_en);
        fd.append('is_active', formData.is_active ? '1' : '0');
        if (photo) fd.append('photo', photo);
        try {
            await updateAdminMessage(fd, token);
            alert('Message updated successfully!');
            loadMessage();
        } catch (err: any) {
            alert(err.message);
        } finally {
            setSaving(false);
        }
    };

    const currentPhoto = previewUrl || (msg?.photo_url ? getFileUrl(msg.photo_url) : null);

    return (
        <AdminLayout>
            <div className="mb-6">
                <h1 className="text-2xl font-bold text-gray-800">Administrator's Message</h1>
                <p className="text-gray-500 text-sm">Manage the message displayed on the homepage.</p>
            </div>

            {loading ? (
                <div className="p-20 text-center text-gray-400">
                    <FaSpinner className="animate-spin text-4xl mx-auto mb-4" />
                </div>
            ) : (
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {/* Preview */}
                    <div className="lg:col-span-1">
                        <div className="bg-white rounded-xl border border-gray-100 shadow-sm p-6 text-center sticky top-28">
                            <h3 className="text-sm font-bold text-gray-500 uppercase tracking-wide mb-6">Live Preview</h3>
                            <div className="relative inline-block">
                                {currentPhoto ? (
                                    <img src={currentPhoto} alt="Admin" className="w-40 h-40 rounded-2xl object-cover mx-auto shadow-lg" />
                                ) : (
                                    <div className="w-40 h-40 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto">
                                        <FaCamera className="text-4xl text-gray-300" />
                                    </div>
                                )}
                                <label className="absolute -bottom-3 -right-3 bg-primary text-white rounded-full p-2 cursor-pointer shadow-lg hover:bg-opacity-90 transition">
                                    <FaCamera className="text-sm" />
                                    <input type="file" accept="image/*" className="hidden" onChange={handlePhotoChange} />
                                </label>
                            </div>
                            <div className="mt-6">
                                <p className="font-bold text-gray-900">{formData.name || 'Name'}</p>
                                <p className="text-sm text-gray-500 mt-1">{formData.title_position || 'Position'}</p>
                            </div>
                            <p className="mt-4 text-sm text-gray-600 italic line-clamp-5">{formData.message_en || 'Message preview...'}</p>
                        </div>
                    </div>

                    {/* Form */}
                    <div className="lg:col-span-2">
                        <form onSubmit={handleSubmit} className="bg-white rounded-xl border border-gray-100 shadow-sm p-8 space-y-6">
                            <div className="grid grid-cols-2 gap-6">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Administrator Name</label>
                                    <input
                                        type="text"
                                        required
                                        className="w-full p-2.5 border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition"
                                        value={formData.name}
                                        onChange={e => setFormData({ ...formData, name: e.target.value })}
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Title / Position</label>
                                    <input
                                        type="text"
                                        required
                                        className="w-full p-2.5 border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition"
                                        value={formData.title_position}
                                        onChange={e => setFormData({ ...formData, title_position: e.target.value })}
                                    />
                                </div>
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Message (English)</label>
                                <textarea
                                    required
                                    className="w-full p-2.5 border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition h-52"
                                    value={formData.message_en}
                                    onChange={e => setFormData({ ...formData, message_en: e.target.value })}
                                />
                            </div>
                            <div className="flex items-center gap-2">
                                <input
                                    type="checkbox"
                                    id="is_active"
                                    checked={formData.is_active}
                                    onChange={e => setFormData({ ...formData, is_active: e.target.checked })}
                                />
                                <label htmlFor="is_active" className="text-sm font-medium text-gray-700">Display on public homepage</label>
                            </div>
                            <div className="pt-4 border-t">
                                <button
                                    type="submit"
                                    disabled={saving}
                                    className="bg-primary text-white px-8 py-3 rounded-lg font-bold flex items-center gap-2 hover:bg-opacity-90 transition disabled:opacity-60"
                                >
                                    {saving ? <FaSpinner className="animate-spin" /> : <FaSave />}
                                    {saving ? 'Saving...' : 'Save Changes'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}
