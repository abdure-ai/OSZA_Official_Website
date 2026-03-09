'use client';

import { useEffect, useState, useCallback } from 'react';
import AdminLayout from '@/components/admin/AdminLayout';
import { fetchOfficeSettings, updateOfficeSettings, OfficeSettings } from '@/lib/api';
import { FaPhone, FaEnvelope, FaMapMarkerAlt, FaClock, FaFacebook, FaTwitter, FaLinkedin, FaYoutube, FaSave, FaSpinner, FaGlobe, FaShieldAlt, FaMapMarkedAlt, FaLanguage, FaRegClock, FaCity, FaHashtag, FaCheckCircle } from 'react-icons/fa';
import { motion, AnimatePresence } from 'framer-motion';

export default function AdminSettingsPage() {
    const [settings, setSettings] = useState<OfficeSettings | null>(null);
    const [loading, setLoading] = useState(true);
    const [saving, setSaving] = useState(false);

    const [formData, setFormData] = useState({
        phone: '',
        email: '',
        address: '',
        address_am: '',
        address_or: '',
        working_hours: '',
        working_hours_am: '',
        working_hours_or: '',
        map_url: '',
        facebook_url: '',
        twitter_url: '',
        linkedin_url: '',
        youtube_url: '',
    });

    const loadSettings = useCallback(async () => {
        setLoading(true);
        try {
            const data = await fetchOfficeSettings();
            if (data) {
                setSettings(data);
                setFormData({
                    phone: data.phone || '',
                    email: data.email || '',
                    address: data.address || '',
                    address_am: data.address_am || '',
                    address_or: data.address_or || '',
                    working_hours: data.working_hours || '',
                    working_hours_am: data.working_hours_am || '',
                    working_hours_or: data.working_hours_or || '',
                    map_url: data.map_url || '',
                    facebook_url: data.facebook_url || '',
                    twitter_url: data.twitter_url || '',
                    linkedin_url: data.linkedin_url || '',
                    youtube_url: data.youtube_url || '',
                });
            }
        } catch (err) {
            console.error('Failed to load settings:', err);
        } finally {
            setLoading(false);
        }
    }, []);

    useEffect(() => {
        loadSettings();
    }, [loadSettings]);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setSaving(true);
        try {
            const token = localStorage.getItem('adminToken') || '';
            await updateOfficeSettings(formData, token);
            await loadSettings();
        } catch (err: any) {
            alert(err.message);
        } finally {
            setSaving(false);
        }
    };

    return (
        <AdminLayout>
            <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10">
                <motion.div
                    initial={{ opacity: 0, x: -20 }}
                    animate={{ opacity: 1, x: 0 }}
                >
                    <h1 className="text-4xl font-black text-gray-900 tracking-tight flex items-center gap-4">
                        <span className="p-3 bg-primary/10 rounded-2xl text-primary"><FaShieldAlt /></span>
                        Core Infrastructure
                    </h1>
                    <p className="text-gray-500 mt-2 font-medium italic">Synchronize global office credentials and communication channels site-wide.</p>
                </motion.div>
            </div>

            {loading ? (
                <div className="p-20 flex flex-col items-center justify-center text-gray-400">
                    <FaSpinner className="animate-spin text-5xl text-primary mb-4" />
                    <p className="font-bold uppercase tracking-widest text-[10px]">Accessing Secure Registry...</p>
                </div>
            ) : (
                <form onSubmit={handleSubmit} className="space-y-10">
                    <div className="grid grid-cols-1 xl:grid-cols-2 gap-10">
                        {/* Primary Contact Info */}
                        <motion.div
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            className="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-10 space-y-10"
                        >
                            <h3 className="text-sm font-black text-primary uppercase tracking-[0.3em] flex items-center gap-3">
                                <FaHashtag /> Terminal Access Protocols
                            </h3>

                            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div className="space-y-2">
                                    <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Official Voice Line</label>
                                    <div className="relative">
                                        <FaPhone className="absolute left-4 top-1/2 -translate-y-1/2 text-primary/40" />
                                        <input
                                            type="text"
                                            className="w-full pl-12 pr-4 py-4 bg-gray-50 border-2 border-transparent rounded-[1.25rem] focus:bg-white focus:border-primary/30 focus:ring-4 focus:ring-primary/10 transition-all font-bold text-gray-900 outline-none"
                                            value={formData.phone}
                                            onChange={e => setFormData({ ...formData, phone: e.target.value })}
                                        />
                                    </div>
                                </div>
                                <div className="space-y-2">
                                    <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Secure Email Channel</label>
                                    <div className="relative">
                                        <FaEnvelope className="absolute left-4 top-1/2 -translate-y-1/2 text-primary/40" />
                                        <input
                                            type="email"
                                            className="w-full pl-12 pr-4 py-4 bg-gray-50 border-2 border-transparent rounded-[1.25rem] focus:bg-white focus:border-primary/30 focus:ring-4 focus:ring-primary/10 transition-all font-bold text-gray-900 outline-none"
                                            value={formData.email}
                                            onChange={e => setFormData({ ...formData, email: e.target.value })}
                                        />
                                    </div>
                                </div>
                            </div>

                            <div className="space-y-6">
                                <div className="flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                    <FaMapMarkerAlt className="text-primary" /> Physical Headquarters
                                </div>
                                <div className="grid grid-cols-1 gap-6">
                                    <textarea
                                        placeholder="International Address (English)"
                                        className="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-[1.25rem] focus:bg-white focus:border-primary/30 outline-none transition-all font-medium text-gray-800"
                                        rows={2}
                                        value={formData.address}
                                        onChange={e => setFormData({ ...formData, address: e.target.value })}
                                    />
                                    <textarea
                                        placeholder="አድራሻ (Amharic)"
                                        className="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-[1.25rem] focus:bg-white focus:border-primary/30 outline-none transition-all font-medium text-gray-800"
                                        rows={2}
                                        value={formData.address_am}
                                        onChange={e => setFormData({ ...formData, address_am: e.target.value })}
                                    />
                                    <textarea
                                        placeholder="Teessoo (Oromo)"
                                        className="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-[1.25rem] focus:bg-white focus:border-primary/30 outline-none transition-all font-medium text-gray-800"
                                        rows={2}
                                        value={formData.address_or}
                                        onChange={e => setFormData({ ...formData, address_or: e.target.value })}
                                    />
                                </div>
                            </div>
                        </motion.div>

                        {/* Presence & Map */}
                        <motion.div
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ delay: 0.1 }}
                            className="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-10 space-y-10"
                        >
                            <h3 className="text-sm font-black text-primary uppercase tracking-[0.3em] flex items-center gap-3">
                                <FaClock /> Operational Intervals
                            </h3>

                            <div className="grid grid-cols-1 gap-6">
                                <div className="space-y-2">
                                    <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Standard Window (EN)</label>
                                    <div className="relative">
                                        <FaRegClock className="absolute left-4 top-1/2 -translate-y-1/2 text-primary/40" />
                                        <input
                                            type="text"
                                            className="w-full pl-12 pr-4 py-4 bg-gray-50 border-2 border-transparent rounded-[1.25rem] focus:bg-white focus:border-primary/30 outline-none transition-all font-bold text-gray-900"
                                            value={formData.working_hours}
                                            onChange={e => setFormData({ ...formData, working_hours: e.target.value })}
                                        />
                                    </div>
                                </div>
                                <div className="space-y-2">
                                    <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">የስራ ሰዓት (AM)</label>
                                    <input
                                        type="text"
                                        className="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-[1.25rem] focus:bg-white focus:border-primary/30 outline-none transition-all font-bold text-gray-900"
                                        value={formData.working_hours_am}
                                        onChange={e => setFormData({ ...formData, working_hours_am: e.target.value })}
                                    />
                                </div>
                                <div className="space-y-2">
                                    <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Sa'aatii Hojii (OR)</label>
                                    <input
                                        type="text"
                                        className="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-[1.25rem] focus:bg-white focus:border-primary/30 outline-none transition-all font-bold text-gray-900"
                                        value={formData.working_hours_or}
                                        onChange={e => setFormData({ ...formData, working_hours_or: e.target.value })}
                                    />
                                </div>
                            </div>

                            <div className="space-y-4">
                                <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                                    <FaMapMarkedAlt className="text-primary" /> Geospatial Coordinate Embed (URL)
                                </label>
                                <input
                                    type="text"
                                    placeholder="Google Maps IFRAME Source Terminal"
                                    className="w-full px-6 py-4 bg-slate-50 border-2 border-transparent rounded-[1.25rem] focus:bg-white focus:border-primary/30 outline-none transition-all font-medium text-xs font-mono"
                                    value={formData.map_url}
                                    onChange={e => setFormData({ ...formData, map_url: e.target.value })}
                                />
                            </div>
                        </motion.div>
                    </div>

                    {/* Social Hub */}
                    <motion.section
                        initial={{ opacity: 0, y: 20 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ delay: 0.2 }}
                        className="bg-white rounded-[3rem] border border-gray-100 shadow-sm p-12"
                    >
                        <h3 className="text-sm font-black text-primary uppercase tracking-[0.3em] mb-12 flex items-center gap-3">
                            <FaGlobe /> External Projection Channels
                        </h3>

                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                            {[
                                { id: 'facebook_url', icon: <FaFacebook />, color: 'text-[#1877F2]', bcolor: 'border-[#1877F2]/20', label: 'Meta Protocol' },
                                { id: 'twitter_url', icon: <FaTwitter />, color: 'text-[#1DA1F2]', bcolor: 'border-[#1DA1F2]/20', label: 'X Broadcast' },
                                { id: 'linkedin_url', icon: <FaLinkedin />, color: 'text-[#0A66C2]', bcolor: 'border-[#0A66C2]/20', label: 'Professional Grid' },
                                { id: 'youtube_url', icon: <FaYoutube />, color: 'text-[#FF0000]', bcolor: 'border-[#FF0000]/20', label: 'Visual Archive' },
                            ].map((social) => (
                                <div key={social.id} className={`p-6 bg-slate-50/50 rounded-[2rem] border-2 ${social.bcolor} flex flex-col gap-4 group hover:bg-white hover:shadow-xl transition-all`}>
                                    <div className={`w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-xl shadow-sm ${social.color} transition-transform group-hover:scale-110`}>
                                        {social.icon}
                                    </div>
                                    <div className="space-y-1">
                                        <p className="text-[9px] font-black text-gray-400 uppercase tracking-widest">{social.label}</p>
                                        <input
                                            type="text"
                                            className="w-full bg-transparent border-none p-0 focus:ring-0 font-bold text-gray-900 text-xs placeholder:text-gray-300"
                                            placeholder="URL Source..."
                                            value={(formData as any)[social.id]}
                                            onChange={e => setFormData({ ...formData, [social.id]: e.target.value })}
                                        />
                                    </div>
                                </div>
                            ))}
                        </div>

                        <div className="mt-16 pt-12 border-t border-gray-50 flex flex-col md:flex-row items-center justify-between gap-8">
                            <div className="flex items-center gap-4 py-3 px-6 bg-emerald-50 rounded-2xl text-emerald-600">
                                <FaCheckCircle className="animate-bounce" />
                                <span className="text-[10px] font-black uppercase tracking-widest">Global Synchronization Ready</span>
                            </div>

                            <button
                                type="submit"
                                disabled={saving}
                                className="w-full md:w-auto bg-gradient-to-r from-primary to-blue-700 text-white px-16 py-5 rounded-[1.5rem] font-black uppercase tracking-[0.2em] text-[10px] shadow-2xl shadow-primary/30 hover:shadow-primary/50 transition-all active:scale-95 flex items-center justify-center gap-4 group disabled:opacity-50"
                            >
                                {saving ? <FaSpinner className="animate-spin" /> : <FaSave className="group-hover:rotate-12 transition-transform" />}
                                {saving ? 'Synchronizing Infostructure...' : 'Commit Settings to Core'}
                            </button>
                        </div>
                    </motion.section>
                </form>
            )}
        </AdminLayout>
    );
}
