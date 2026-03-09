'use client';

import { useEffect, useState, useCallback } from 'react';
import AdminLayout from '@/components/admin/AdminLayout';
import { fetchContactMessages, ContactMessage } from '@/lib/api';
import { FaEnvelope, FaUser, FaCalendarAlt, FaSpinner, FaEye, FaReply, FaCheckCircle, FaTrash, FaMailBulk, FaClock, FaSearch, FaInfoCircle, FaPhoneAlt, FaEnvelopeOpenText } from 'react-icons/fa';
import { motion, AnimatePresence } from 'framer-motion';

export default function ContactAdminPage() {
    const [messages, setMessages] = useState<ContactMessage[]>([]);
    const [loading, setLoading] = useState(true);
    const [selectedMsg, setSelectedMsg] = useState<ContactMessage | null>(null);
    const [searchQuery, setSearchQuery] = useState('');

    const loadMessages = useCallback(async () => {
        setLoading(true);
        try {
            const t = localStorage.getItem('adminToken') || '';
            const data = await fetchContactMessages(t);
            setMessages(data);
        } catch (err) {
            console.error('Failed to load messages:', err);
        } finally {
            setLoading(false);
        }
    }, []);

    useEffect(() => {
        loadMessages();
    }, [loadMessages]);

    const filteredMessages = messages.filter(m =>
        m.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
        (m.subject || '').toLowerCase().includes(searchQuery.toLowerCase()) ||
        m.email.toLowerCase().includes(searchQuery.toLowerCase())
    );

    return (
        <AdminLayout>
            <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h1 className="text-3xl font-black text-gray-900 tracking-tight">Public Inquiries</h1>
                    <p className="text-gray-500 mt-1 font-medium italic">Manage official communications from the public portal.</p>
                </div>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 h-[calc(100vh-250px)]">
                {/* Master List */}
                <div className="lg:col-span-4 flex flex-col h-full bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                    <div className="p-6 border-b border-gray-50 bg-gray-50/50">
                        <div className="relative">
                            <FaSearch className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" />
                            <input
                                type="text"
                                placeholder="Search conversations..."
                                className="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary/50 transition-all font-medium text-sm outline-none"
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                            />
                        </div>
                    </div>

                    <div className="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-3">
                        {loading ? (
                            [...Array(6)].map((_, i) => (
                                <div key={i} className="p-5 bg-gray-50 rounded-2xl animate-pulse flex gap-4">
                                    <div className="w-10 h-10 bg-gray-200 rounded-full" />
                                    <div className="flex-1 space-y-2">
                                        <div className="h-4 bg-gray-200 rounded w-1/2" />
                                        <div className="h-3 bg-gray-100 rounded w-3/4" />
                                    </div>
                                </div>
                            ))
                        ) : filteredMessages.length === 0 ? (
                            <div className="flex flex-col items-center justify-center h-full text-center p-8">
                                <FaEnvelopeOpenText className="text-5xl text-gray-100 mb-4" />
                                <p className="text-gray-400 font-bold uppercase tracking-widest text-[10px]">No active transmissions</p>
                            </div>
                        ) : filteredMessages.map((m) => (
                            <motion.div
                                key={m.id}
                                layoutId={`msg-${m.id}`}
                                onClick={() => setSelectedMsg(m)}
                                className={`p-5 rounded-[1.5rem] border cursor-pointer group transition-all duration-300 relative ${selectedMsg?.id === m.id
                                    ? 'bg-primary/5 border-primary shadow-lg shadow-primary/10 ring-1 ring-primary/20'
                                    : 'bg-white border-gray-100 hover:bg-gray-50/80 hover:scale-[1.02] shadow-sm'
                                    }`}
                            >
                                <div className="flex justify-between items-start mb-2">
                                    <div className="flex items-center gap-3">
                                        <div className={`w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black shrink-0 ${selectedMsg?.id === m.id ? 'bg-primary text-white' : 'bg-gray-100 text-gray-500'
                                            }`}>
                                            {m.name.charAt(0).toUpperCase()}
                                        </div>
                                        <h4 className={`font-black tracking-tight truncate whitespace-nowrap max-w-[120px] transition-colors ${selectedMsg?.id === m.id ? 'text-primary' : 'text-gray-900 group-hover:text-primary'
                                            }`}>
                                            {m.name}
                                        </h4>
                                    </div>
                                    <span className={`text-[9px] px-2 py-0.5 rounded-lg font-black uppercase tracking-tighter ${m.status === 'read' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700'
                                        }`}>
                                        {m.status || 'new'}
                                    </span>
                                </div>
                                <p className="text-[11px] font-bold text-gray-500 mb-3 truncate pl-11">{m.subject || 'Encryption: No Subject'}</p>
                                <div className="flex items-center justify-between pl-11">
                                    <div className="flex items-center gap-2 text-[9px] text-gray-400 font-black uppercase tracking-widest">
                                        <FaClock className="text-[8px]" />
                                        {m.created_at ? new Date(m.created_at).toLocaleDateString() : 'Unknown'}
                                    </div>
                                    {m.status !== 'read' && (
                                        <div className="w-2 h-2 bg-primary rounded-full animate-pulse shadow-[0_0_8px_rgba(var(--primary-rgb),0.5)]" />
                                    )}
                                </div>
                            </motion.div>
                        ))}
                    </div>
                </div>

                {/* Detail View */}
                <div className="lg:col-span-8 bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col h-full relative">
                    <AnimatePresence mode="wait">
                        {selectedMsg ? (
                            <motion.div
                                key={`detail-${selectedMsg.id}`}
                                initial={{ opacity: 0, x: 20 }}
                                animate={{ opacity: 1, x: 0 }}
                                exit={{ opacity: 0, x: -20 }}
                                className="flex flex-col h-full"
                            >
                                <div className="p-10 border-b border-gray-50 shrink-0">
                                    <div className="flex items-start justify-between">
                                        <div className="flex items-center gap-6">
                                            <div className="w-16 h-16 bg-gradient-to-tr from-primary to-blue-600 rounded-[1.75rem] flex items-center justify-center text-white text-2xl font-black shadow-xl shadow-primary/20">
                                                {selectedMsg.name.charAt(0).toUpperCase()}
                                            </div>
                                            <div>
                                                <h2 className="text-3xl font-black text-gray-900 tracking-tight mb-1">{selectedMsg.subject || 'Inquiry Subject Missing'}</h2>
                                                <div className="flex items-center gap-3">
                                                    <span className="text-xs font-bold text-gray-400 flex items-center gap-2 bg-gray-50 px-3 py-1 rounded-full uppercase tracking-tighter">
                                                        <FaUser className="text-[9px] text-primary" /> {selectedMsg.name}
                                                    </span>
                                                    <span className="text-xs font-bold text-gray-400 flex items-center gap-2 bg-gray-50 px-3 py-1 rounded-full uppercase tracking-tighter">
                                                        <FaClock className="text-[9px] text-primary" /> {selectedMsg.created_at ? new Date(selectedMsg.created_at).toLocaleString() : 'Undated Terminal'}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="flex gap-2">
                                            <button className="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400 hover:bg-rose-50 hover:text-rose-600 transition-all border border-transparent hover:border-rose-100 group">
                                                <FaTrash size={14} className="group-hover:scale-110 transition-transform" />
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div className="p-10 flex-1 overflow-y-auto custom-scrollbar space-y-10">
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div className="p-6 bg-slate-50 rounded-[2rem] border border-slate-100 flex items-center gap-5 group hover:bg-white hover:shadow-xl hover:shadow-slate-200/50 transition-all">
                                            <div className="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-primary shadow-sm border border-slate-200 group-hover:bg-primary group-hover:text-white transition-colors">
                                                <FaPhoneAlt />
                                            </div>
                                            <div>
                                                <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Callback Terminal</p>
                                                <p className="font-black text-gray-900">{selectedMsg.phone || 'Registry Missing'}</p>
                                            </div>
                                        </div>
                                        <div className="p-6 bg-slate-50 rounded-[2rem] border border-slate-100 flex items-center gap-5 group hover:bg-white hover:shadow-xl hover:shadow-slate-200/50 transition-all">
                                            <div className="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-primary shadow-sm border border-slate-200 group-hover:bg-primary group-hover:text-white transition-colors">
                                                <FaMailBulk />
                                            </div>
                                            <div>
                                                <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Response Channel</p>
                                                <p className="font-black text-gray-900 lowercase">{selectedMsg.email}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div className="space-y-4">
                                        <div className="flex items-center gap-2 text-primary font-black text-[10px] uppercase tracking-[0.25em] ml-2">
                                            <FaInfoCircle /> Transmission Body
                                        </div>
                                        <div className="bg-white border-2 border-slate-50 rounded-[2.5rem] p-10 text-gray-800 font-medium leading-relaxed text-lg shadow-inner-white selection:bg-primary selection:text-white underline-offset-8 decoration-primary/20">
                                            {selectedMsg.message}
                                        </div>
                                    </div>
                                </div>

                                <div className="p-10 border-t border-gray-50 flex items-center justify-between bg-gray-50/30 shrink-0">
                                    <p className="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] flex items-center gap-2">
                                        <FaEye className="text-primary animate-pulse" /> Channel monitored by Admin
                                    </p>
                                    <a
                                        href={`mailto:${selectedMsg.email}?subject=Official Response: ${selectedMsg.subject || 'Inquiry'}`}
                                        className="bg-gradient-to-r from-primary to-blue-700 text-white px-12 py-5 rounded-[1.5rem] font-black uppercase tracking-widest text-[10px] shadow-2xl shadow-primary/30 hover:shadow-primary/50 transition-all active:scale-95 flex items-center gap-4 group"
                                    >
                                        <FaReply className="group-hover:-translate-x-1 group-hover:rotate-12 transition-transform" />
                                        Authorize Secure Response
                                    </a>
                                </div>
                            </motion.div>
                        ) : (
                            <div className="flex flex-col items-center justify-center h-full p-20 text-center">
                                <div className="w-32 h-32 bg-gray-50 rounded-[3rem] flex items-center justify-center mb-8 border border-gray-100 shadow-inner group overflow-hidden">
                                    <FaEnvelope className="text-6xl text-gray-100 group-hover:scale-110 group-hover:text-primary/10 transition-all duration-500" />
                                </div>
                                <h3 className="text-2xl font-black text-gray-900 tracking-tight mb-2">Awaiting Decryption</h3>
                                <p className="text-gray-400 font-medium max-w-xs mx-auto">Select a transmission from the secure feed to initiate clearance and response protocols.</p>
                                <div className="mt-8 flex gap-2">
                                    <div className="w-2 h-2 bg-gray-100 rounded-full animate-bounce" />
                                    <div className="w-2 h-2 bg-gray-100 rounded-full animate-bounce delay-150" />
                                    <div className="w-2 h-2 bg-gray-100 rounded-full animate-bounce delay-300" />
                                </div>
                            </div>
                        )}
                    </AnimatePresence>
                </div>
            </div>
        </AdminLayout>
    );
}
