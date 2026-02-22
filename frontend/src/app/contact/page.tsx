'use client';

import { useEffect, useState } from 'react';
import { FaPhone, FaEnvelope, FaMapMarkerAlt, FaClock, FaPaperPlane, FaFacebook, FaTwitter, FaLinkedin, FaYoutube } from 'react-icons/fa';
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
        <div className="bg-gray-50 pb-20">
            {/* Header Hero */}
            <div className="bg-gradient-to-br from-green-900 via-green-800 to-green-700 text-white py-20 px-4 text-center">
                <div className="container mx-auto">
                    <h1 className="text-4xl md:text-5xl font-extrabold mb-4 animate-fade-in">Contact Us</h1>
                    <p className="text-xl text-green-100 max-w-2xl mx-auto">
                        Have questions? We're here to help. Reach out to the Oromo Special Zone Administration.
                    </p>
                </div>
            </div>

            <div className="container mx-auto px-4 -mt-10">
                <div className="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 lg:flex">
                    {/* Contact Info Sidebar */}
                    <div className="bg-green-800 text-white p-10 lg:w-1/3 xl:p-14">
                        <h2 className="text-2xl font-bold mb-8">Contact Information</h2>
                        <div className="space-y-8">
                            <div className="flex items-start gap-4">
                                <div className="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0">
                                    <FaPhone className="text-green-300" />
                                </div>
                                <div>
                                    <p className="text-white/60 text-sm font-medium uppercase tracking-wider mb-1">Call Us</p>
                                    <p className="font-bold text-lg">{settings?.phone || '+251 XXX XXX XXX'}</p>
                                </div>
                            </div>

                            <div className="flex items-start gap-4">
                                <div className="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0">
                                    <FaEnvelope className="text-green-300" />
                                </div>
                                <div>
                                    <p className="text-white/60 text-sm font-medium uppercase tracking-wider mb-1">Email Us</p>
                                    <p className="font-bold text-lg">{settings?.email || 'info@osza.gov.et'}</p>
                                </div>
                            </div>

                            <div className="flex items-start gap-4">
                                <div className="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0">
                                    <FaMapMarkerAlt className="text-green-300" />
                                </div>
                                <div>
                                    <p className="text-white/60 text-sm font-medium uppercase tracking-wider mb-1">Visit Us</p>
                                    <p className="text-sm leading-relaxed">{settings?.address || 'Oromo Special Zone Administration'}</p>
                                </div>
                            </div>

                            <div className="flex items-start gap-4">
                                <div className="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0">
                                    <FaClock className="text-green-300" />
                                </div>
                                <div>
                                    <p className="text-white/60 text-sm font-medium uppercase tracking-wider mb-1">Working Hours</p>
                                    <p className="text-sm opacity-90">{settings?.working_hours || 'Mon - Fri: 8:30 AM - 5:30 PM'}</p>
                                </div>
                            </div>
                        </div>

                        {/* Social Links */}
                        <div className="mt-16 pt-8 border-t border-white/10">
                            <p className="text-white/60 text-sm mb-4 uppercase tracking-widest">Follow Us</p>
                            <div className="flex gap-4">
                                {settings?.facebook_url && (
                                    <a href={settings.facebook_url} target="_blank" className="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#1877F2] transition"><FaFacebook /></a>
                                )}
                                {settings?.twitter_url && (
                                    <a href={settings.twitter_url} target="_blank" className="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#1DA1F2] transition"><FaTwitter /></a>
                                )}
                                {settings?.linkedin_url && (
                                    <a href={settings.linkedin_url} target="_blank" className="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#0A66C2] transition"><FaLinkedin /></a>
                                )}
                                {settings?.youtube_url && (
                                    <a href={settings.youtube_url} target="_blank" className="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#FF0000] transition"><FaYoutube /></a>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* Contact Form */}
                    <div className="p-10 lg:w-2/3 xl:p-14">
                        <h2 className="text-2xl font-bold text-gray-900 mb-6">Send us a Message</h2>

                        {status.type && (
                            <div className={`mb-6 p-4 rounded-xl text-sm font-medium ${status.type === 'success' ? 'bg-green-50 text-green-800 border border-green-100' : 'bg-red-50 text-red-800 border border-red-100'
                                }`}>
                                {status.msg}
                            </div>
                        )}

                        <form onSubmit={handleSubmit} className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div className="space-y-2">
                                <label htmlFor="name" className="text-sm font-bold text-gray-700">Full Name</label>
                                <input
                                    id="name"
                                    type="text"
                                    required
                                    value={formData.name}
                                    onChange={handleChange}
                                    className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:bg-white transition"
                                    placeholder="Enter your name"
                                />
                            </div>

                            <div className="space-y-2">
                                <label htmlFor="email" className="text-sm font-bold text-gray-700">Email Address</label>
                                <input
                                    id="email"
                                    type="email"
                                    required
                                    value={formData.email}
                                    onChange={handleChange}
                                    className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:bg-white transition"
                                    placeholder="Enter your email"
                                />
                            </div>

                            <div className="space-y-2">
                                <label htmlFor="phone" className="text-sm font-bold text-gray-700">Phone Number (Optional)</label>
                                <input
                                    id="phone"
                                    type="tel"
                                    value={formData.phone}
                                    onChange={handleChange}
                                    className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:bg-white transition"
                                    placeholder="+251 ..."
                                />
                            </div>

                            <div className="space-y-2">
                                <label htmlFor="subject" className="text-sm font-bold text-gray-700">Subject</label>
                                <input
                                    id="subject"
                                    type="text"
                                    value={formData.subject}
                                    onChange={handleChange}
                                    className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:bg-white transition"
                                    placeholder="What's this about?"
                                />
                            </div>

                            <div className="space-y-2 md:col-span-2">
                                <label htmlFor="message" className="text-sm font-bold text-gray-700">Message</label>
                                <textarea
                                    id="message"
                                    required
                                    rows={5}
                                    value={formData.message}
                                    onChange={handleChange}
                                    className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:bg-white transition"
                                    placeholder="Tell us how we can help..."
                                />
                            </div>

                            <div className="md:col-span-2 pt-4">
                                <button
                                    type="submit"
                                    disabled={loading}
                                    className="w-full bg-gradient-to-r from-green-800 to-green-600 text-white font-bold py-4 rounded-xl shadow-lg border-b-4 border-green-900 hover:scale-[1.01] active:scale-[0.99] active:border-b-0 transition-all flex items-center justify-center gap-2 disabled:opacity-70"
                                >
                                    {loading ? (
                                        <span className="flex items-center gap-2">
                                            <div className="w-5 h-5 border-2 border-white/20 border-t-white rounded-full animate-spin" />
                                            Sending...
                                        </span>
                                    ) : (
                                        <>
                                            <FaPaperPlane className="text-sm" />
                                            Send Message
                                        </>
                                    )}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {/* Map */}
                <div className="mt-12 bg-white rounded-3xl overflow-hidden shadow-lg border border-gray-100 h-[500px]">
                    {settings?.map_url ? (
                        <iframe
                            src={settings.map_url}
                            width="100%"
                            height="100%"
                            style={{ border: 0 }}
                            allowFullScreen
                            loading="lazy"
                            referrerPolicy="no-referrer-when-downgrade"
                        />
                    ) : (
                        <div className="h-full w-full bg-green-50 flex items-center justify-center relative">
                            <div className="text-center z-10 px-4">
                                <div className="bg-white/90 backdrop-blur p-6 rounded-2xl shadow-xl max-w-sm inline-block">
                                    <FaMapMarkerAlt className="text-green-600 text-4xl mx-auto mb-3" />
                                    <h3 className="font-bold text-gray-900">Our Location</h3>
                                    <p className="text-sm text-gray-600 mt-1">{settings?.address || 'Oromo Special Zone Administration'}</p>
                                </div>
                            </div>
                            <div className="absolute inset-0 opacity-10 pointer-events-none" style={{ backgroundImage: 'radial-gradient(#065f46 1px, transparent 1px)', backgroundSize: '20px 20px' }} />
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}
