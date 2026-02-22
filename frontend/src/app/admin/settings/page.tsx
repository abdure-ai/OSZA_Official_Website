'use client';

import { useEffect, useState } from 'react';
import AdminLayout from '@/components/admin/AdminLayout';
import { fetchOfficeSettings, updateOfficeSettings, OfficeSettings } from '@/lib/api';
import { FaPhone, FaEnvelope, FaMapMarkerAlt, FaClock, FaFacebook, FaTwitter, FaLinkedin, FaYoutube, FaSave, FaSpinner, FaGlobe } from 'react-icons/fa';

export default function AdminSettingsPage() {
    const [settings, setSettings] = useState<OfficeSettings | null>(null);
    const [loading, setLoading] = useState(true);
    const [saving, setSaving] = useState(false);
    const [token, setToken] = useState('');

    const [formData, setFormData] = useState({
        phone: '',
        email: '',
        address: '',
        working_hours: '',
        map_url: '',
        facebook_url: '',
        twitter_url: '',
        linkedin_url: '',
        youtube_url: '',
    });

    useEffect(() => {
        const t = localStorage.getItem('adminToken') || '';
        setToken(t);
        loadSettings();
    }, []);

    async function loadSettings() {
        setLoading(true);
        const data = await fetchOfficeSettings();
        if (data) {
            setSettings(data);
            setFormData({
                phone: data.phone || '',
                email: data.email || '',
                address: data.address || '',
                working_hours: data.working_hours || '',
                map_url: data.map_url || '',
                facebook_url: data.facebook_url || '',
                twitter_url: data.twitter_url || '',
                linkedin_url: data.linkedin_url || '',
                youtube_url: data.youtube_url || '',
            });
        }
        setLoading(false);
    }

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setSaving(true);
        try {
            await updateOfficeSettings(formData, token);
            alert('Settings updated successfully!');
            loadSettings();
        } catch (err: any) {
            alert(err.message);
        } finally {
            setSaving(false);
        }
    };

    return (
        <AdminLayout>
            <div className="mb-6">
                <h1 className="text-2xl font-bold text-gray-800">Global Office Settings</h1>
                <p className="text-gray-500 text-sm">Manage official contact information and social media links displayed site-wide.</p>
            </div>

            {loading ? (
                <div className="p-20 text-center text-gray-400">
                    <FaSpinner className="animate-spin text-4xl mx-auto mb-4" />
                </div>
            ) : (
                <form onSubmit={handleSubmit} className="space-y-8">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        {/* Primary Contact Info */}
                        <div className="bg-white rounded-xl border border-gray-100 shadow-sm p-8 space-y-6">
                            <h3 className="text-lg font-bold text-gray-800 flex items-center gap-2 border-b pb-4 mb-6">
                                <FaPhone className="text-primary" />
                                Basic Contact Details
                            </h3>

                            <div className="space-y-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Official Phone</label>
                                    <input
                                        type="text"
                                        className="w-full p-2.5 border rounded-lg focus:ring-2 focus:ring-primary outline-none"
                                        value={formData.phone}
                                        onChange={e => setFormData({ ...formData, phone: e.target.value })}
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Official Email</label>
                                    <input
                                        type="email"
                                        className="w-full p-2.5 border rounded-lg focus:ring-2 focus:ring-primary outline-none"
                                        value={formData.email}
                                        onChange={e => setFormData({ ...formData, email: e.target.value })}
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Physical Address</label>
                                    <textarea
                                        rows={3}
                                        className="w-full p-2.5 border rounded-lg focus:ring-2 focus:ring-primary outline-none"
                                        value={formData.address}
                                        onChange={e => setFormData({ ...formData, address: e.target.value })}
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Working Hours</label>
                                    <input
                                        type="text"
                                        placeholder="e.g. Mon - Fri: 8:30 AM - 5:30 PM"
                                        className="w-full p-2.5 border rounded-lg focus:ring-2 focus:ring-primary outline-none"
                                        value={formData.working_hours}
                                        onChange={e => setFormData({ ...formData, working_hours: e.target.value })}
                                    />
                                </div>
                            </div>
                        </div>

                        {/* Presence & Map */}
                        <div className="bg-white rounded-xl border border-gray-100 shadow-sm p-8 space-y-6">
                            <h3 className="text-lg font-bold text-gray-800 flex items-center gap-2 border-b pb-4 mb-6">
                                <FaGlobe className="text-primary" />
                                Online Presence
                            </h3>

                            <div className="space-y-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Google Maps Embed URL</label>
                                    <input
                                        type="text"
                                        placeholder="Paste the iframe src URL"
                                        className="w-full p-2.5 border rounded-lg focus:ring-2 focus:ring-primary outline-none"
                                        value={formData.map_url}
                                        onChange={e => setFormData({ ...formData, map_url: e.target.value })}
                                    />
                                </div>

                                <div className="grid grid-cols-2 gap-4">
                                    <div className="space-y-1">
                                        <label className="flex items-center gap-2 text-sm font-medium text-[#1877F2]">
                                            <FaFacebook /> Facebook
                                        </label>
                                        <input
                                            type="text"
                                            className="w-full p-2.5 border rounded-lg focus:ring-2 focus:ring-primary outline-none text-xs"
                                            value={formData.facebook_url}
                                            onChange={e => setFormData({ ...formData, facebook_url: e.target.value })}
                                        />
                                    </div>
                                    <div className="space-y-1">
                                        <label className="flex items-center gap-2 text-sm font-medium text-[#1DA1F2]">
                                            <FaTwitter /> Twitter / X
                                        </label>
                                        <input
                                            type="text"
                                            className="w-full p-2.5 border rounded-lg focus:ring-2 focus:ring-primary outline-none text-xs"
                                            value={formData.twitter_url}
                                            onChange={e => setFormData({ ...formData, twitter_url: e.target.value })}
                                        />
                                    </div>
                                    <div className="space-y-1">
                                        <label className="flex items-center gap-2 text-sm font-medium text-[#0A66C2]">
                                            <FaLinkedin /> LinkedIn
                                        </label>
                                        <input
                                            type="text"
                                            className="w-full p-2.5 border rounded-lg focus:ring-2 focus:ring-primary outline-none text-xs"
                                            value={formData.linkedin_url}
                                            onChange={e => setFormData({ ...formData, linkedin_url: e.target.value })}
                                        />
                                    </div>
                                    <div className="space-y-1">
                                        <label className="flex items-center gap-2 text-sm font-medium text-[#FF0000]">
                                            <FaYoutube /> YouTube
                                        </label>
                                        <input
                                            type="text"
                                            className="w-full p-2.5 border rounded-lg focus:ring-2 focus:ring-primary outline-none text-xs"
                                            value={formData.youtube_url}
                                            onChange={e => setFormData({ ...formData, youtube_url: e.target.value })}
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="flex justify-end pt-6 border-t font-inter">
                        <button
                            type="submit"
                            disabled={saving}
                            className="bg-primary text-white px-10 py-3 rounded-xl font-bold flex items-center gap-2 hover:shadow-lg transition disabled:opacity-50"
                        >
                            {saving ? <FaSpinner className="animate-spin" /> : <FaSave />}
                            {saving ? 'Saving...' : 'Save Office Settings'}
                        </button>
                    </div>
                </form>
            )}
        </AdminLayout>
    );
}
