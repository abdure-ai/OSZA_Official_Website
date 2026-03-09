'use client';

import { useEffect, useState } from 'react';
import { FaPhone, FaEnvelope, FaMapMarkerAlt, FaClock, FaPaperPlane, FaFacebook, FaTwitter, FaLinkedin, FaYoutube, FaHeadset } from 'react-icons/fa';
import { submitContactForm, fetchOfficeSettings, OfficeSettings } from '@/lib/api';

export default function ContactPage() {
    const [settings, setSettings] = useState<OfficeSettings | null>(null);
    const [formData, setFormData] = useState({
        name: '',
        email: '',
        phone: '',
        subject: '',
        message: ''
    });
    const [status, setStatus] = useState<{ type: 'success' | 'error' | null; msg: string }>({ type: null, msg: '' });
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        loadSettings();
    }, []);

    async function loadSettings() {
        const data = await fetchOfficeSettings();
        if (data) setSettings(data);
    }

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setLoading(true);
        setStatus({ type: null, msg: '' });

        try {
            await submitContactForm(formData);
            setStatus({ type: 'success', msg: 'Your message has been sent successfully. We will get back to you soon!' });
            setFormData({ name: '', email: '', phone: '', subject: '', message: '' });
        } catch (error: any) {
            setStatus({ type: 'error', msg: error.message || 'Something went wrong. Please try again later.' });
        } finally {
            setLoading(false);
        }
    };

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
        setFormData(prev => ({ ...prev, [e.target.id]: e.target.value }));
    };

    return (
        <div className="bg-gray-50 min-h-screen pb-20">
            {/* ═══════════════════════════════════════════ CONTACT HERO ══ */}
            <section className="relative bg-blue-900 text-white py-20 md:py-32 overflow-hidden">
                <div className="absolute inset-0 z-0">
                    <div className="absolute inset-0 bg-gradient-to-r from-blue-950/80 to-blue-900/40 z-10"></div>
                    <img
                        src="https://images.unsplash.com/photo-1423666639041-f56000c27a9a?q=80&w=1920"
                        className="w-full h-full object-cover scale-110 opacity-60"
                        alt="Contact Hero"
                    />
                </div>

                <div className="container mx-auto px-4 relative z-20">
                    <div className="max-w-3xl">
                        <span className="inline-block px-4 py-1.5 bg-[#f5a623] text-blue-900 text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-6 shadow-xl">
                            Support & Inquiries
                        </span>
                        <h1 className="text-5xl md:text-7xl font-black mb-4 leading-none antialiased drop-shadow-2xl italic tracking-tight uppercase">
                            Get in <span className="text-[#f5a623]">Touch</span>
                        </h1>
                        <p className="text-lg md:text-xl text-gray-200 font-medium opacity-90">
                            We are here to listen and serve. Reach out to the Administration for inquiries, official support, or civic feedback.
                        </p>
                    </div>
                </div>
                {/* Bottom fade */}
                <div className="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-gray-50 to-transparent"></div>
            </section>

            <div className="container mx-auto px-4 -mt-10 relative z-30">
                <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 max-w-[1440px] mx-auto">

                    {/* Main Content: Contact Form */}
                    <div className="lg:col-span-7">
                        <div className="bg-white p-10 md:p-16 rounded-[2.5rem] shadow-2xl border border-gray-100">
                            <h2 className="text-2xl font-black text-gray-900 mb-8 italic tracking-tight">Send a Formal Message</h2>

                            {status.type && (
                                <div className={`mb-10 p-6 rounded-2xl text-sm font-bold flex items-center gap-4 ${status.type === 'success' ? 'bg-green-50 text-green-800 border-2 border-green-100' : 'bg-red-50 text-red-800 border-2 border-red-100'}`}>
                                    {status.type === 'success' ? '✓' : '!'} {status.msg}
                                </div>
                            )}

                            <form onSubmit={handleSubmit} className="space-y-6">
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div className="space-y-2">
                                        <label htmlFor="name" className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Full Name *</label>
                                        <input
                                            id="name"
                                            type="text"
                                            required
                                            value={formData.name}
                                            onChange={handleChange}
                                            className="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all shadow-inner"
                                            placeholder="Abebe Bikila"
                                        />
                                    </div>
                                    <div className="space-y-2">
                                        <label htmlFor="email" className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email Address *</label>
                                        <input
                                            id="email"
                                            type="email"
                                            required
                                            value={formData.email}
                                            onChange={handleChange}
                                            className="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all shadow-inner"
                                            placeholder="abebe@example.com"
                                        />
                                    </div>
                                </div>

                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div className="space-y-2">
                                        <label htmlFor="phone" className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Phone Number</label>
                                        <input
                                            id="phone"
                                            type="tel"
                                            value={formData.phone}
                                            onChange={handleChange}
                                            className="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all shadow-inner"
                                            placeholder="+251 ..."
                                        />
                                    </div>
                                    <div className="space-y-2">
                                        <label htmlFor="subject" className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Subject</label>
                                        <input
                                            id="subject"
                                            type="text"
                                            value={formData.subject}
                                            onChange={handleChange}
                                            className="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all shadow-inner"
                                            placeholder="Woreda Inquiry"
                                        />
                                    </div>
                                </div>

                                <div className="space-y-2">
                                    <label htmlFor="message" className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Your Message *</label>
                                    <textarea
                                        id="message"
                                        required
                                        rows={6}
                                        value={formData.message}
                                        onChange={handleChange}
                                        className="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all shadow-inner resize-none"
                                        placeholder="How can we assist you today?"
                                    />
                                </div>

                                <button
                                    type="submit"
                                    disabled={loading}
                                    className="w-full bg-blue-600 text-white font-black py-5 rounded-2xl shadow-xl shadow-blue-200 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3 uppercase text-[10px] tracking-[0.2em] disabled:opacity-70"
                                >
                                    {loading ? 'Transmitting...' : (
                                        <>
                                            <FaPaperPlane className="text-xs" /> Send Official Inquiry
                                        </>
                                    )}
                                </button>
                            </form>
                        </div>
                    </div>

                    {/* Sidebar: Find Us & Map */}
                    <div className="lg:col-span-5 space-y-10">
                        <div className="bg-white p-10 rounded-[2.5rem] shadow-2xl border border-gray-100">
                            <h3 className="text-sm font-black text-blue-950 mb-10 flex items-center gap-3 uppercase tracking-widest">
                                <FaMapMarkerAlt /> Find Our Office
                            </h3>

                            <div className="space-y-10">
                                <div className="flex gap-6">
                                    <div className="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 shadow-sm">
                                        <FaMapMarkerAlt size={20} />
                                    </div>
                                    <div>
                                        <p className="font-black text-sm text-gray-900 italic tracking-tight mb-1">Administrative Headquarters</p>
                                        <p className="text-[11px] text-gray-500 font-medium leading-relaxed">{settings?.address || 'Kemise, Amhara Region, Ethiopia'}</p>
                                    </div>
                                </div>

                                <div className="flex gap-6">
                                    <div className="w-12 h-12 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0 shadow-sm">
                                        <FaPhone size={20} />
                                    </div>
                                    <div>
                                        <p className="font-black text-sm text-gray-900 italic tracking-tight mb-1">Public Relations Office</p>
                                        <p className="text-[11px] text-gray-500 font-medium leading-relaxed">{settings?.phone || '+251 33 111 2222'}</p>
                                    </div>
                                </div>

                                <div className="flex gap-6">
                                    <div className="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 shadow-sm">
                                        <FaEnvelope size={20} />
                                    </div>
                                    <div>
                                        <p className="font-black text-sm text-gray-900 italic tracking-tight mb-1">Electronic Correspondence</p>
                                        <p className="text-[11px] text-gray-500 font-medium leading-relaxed">{settings?.email || 'info@oromospecialzone.gov.et'}</p>
                                    </div>
                                </div>
                            </div>

                            <div className="mt-12 p-8 bg-gray-50 rounded-[2rem] border border-gray-100">
                                <h4 className="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                    <FaClock /> Operation Hours
                                </h4>
                                <p className="text-xs font-black text-gray-900 uppercase tracking-tight italic">
                                    {settings?.working_hours || 'Mon - Fri: 8:30 AM - 5:30 PM'}
                                </p>
                            </div>
                        </div>

                        {/* Interactive Map (Placeholder but styled) */}
                        <div className="bg-gray-900 rounded-[2.5rem] overflow-hidden shadow-2xl h-[350px] relative group">
                            {settings?.map_url ? (
                                <iframe
                                    src={settings.map_url}
                                    className="w-full h-full border-none opacity-80 group-hover:opacity-100 transition-opacity"
                                    loading="lazy"
                                />
                            ) : (
                                <div className="w-full h-full flex flex-col items-center justify-center text-center p-10">
                                    <FaMapMarkerAlt className="text-blue-500 text-5xl mb-4 animate-bounce" />
                                    <h4 className="text-white font-black italic tracking-tight text-lg mb-2">Our Precise Location</h4>
                                    <p className="text-gray-500 text-[10px] font-bold uppercase tracking-widest">Kemise Administration Hub</p>
                                </div>
                            )}
                        </div>

                        {/* Social Support */}
                        <div className="bg-gradient-to-r from-blue-600 to-blue-800 p-8 rounded-[2rem] text-white flex items-center justify-between">
                            <div>
                                <p className="text-[10px] font-black text-blue-200 uppercase tracking-widest mb-1 italic">Connect with us</p>
                                <p className="text-sm font-black italic tracking-tight">Social Media Portals</p>
                            </div>
                            <div className="flex gap-3">
                                {[FaFacebook, FaTwitter, FaYoutube].map((Icon, i) => (
                                    <a key={i} href="#" className="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center hover:bg-white hover:text-blue-600 transition-all">
                                        <Icon size={16} />
                                    </a>
                                ))}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    );
}
