'use client';
import { useState, useEffect, useCallback } from 'react';
import AdminLayout from '@/components/admin/AdminLayout';
import { FaPlus, FaTrash, FaToggleOn, FaToggleOff, FaTimes, FaEye, FaEyeSlash } from 'react-icons/fa';
import {
    fetchAllAlerts,
    createAlert,
    toggleAlert,
    deleteAlert,
    AlertItem,
} from '@/lib/api';

type FormData = {
    message_en: string;
    message_am: string;
    message_or: string;
    severity: 'info' | 'warning' | 'critical';
    is_active: boolean;
    expires_at: string;
};

const EMPTY_FORM: FormData = {
    message_en: '',
    message_am: '',
    message_or: '',
    severity: 'info',
    is_active: true,
    expires_at: '',
};

export default function AdminAlertsPage() {
    const [alerts, setAlerts] = useState<AlertItem[]>([]);
    const [loading, setLoading] = useState(true);
    const [showModal, setShowModal] = useState(false);
    const [form, setForm] = useState<FormData>(EMPTY_FORM);
    const [saving, setSaving] = useState(false);
    const [error, setError] = useState('');

    const token = typeof window !== 'undefined' ? localStorage.getItem('adminToken') || '' : '';

    const loadAlerts = useCallback(async () => {
        setLoading(true);
        const data = await fetchAllAlerts(token);
        setAlerts(data);
        setLoading(false);
    }, [token]);

    useEffect(() => { loadAlerts(); }, [loadAlerts]);

    const handleCreate = async (e: React.FormEvent) => {
        e.preventDefault();
        setSaving(true);
        setError('');
        try {
            await createAlert(
                {
                    message_en: form.message_en,
                    message_am: form.message_am,
                    message_or: form.message_or,
                    severity: form.severity,
                    is_active: form.is_active,
                    expires_at: form.expires_at || undefined,
                },
                token,
            );
            setShowModal(false);
            setForm(EMPTY_FORM);
            loadAlerts();
        } catch (err: any) {
            setError(err.message || 'An error occurred.');
        } finally {
            setSaving(false);
        }
    };

    const handleToggle = async (id: number) => {
        try {
            await toggleAlert(id, token);
            setAlerts((prev) =>
                prev.map((a) => (a.id === id ? { ...a, is_active: !a.is_active } : a)),
            );
        } catch (err: any) {
            alert(err.message || 'Failed to toggle.');
        }
    };

    const handleDelete = async (id: number) => {
        if (!confirm('Delete this alert permanently?')) return;
        try {
            await deleteAlert(id, token);
            setAlerts((prev) => prev.filter((a) => a.id !== id));
        } catch (err: any) {
            alert(err.message || 'Failed to delete.');
        }
    };

    return (
        <AdminLayout>
            <div className="mb-10">
                <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div className="flex items-center gap-3 mb-2">
                            <span className="h-1 w-12 bg-rose-600 rounded-full"></span>
                            <span className="text-[10px] font-black uppercase tracking-[0.3em] text-rose-600">Crisis Response</span>
                        </div>
                        <h1 className="text-4xl font-black text-slate-900 tracking-tight italic">
                            Emergency <span className="text-rose-600">Terminal</span>
                        </h1>
                        <p className="text-slate-500 mt-2 text-sm font-medium">Broadcast critical warnings and information to the public site in real-time.</p>
                    </div>
                    <button
                        onClick={() => { setShowModal(true); setError(''); setForm(EMPTY_FORM); }}
                        className="group bg-rose-600 text-white px-8 py-4 rounded-2xl flex items-center gap-3 hover:bg-rose-700 transition-all shadow-2xl shadow-rose-200 active:scale-95"
                    >
                        <div className="w-6 h-6 bg-white/20 rounded-lg flex items-center justify-center group-hover:rotate-90 transition-transform">
                            <FaPlus className="text-xs" />
                        </div>
                        <span className="font-black text-[10px] uppercase tracking-widest">Broadcast New Alert</span>
                    </button>
                </div>
            </div>

            <div className="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="bg-slate-900">
                                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Broadcast Message</th>
                                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Urgency</th>
                                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Transmission</th>
                                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Expiration</th>
                                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Control</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-50">
                            {loading ? (
                                Array.from({ length: 4 }).map((_, i) => (
                                    <tr key={i} className="animate-pulse">
                                        <td className="px-8 py-6"><div className="h-4 bg-slate-100 rounded w-64" /></td>
                                        <td className="px-8 py-6"><div className="h-4 bg-slate-100 rounded w-20" /></td>
                                        <td className="px-8 py-6"><div className="h-4 bg-slate-100 rounded w-16" /></td>
                                        <td className="px-8 py-6"><div className="h-4 bg-slate-100 rounded w-24" /></td>
                                        <td className="px-8 py-6" />
                                    </tr>
                                ))
                            ) : alerts.length === 0 ? (
                                <tr>
                                    <td colSpan={5} className="px-8 py-24 text-center">
                                        <div className="flex flex-col items-center gap-4 opacity-20">
                                            <FaToggleOff className="text-6xl text-slate-400" />
                                            <p className="text-slate-400 font-bold tracking-tight">System nominal. No active broadcasts found.</p>
                                        </div>
                                    </td>
                                </tr>
                            ) : alerts.map((alert) => (
                                <tr key={alert.id} className="hover:bg-slate-50 transition-all group">
                                    <td className="px-8 py-6">
                                        <div className="max-w-md">
                                            <p className="font-bold text-slate-900 leading-snug">{alert.message_en}</p>
                                            <div className="mt-2 flex gap-4 text-[9px] font-black uppercase tracking-widest text-slate-400 italic">
                                                <span>AM: {alert.message_am ? 'Localized' : 'Missing'}</span>
                                                <span>OR: {alert.message_or ? 'Localized' : 'Missing'}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-8 py-6">
                                        <span className={`px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-[0.1em] border-2 shadow-sm ${alert.severity === 'critical'
                                            ? 'bg-rose-100 text-rose-700 border-rose-200'
                                            : alert.severity === 'warning'
                                                ? 'bg-amber-100 text-amber-700 border-amber-200'
                                                : 'bg-indigo-100 text-indigo-700 border-indigo-200'
                                            }`}>
                                            {alert.severity}
                                        </span>
                                    </td>
                                    <td className="px-8 py-6">
                                        <button
                                            onClick={() => handleToggle(alert.id)}
                                            className={`flex items-center gap-3 transition-colors ${alert.is_active ? 'text-emerald-600' : 'text-slate-300'}`}
                                        >
                                            {alert.is_active ? <FaToggleOn size={28} className="drop-shadow-sm" /> : <FaToggleOff size={28} />}
                                            <span className="text-[10px] font-black uppercase tracking-widest">
                                                {alert.is_active ? 'Online' : 'Offline'}
                                            </span>
                                        </button>
                                    </td>
                                    <td className="px-8 py-6 text-slate-500 font-bold text-xs italic">
                                        {alert.expires_at ? new Date(alert.expires_at).toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' }) : 'PERMANENT'}
                                    </td>
                                    <td className="px-8 py-6 text-right">
                                        <button
                                            onClick={() => handleDelete(alert.id)}
                                            className="w-10 h-10 flex items-center justify-center bg-white text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all shadow-sm border border-slate-100"
                                            title="Terminate Broadcast"
                                        >
                                            <FaTrash size={14} />
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>

            {/* Emergency Broadcast Modal */}
            {showModal && (
                <div className="fixed inset-0 bg-slate-900/60 backdrop-blur-md flex items-center justify-center z-50 p-4">
                    <div className="bg-white rounded-[2rem] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.3)] w-full max-w-2xl overflow-hidden border border-slate-100">
                        <div className="flex items-center justify-between p-8 border-b bg-slate-50/50">
                            <div>
                                <h2 className="text-2xl font-black text-slate-900 tracking-tight italic">
                                    Broadcast <span className="text-rose-600">Sequencer</span>
                                </h2>
                                <p className="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] mt-1">Configure Public Alert System</p>
                            </div>
                            <button onClick={() => setShowModal(false)} className="w-10 h-10 bg-white border border-slate-200 rounded-full flex items-center justify-center text-slate-400 hover:text-rose-600 hover:border-rose-200 transition-all shadow-sm">
                                <FaTimes size={18} />
                            </button>
                        </div>

                        <form onSubmit={handleCreate} className="p-8 space-y-8 scrollbar-thin scrollbar-thumb-slate-200 max-h-[70vh] overflow-y-auto">
                            {error && (
                                <div className="bg-rose-50 border-2 border-rose-100 p-4 rounded-2xl text-rose-600 text-xs font-black uppercase tracking-widest flex items-center gap-3 animate-pulse">
                                    <div className="w-6 h-6 bg-rose-600 text-white rounded-full flex items-center justify-center text-[10px]">!</div>
                                    {error}
                                </div>
                            )}

                            <div className="space-y-6">
                                <div>
                                    <div className="flex items-center gap-3 mb-3">
                                        <div className="h-4 w-1 bg-rose-600 rounded-full" />
                                        <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Message (English) <span className="text-rose-500">*</span></label>
                                    </div>
                                    <textarea
                                        required
                                        rows={3}
                                        value={form.message_en}
                                        onChange={(e) => setForm({ ...form, message_en: e.target.value })}
                                        className="w-full border-2 border-slate-100 bg-slate-50/50 rounded-2xl px-6 py-4 focus:outline-none focus:border-rose-500 focus:bg-white transition-all text-sm font-bold text-slate-900 placeholder:text-slate-300 resize-none"
                                        placeholder="Enter the primary English broadcast message..."
                                    />
                                </div>

                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <div className="flex items-center gap-3 mb-3">
                                            <div className="h-4 w-1 bg-slate-300 rounded-full" />
                                            <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Message (Amharic)</label>
                                        </div>
                                        <textarea
                                            rows={2}
                                            value={form.message_am}
                                            onChange={(e) => setForm({ ...form, message_am: e.target.value })}
                                            className="w-full border-2 border-slate-100 bg-slate-50/50 rounded-xl px-4 py-3 focus:outline-none focus:border-rose-500 focus:bg-white transition-all text-xs font-medium text-slate-900"
                                            placeholder="መልዕክት በዐማርኛ..."
                                        />
                                    </div>
                                    <div>
                                        <div className="flex items-center gap-3 mb-3">
                                            <div className="h-4 w-1 bg-slate-300 rounded-full" />
                                            <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Message (Afaan Oromo)</label>
                                        </div>
                                        <textarea
                                            rows={2}
                                            value={form.message_or}
                                            onChange={(e) => setForm({ ...form, message_or: e.target.value })}
                                            className="w-full border-2 border-slate-100 bg-slate-50/50 rounded-xl px-4 py-3 focus:outline-none focus:border-rose-500 focus:bg-white transition-all text-xs font-medium text-slate-900"
                                            placeholder="Ergaa..."
                                        />
                                    </div>
                                </div>

                                <div className="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                                    <div className="space-y-4">
                                        <div className="flex items-center gap-3 mb-1">
                                            <div className="h-4 w-1 bg-amber-500 rounded-full" />
                                            <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Urgency Scale</label>
                                        </div>
                                        <div className="flex gap-2">
                                            {(['info', 'warning', 'critical'] as const).map((s) => (
                                                <button
                                                    key={s}
                                                    type="button"
                                                    onClick={() => setForm({ ...form, severity: s })}
                                                    className={`flex-1 py-4 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all border-2 ${form.severity === s
                                                        ? s === 'critical' ? 'bg-rose-600 border-rose-600 text-white shadow-lg shadow-rose-200'
                                                            : s === 'warning' ? 'bg-amber-500 border-amber-500 text-white shadow-lg shadow-amber-200'
                                                                : 'bg-indigo-600 border-indigo-600 text-white shadow-lg shadow-indigo-200'
                                                        : 'bg-slate-50 border-slate-100 text-slate-400 hover:bg-slate-100'
                                                        }`}
                                                >
                                                    {s}
                                                </button>
                                            ))}
                                        </div>
                                    </div>
                                    <div className="space-y-4">
                                        <div className="flex items-center gap-3 mb-1">
                                            <div className="h-4 w-1 bg-indigo-500 rounded-full" />
                                            <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Expiration Protocol</label>
                                        </div>
                                        <input
                                            type="date"
                                            value={form.expires_at}
                                            onChange={(e) => setForm({ ...form, expires_at: e.target.value })}
                                            className="w-full border-2 border-slate-100 bg-slate-50/50 rounded-xl px-6 py-3.5 focus:outline-none focus:border-indigo-500 focus:bg-white transition-all text-xs font-black text-slate-900"
                                        />
                                    </div>
                                </div>

                                <div className="flex items-center gap-4 bg-slate-900 p-6 rounded-[1.5rem] border border-slate-800 shadow-xl shadow-slate-200">
                                    <div className="relative inline-flex items-center cursor-pointer">
                                        <input
                                            type="checkbox"
                                            className="sr-only peer"
                                            checked={form.is_active}
                                            onChange={(e) => setForm({ ...form, is_active: e.target.checked })}
                                        />
                                        <div className="w-14 h-8 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-emerald-500 shadow-inner"></div>
                                    </div>
                                    <div>
                                        <p className="text-xs font-black text-white uppercase tracking-widest">Live Broadcast</p>
                                        <p className="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Push immediately to public interfaces</p>
                                    </div>
                                </div>
                            </div>

                            <div className="flex justify-end gap-3 pt-6">
                                <button
                                    type="button"
                                    onClick={() => setShowModal(false)}
                                    className="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 border-2 border-slate-100 rounded-2xl hover:bg-slate-50 transition-all"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    disabled={saving}
                                    className="px-10 py-4 bg-rose-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-700 transition-all disabled:opacity-50 shadow-2xl shadow-rose-200"
                                >
                                    {saving ? 'Transmitting...' : 'Initialize Broadcast'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}
