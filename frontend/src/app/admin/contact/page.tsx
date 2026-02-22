'use client';

import { useEffect, useState } from 'react';
import AdminLayout from '@/components/admin/AdminLayout';
import { fetchContactMessages, ContactMessage } from '@/lib/api';
import { FaEnvelope, FaUser, FaCalendarAlt, FaSpinner, FaEye } from 'react-icons/fa';

export default function ContactAdminPage() {
    const [messages, setMessages] = useState<ContactMessage[]>([]);
    const [loading, setLoading] = useState(true);
    const [token, setToken] = useState('');
    const [selectedMsg, setSelectedMsg] = useState<ContactMessage | null>(null);

    useEffect(() => {
        const t = localStorage.getItem('adminToken') || '';
        setToken(t);
        loadMessages(t);
    }, []);

    async function loadMessages(t: string) {
        setLoading(true);
        const data = await fetchContactMessages(t);
        setMessages(data);
        setLoading(false);
    }

    return (
        <AdminLayout>
            <div className="mb-6 flex justify-between items-center">
                <div>
                    <h1 className="text-2xl font-bold text-gray-800">Contact Us Messages</h1>
                    <p className="text-gray-500 text-sm">View and manage inquiries from the public.</p>
                </div>
            </div>

            {loading ? (
                <div className="p-20 text-center text-gray-400">
                    <FaSpinner className="animate-spin text-4xl mx-auto mb-4" />
                    <p>Loading messages...</p>
                </div>
            ) : messages.length === 0 ? (
                <div className="bg-white rounded-xl border border-dashed border-gray-300 p-20 text-center">
                    <FaEnvelope className="text-5xl text-gray-200 mx-auto mb-4" />
                    <h3 className="text-lg font-medium text-gray-900">No messages yet</h3>
                    <p className="text-gray-500">Inquiries submitted via the Contact form will appear here.</p>
                </div>
            ) : (
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {/* List */}
                    <div className="lg:col-span-1 space-y-4">
                        {messages.map((m) => (
                            <div
                                key={m.id}
                                onClick={() => setSelectedMsg(m)}
                                className={`p-4 rounded-xl border cursor-pointer transition-all ${selectedMsg?.id === m.id
                                        ? 'bg-primary/5 border-primary shadow-sm'
                                        : 'bg-white border-gray-100 hover:border-primary/30'
                                    }`}
                            >
                                <div className="flex justify-between items-start mb-2">
                                    <h4 className="font-bold text-gray-900 truncate pr-2">{m.name}</h4>
                                    <span className={`text-[10px] px-2 py-0.5 rounded-full font-bold uppercase ${m.status === 'read' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700'
                                        }`}>
                                        {m.status || 'new'}
                                    </span>
                                </div>
                                <p className="text-xs text-gray-500 mb-2 truncate">{m.subject || 'No Subject'}</p>
                                <div className="flex items-center gap-3 mt-3 pt-3 border-t border-gray-50 text-[10px] text-gray-400">
                                    <span className="flex items-center gap-1">
                                        <FaCalendarAlt />
                                        {m.created_at ? new Date(m.created_at).toLocaleDateString() : '—'}
                                    </span>
                                </div>
                            </div>
                        ))}
                    </div>

                    {/* Details */}
                    <div className="lg:col-span-2">
                        {selectedMsg ? (
                            <div className="bg-white rounded-xl border border-gray-100 shadow-sm p-8 h-full">
                                <div className="flex justify-between items-start mb-8 pb-6 border-b">
                                    <div>
                                        <h2 className="text-xl font-bold text-gray-900 mb-1">{selectedMsg.subject || 'Inquiry'}</h2>
                                        <p className="text-sm text-gray-500">From: {selectedMsg.name}</p>
                                    </div>
                                    <div className="text-right text-xs text-gray-400">
                                        <p>{selectedMsg.created_at ? new Date(selectedMsg.created_at).toLocaleString() : ''}</p>
                                    </div>
                                </div>

                                <div className="grid grid-cols-2 gap-6 mb-8">
                                    <div className="bg-gray-50 p-4 rounded-lg">
                                        <p className="text-[10px] text-gray-400 uppercase font-bold mb-1">Email Address</p>
                                        <p className="text-sm text-gray-700 font-medium">{selectedMsg.email}</p>
                                    </div>
                                    <div className="bg-gray-50 p-4 rounded-lg">
                                        <p className="text-[10px] text-gray-400 uppercase font-bold mb-1">Phone Number</p>
                                        <p className="text-sm text-gray-700 font-medium">{selectedMsg.phone || 'Not provided'}</p>
                                    </div>
                                </div>

                                <div>
                                    <p className="text-[10px] text-gray-400 uppercase font-bold mb-3">Message Body</p>
                                    <div className="bg-white border rounded-xl p-6 text-gray-700 leading-relaxed whitespace-pre-wrap">
                                        {selectedMsg.message}
                                    </div>
                                </div>

                                <div className="mt-12 pt-8 border-t flex gap-4">
                                    <a
                                        href={`mailto:${selectedMsg.email}?subject=Re: ${selectedMsg.subject || 'Inquiry'}`}
                                        className="bg-primary text-white px-6 py-2.5 rounded-lg font-bold text-sm hover:bg-opacity-90 transition"
                                    >
                                        Reply via Email
                                    </a>
                                </div>
                            </div>
                        ) : (
                            <div className="bg-gray-50 rounded-xl border border-dashed border-gray-200 h-full flex flex-col items-center justify-center p-20 text-center">
                                <FaEye className="text-4xl text-gray-200 mb-4" />
                                <p className="text-gray-400">Select a message from the list to view details.</p>
                            </div>
                        )}
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}
