import { fetchWoredaBySlug } from '@/lib/api';
import { notFound } from 'next/navigation';
import { FaPhone, FaEnvelope, FaMapMarkerAlt, FaClock } from 'react-icons/fa';

export async function generateMetadata({ params }: { params: { slug: string } }) {
    const woreda = await fetchWoredaBySlug(params.slug);
    return { title: `Contact — ${woreda?.name_en || 'Woreda'} | Oromo Special Zone` };
}

export default async function WoredaContact({ params }: { params: { slug: string } }) {
    const woreda = await fetchWoredaBySlug(params.slug);
    if (!woreda) notFound();

    return (
        <div className="container mx-auto px-4 py-10 max-w-5xl">
            <div className="border-b border-gray-200 pb-6 mb-8">
                <h2 className="text-3xl font-bold text-gray-900">Contact Us</h2>
                <p className="text-gray-500 mt-2">Reach the {woreda.name_en} Woreda Administration</p>
            </div>

            <div className="grid md:grid-cols-2 gap-8">
                {/* Contact Cards */}
                <div className="space-y-4">
                    {woreda.contact_phone && (
                        <a href={`tel:${woreda.contact_phone}`}
                            className="flex items-center gap-4 bg-white border border-gray-100 shadow-sm rounded-xl p-5 hover:shadow-md transition group">
                            <div className="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 group-hover:bg-green-200 transition">
                                <FaPhone className="text-green-700 text-lg" />
                            </div>
                            <div>
                                <p className="text-xs font-semibold text-gray-400 uppercase tracking-wide">Phone</p>
                                <p className="text-gray-900 font-bold mt-0.5">{woreda.contact_phone}</p>
                                <p className="text-xs text-green-700 mt-0.5">Click to call</p>
                            </div>
                        </a>
                    )}
                    {woreda.contact_email && (
                        <a href={`mailto:${woreda.contact_email}`}
                            className="flex items-center gap-4 bg-white border border-gray-100 shadow-sm rounded-xl p-5 hover:shadow-md transition group">
                            <div className="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-200 transition">
                                <FaEnvelope className="text-blue-700 text-lg" />
                            </div>
                            <div>
                                <p className="text-xs font-semibold text-gray-400 uppercase tracking-wide">Email</p>
                                <p className="text-gray-900 font-bold mt-0.5">{woreda.contact_email}</p>
                                <p className="text-xs text-blue-700 mt-0.5">Click to send email</p>
                            </div>
                        </a>
                    )}
                    {woreda.address_en && (
                        <div className="flex items-center gap-4 bg-white border border-gray-100 shadow-sm rounded-xl p-5">
                            <div className="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                                <FaMapMarkerAlt className="text-amber-700 text-lg" />
                            </div>
                            <div>
                                <p className="text-xs font-semibold text-gray-400 uppercase tracking-wide">Address</p>
                                <p className="text-gray-900 font-bold mt-0.5">{woreda.address_en}</p>
                            </div>
                        </div>
                    )}
                    {/* Working Hours */}
                    <div className="flex items-start gap-4 bg-white border border-gray-100 shadow-sm rounded-xl p-5">
                        <div className="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                            <FaClock className="text-purple-700 text-lg" />
                        </div>
                        <div>
                            <p className="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Working Hours</p>
                            <div className="space-y-1 text-sm text-gray-700">
                                <div className="flex justify-between gap-6">
                                    <span>Monday – Thursday</span>
                                    <span className="font-semibold">8:30 AM – 5:30 PM</span>
                                </div>
                                <div className="flex justify-between gap-6">
                                    <span>Friday</span>
                                    <span className="font-semibold">8:30 AM – 11:30 AM</span>
                                </div>
                                <div className="flex justify-between gap-6 border-t pt-1 mt-1">
                                    <span>Saturday – Sunday</span>
                                    <span className="font-semibold text-red-500">Closed</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Contact Form */}
                <div className="bg-white border border-gray-100 shadow-sm rounded-xl p-6">
                    <h3 className="text-lg font-bold text-gray-800 mb-4">Send a Message</h3>
                    <form className="space-y-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input
                                type="text"
                                className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                                placeholder="Your name"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input
                                type="email"
                                className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                                placeholder="your@email.com"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                            <input
                                type="text"
                                className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                                placeholder="What is it about?"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Message</label>
                            <textarea
                                rows={4}
                                className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                                placeholder="Write your message here..."
                            />
                        </div>
                        <button
                            type="submit"
                            className="w-full bg-green-700 text-white py-2.5 rounded-lg font-semibold hover:bg-green-800 transition"
                        >
                            Send Message
                        </button>
                        <p className="text-xs text-gray-400 text-center">
                            We typically respond within 2–3 business days.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    );
}
