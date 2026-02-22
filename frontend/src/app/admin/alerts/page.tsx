'use client';
import { useState, useEffect, useCallback } from 'react';
import AdminLayout from '@/components/admin/AdminLayout';
import { FaPlus, FaTrash, FaToggleOn, FaToggleOff, FaTimes } from 'react-icons/fa';
import {
    fetchAllAlerts,
    createAlert,
    toggleAlert,
    deleteAlert,
    AlertItem,
} from '@/lib/api';

type FormData = {
    message_en: string;
    severity: 'info' | 'warning' | 'critical';
    is_active: boolean;
    expires_at: string;
};

const EMPTY_FORM: FormData = {
    message_en: '',
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

    const severityColor = (s: string) => {
        if (s === 'critical') return 'bg-red-100 text-red-700';
        if (s === 'warning') return 'bg-amber-100 text-amber-700';
        return 'bg-blue-100 text-blue-700';
    };

    return (
        <AdminLayout>
            <div className="flex justify-between items-center mb-6">
                <h1 className="text-2xl font-bold text-gray-800">Emergency Alerts</h1>
                <button
                    onClick={() => { setShowModal(true); setError(''); setForm(EMPTY_FORM); }}
                    className="bg-red-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-red-700 transition"
                >
                    <FaPlus /> New Alert
                </button>
            </div>

            <div className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table className="w-full text-left">
                    <thead className="bg-gray-50 border-b">
                        <tr>
                            <th className="px-6 py-4 font-semibold text-gray-600">Message</th>
                            <th className="px-6 py-4 font-semibold text-gray-600">Severity</th>
                            <th className="px-6 py-4 font-semibold text-gray-600">Status</th>
                            <th className="px-6 py-4 font-semibold text-gray-600">Expires</th>
                            <th className="px-6 py-4 font-semibold text-gray-600 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-gray-100">
                        {loading ? (
                            <tr><td colSpan={5} className="px-6 py-8 text-center text-gray-400">Loading alerts...</td></tr>
                        ) : alerts.length === 0 ? (
                            <tr><td colSpan={5} className="px-6 py-8 text-center text-gray-400">No alerts found. Create one to broadcast an emergency message.</td></tr>
                        ) : alerts.map((alert) => (
                            <tr key={alert.id} className="hover:bg-gray-50 transition">
                                <td className="px-6 py-4 font-medium text-gray-900 max-w-sm truncate">{alert.message_en}</td>
                                <td className="px-6 py-4">
                                    <span className={`px-2 py-1 rounded-full text-xs font-medium uppercase ${severityColor(alert.severity)}`}>
                                        {alert.severity}
                                    </span>
                                </td>
                                <td className="px-6 py-4">
                                    <button
                                        onClick={() => handleToggle(alert.id)}
                                        className={`flex items-center gap-2 text-sm font-medium ${alert.is_active ? 'text-green-600' : 'text-gray-400'}`}
                                        title={alert.is_active ? 'Click to deactivate' : 'Click to activate'}
                                    >
                                        {alert.is_active ? <FaToggleOn size={22} /> : <FaToggleOff size={22} />}
                                        {alert.is_active ? 'Active' : 'Inactive'}
                                    </button>
                                </td>
                                <td className="px-6 py-4 text-gray-500 text-sm">
                                    {alert.expires_at
                                        ? new Date(alert.expires_at).toLocaleDateString()
                                        : '—'}
                                </td>
                                <td className="px-6 py-4 text-right">
                                    <button
                                        onClick={() => handleDelete(alert.id)}
                                        className="text-red-500 hover:text-red-700"
                                        title="Delete"
                                    >
                                        <FaTrash />
                                    </button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
                <div className="p-4 border-t bg-gray-50 text-sm text-gray-500">
                    {alerts.length} alert{alerts.length !== 1 ? 's' : ''} total
                </div>
            </div>

            {/* Create Alert Modal */}
            {showModal && (
                <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                    <div className="bg-white rounded-xl shadow-2xl w-full max-w-md">
                        <div className="flex items-center justify-between p-6 border-b">
                            <h2 className="text-xl font-bold text-gray-800">Create Alert</h2>
                            <button onClick={() => setShowModal(false)} className="text-gray-400 hover:text-gray-600">
                                <FaTimes size={20} />
                            </button>
                        </div>
                        <form onSubmit={handleCreate} className="p-6 space-y-4">
                            {error && (
                                <p className="text-red-600 bg-red-50 border border-red-200 p-3 rounded-lg text-sm">{error}</p>
                            )}
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Message (English)</label>
                                <textarea
                                    required
                                    rows={3}
                                    value={form.message_en}
                                    onChange={(e) => setForm({ ...form, message_en: e.target.value })}
                                    className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 resize-none"
                                    placeholder="e.g. Heavy rainfall warning in highland districts..."
                                />
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Severity</label>
                                    <select
                                        value={form.severity}
                                        onChange={(e) => setForm({ ...form, severity: e.target.value as FormData['severity'] })}
                                        className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"
                                    >
                                        <option value="info">Info</option>
                                        <option value="warning">Warning</option>
                                        <option value="critical">Critical</option>
                                    </select>
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Expires At</label>
                                    <input
                                        type="date"
                                        value={form.expires_at}
                                        onChange={(e) => setForm({ ...form, expires_at: e.target.value })}
                                        className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"
                                    />
                                </div>
                            </div>
                            <div className="flex items-center gap-3">
                                <input
                                    type="checkbox"
                                    id="is_active"
                                    checked={form.is_active}
                                    onChange={(e) => setForm({ ...form, is_active: e.target.checked })}
                                    className="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500"
                                />
                                <label htmlFor="is_active" className="text-sm text-gray-700">
                                    Activate immediately (show on public site)
                                </label>
                            </div>
                            <div className="flex justify-end gap-3 pt-2">
                                <button
                                    type="button"
                                    onClick={() => setShowModal(false)}
                                    className="px-4 py-2 text-gray-600 border rounded-lg hover:bg-gray-50 transition"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    disabled={saving}
                                    className="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition disabled:opacity-50"
                                >
                                    {saving ? 'Creating...' : 'Broadcast Alert'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}
