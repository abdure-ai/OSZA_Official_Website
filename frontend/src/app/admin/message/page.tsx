'use client';

import { useEffect, useState, useCallback } from 'react';
import AdminLayout from '@/components/admin/AdminLayout';
import { fetchAdminMessage, updateAdminMessage, AdminMessage, getFileUrl } from '@/lib/api';
import { FaCamera, FaSpinner, FaSave, FaCheckCircle, FaGlobe, FaLanguage, FaPenNib, FaUserTie, FaEye, FaFileUpload } from 'react-icons/fa';
import { motion, AnimatePresence } from 'framer-motion';

export default function AdminMessagePage() {
    const [msg, setMsg] = useState<AdminMessage | null>(null);
    const [loading, setLoading] = useState(true);
    const [saving, setSaving] = useState(false);
    const [photo, setPhoto] = useState<File | null>(null);
    const [previewUrl, setPreviewUrl] = useState<string | null>(null);

    const [formData, setFormData] = useState({
        name: '',
        title_position: '',
        message_en: '',
        message_am: '',
        message_or: '',
        is_active: true,
    });

    const loadMessage = useCallback(async () => {
        setLoading(true);
        try {
            const data = await fetchAdminMessage();
            if (data) {
                setMsg(data);
                setFormData({
                    name: data.name || '',
                    title_position: data.title_position || '',
                    message_en: data.message_en || '',
                    message_am: data.message_am || '',
                    message_or: data.message_or || '',
                    is_active: !!data.is_active,
                });
            }
        } catch (err) {
            console.error('Failed to load admin message:', err);
        } finally {
            setLoading(false);
        }
    }, []);

    useEffect(() => {
        loadMessage();
    }, [loadMessage]);

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
        fd.append('message_am', formData.message_am);
        fd.append('message_or', formData.message_or);
        fd.append('is_active', formData.is_active ? '1' : '0');
        if (photo) fd.append('photo', photo);

        try {
            const token = localStorage.getItem('adminToken') || '';
            await updateAdminMessage(fd, token);
            await loadMessage();
        } catch (err: any) {
            alert(err.message);
        } finally {
            setSaving(false);
        }
    };

    const currentPhoto = previewUrl || (msg?.photo_url ? getFileUrl(msg.photo_url) : null);

    return (
        <AdminLayout>
            <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10">
                <motion.div
                    initial={{ opacity: 0, x: -20 }}
                    animate={{ opacity: 1, x: 0 }}
                >
                    <h1 className="text-4xl font-black text-gray-900 tracking-tight flex items-center gap-4">
                        <span className="p-3 bg-primary/10 rounded-2xl text-primary"><FaUserTie /></span>
                        Global Addressment
                    </h1>
                    <p className="text-gray-500 mt-2 font-medium italic">Configure the official executive greeting for the platform summit.</p>
                </motion.div>
            </div>

            {loading ? (
                <div className="p-20 flex flex-col items-center justify-center text-gray-400">
                    <FaSpinner className="animate-spin text-5xl text-primary mb-4" />
                    <p className="font-bold uppercase tracking-widest text-[10px]">Synchronizing Executive Data...</p>
                </div>
            ) : (
                <div className="grid grid-cols-1 xl:grid-cols-12 gap-10">
                    {/* Preview Sidebar */}
                    <motion.div
                        initial={{ opacity: 0, scale: 0.95 }}
                        animate={{ opacity: 1, scale: 1 }}
                        className="xl:col-span-4"
                    >
                        <div className="bg-white rounded-[2.5rem] border border-gray-100 shadow-2xl p-8 sticky top-28 overflow-hidden">
                            <div className="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-bl-[5rem] -mr-10 -mt-10" />

                            <h3 className="text-xs font-black text-primary uppercase tracking-[0.3em] mb-8 flex items-center gap-2">
                                <FaEye className="animate-pulse" /> Live Transmission Preview
                            </h3>

                            <div className="relative z-10">
                                <div className="group relative w-56 h-56 mx-auto mb-8">
                                    <div className="absolute inset-0 bg-primary/10 rounded-[2.5rem] rotate-6 group-hover:rotate-12 transition-transform duration-500" />
                                    <div className="absolute inset-0 bg-blue-600/10 rounded-[2.5rem] -rotate-3 group-hover:-rotate-6 transition-transform duration-500" />

                                    <div className="relative w-full h-full rounded-[2.25rem] overflow-hidden border-4 border-white shadow-xl bg-gray-50">
                                        {currentPhoto ? (
                                            <img src={currentPhoto} alt="Executive" className="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110" />
                                        ) : (
                                            <div className="w-full h-full flex items-center justify-center text-gray-200">
                                                <FaUserTie className="text-6xl" />
                                            </div>
                                        )}

                                        <label className="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer backdrop-blur-sm">
                                            <div className="flex flex-col items-center text-white">
                                                <FaFileUpload className="text-3xl mb-2" />
                                                <span className="text-[10px] font-black uppercase tracking-widest">Update Photo</span>
                                            </div>
                                            <input type="file" accept="image/*" className="hidden" onChange={handlePhotoChange} />
                                        </label>
                                    </div>
                                </div>

                                <div className="text-center space-y-2">
                                    <h4 className="text-2xl font-black text-gray-900 tracking-tight">{formData.name || 'Executive Name'}</h4>
                                    <p className="text-primary font-bold text-xs uppercase tracking-widest">{formData.title_position || 'Executive Position'}</p>
                                </div>

                                <div className="mt-8 pt-8 border-t border-gray-50">
                                    <div className="relative bg-gray-50 rounded-2xl p-6 italic text-gray-600 text-sm leading-relaxed">
                                        <span className="absolute -top-3 left-6 text-4xl text-primary/20 font-serif">"</span>
                                        <p className="line-clamp-6">{formData.message_en || 'Transmission content pending...'}</p>
                                        <span className="absolute -bottom-6 right-6 text-4xl text-primary/20 font-serif">"</span>
                                    </div>
                                </div>

                                <div className={`mt-8 p-4 rounded-xl text-center font-black text-[10px] uppercase tracking-widest transition-colors ${formData.is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'
                                    }`}>
                                    {formData.is_active ? 'Status: Active Broadcast' : 'Status: Transmission Offline'}
                                </div>
                            </div>
                        </div>
                    </motion.div>

                    {/* Editor Form */}
                    <motion.div
                        initial={{ opacity: 0, x: 20 }}
                        animate={{ opacity: 1, x: 0 }}
                        className="xl:col-span-8"
                    >
                        <form onSubmit={handleSubmit} className="bg-white rounded-[3rem] border border-gray-100 shadow-sm p-12 space-y-12">
                            <section className="space-y-8">
                                <div className="flex items-center gap-4 text-primary font-black text-xs uppercase tracking-[0.4em]">
                                    <FaPenNib className="text-lg" /> Executive Credentials
                                </div>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-10">
                                    <div className="space-y-2">
                                        <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Legal Full Name</label>
                                        <input
                                            type="text"
                                            required
                                            className="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-[1.25rem] focus:bg-white focus:border-primary/30 focus:ring-4 focus:ring-primary/10 transition-all font-bold text-gray-900 outline-none"
                                            value={formData.name}
                                            onChange={e => setFormData({ ...formData, name: e.target.value })}
                                        />
                                    </div>
                                    <div className="space-y-2">
                                        <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Official Designation</label>
                                        <input
                                            type="text"
                                            required
                                            className="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-[1.25rem] focus:bg-white focus:border-primary/30 focus:ring-4 focus:ring-primary/10 transition-all font-bold text-gray-900 outline-none"
                                            value={formData.title_position}
                                            onChange={e => setFormData({ ...formData, title_position: e.target.value })}
                                        />
                                    </div>
                                </div>
                            </section>

                            <section className="space-y-8">
                                <div className="flex items-center gap-4 text-primary font-black text-xs uppercase tracking-[0.4em]">
                                    <FaGlobe className="text-lg" /> Multi-Language Addressment
                                </div>

                                <div className="space-y-10">
                                    {/* English */}
                                    <div className="space-y-3">
                                        <div className="flex items-center justify-between">
                                            <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                                                <FaLanguage className="text-base text-blue-500" /> English Terminal
                                            </label>
                                        </div>
                                        <textarea
                                            required
                                            className="w-full px-8 py-6 bg-slate-50 border-2 border-transparent rounded-[2rem] focus:bg-white focus:border-primary/30 focus:ring-8 focus:ring-primary/5 transition-all font-medium text-gray-800 outline-none h-40 resize-none leading-relaxed"
                                            value={formData.message_en}
                                            onChange={e => setFormData({ ...formData, message_en: e.target.value })}
                                        />
                                    </div>

                                    {/* Amharic */}
                                    <div className="space-y-3">
                                        <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                                            <FaLanguage className="text-base text-emerald-500" /> Amharic Translation
                                        </label>
                                        <textarea
                                            className="w-full px-8 py-6 bg-slate-50 border-2 border-transparent rounded-[2rem] focus:bg-white focus:border-emerald-500/30 focus:ring-8 focus:ring-emerald-500/5 transition-all font-medium text-gray-800 outline-none h-40 resize-none leading-relaxed"
                                            placeholder="ትረካ እዚህ ይግባ..."
                                            value={formData.message_am}
                                            onChange={e => setFormData({ ...formData, message_am: e.target.value })}
                                        />
                                    </div>

                                    {/* Oromo */}
                                    <div className="space-y-3">
                                        <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                                            <FaLanguage className="text-base text-orange-500" /> Afaan Oromo Translation
                                        </label>
                                        <textarea
                                            className="w-full px-8 py-6 bg-slate-50 border-2 border-transparent rounded-[2rem] focus:bg-white focus:border-orange-500/30 focus:ring-8 focus:ring-orange-500/5 transition-all font-medium text-gray-800 outline-none h-40 resize-none leading-relaxed"
                                            placeholder="Ergaa asitti galchi..."
                                            value={formData.message_or}
                                            onChange={e => setFormData({ ...formData, message_or: e.target.value })}
                                        />
                                    </div>
                                </div>
                            </section>

                            <section className="pt-12 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-8">
                                <div
                                    onClick={() => setFormData({ ...formData, is_active: !formData.is_active })}
                                    className="flex items-center gap-4 cursor-pointer group shrink-0"
                                >
                                    <div className={`w-14 h-8 rounded-full p-1 transition-colors duration-300 relative ${formData.is_active ? 'bg-primary' : 'bg-gray-200'}`}>
                                        <motion.div
                                            animate={{ x: formData.is_active ? 24 : 0 }}
                                            className="w-6 h-6 bg-white rounded-full shadow-sm"
                                        />
                                    </div>
                                    <span className="text-xs font-black text-gray-500 uppercase tracking-widest group-hover:text-primary transition-colors">Enabled for Global Broadcast</span>
                                </div>

                                <button
                                    type="submit"
                                    disabled={saving}
                                    className="w-full md:w-auto bg-gradient-to-r from-primary to-blue-700 text-white px-12 py-5 rounded-[1.5rem] font-black uppercase tracking-[0.2em] text-[10px] shadow-2xl shadow-primary/30 hover:shadow-primary/50 transition-all active:scale-95 flex items-center justify-center gap-4 group disabled:opacity-50"
                                >
                                    {saving ? <FaSpinner className="animate-spin" /> : <FaCheckCircle className="group-hover:rotate-12 transition-transform" />}
                                    {saving ? 'Synchronizing Protocols...' : 'Commit Changes to Core'}
                                </button>
                            </section>
                        </form>
                    </motion.div>
                </div>
            )}
        </AdminLayout>
    );
}
