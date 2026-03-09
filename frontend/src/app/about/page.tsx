import { fetchLeadership, getFileUrl, Leadership } from '@/lib/api';
import { FaBullseye, FaEye, FaHistory, FaUsers, FaAward, FaBuilding } from 'react-icons/fa';

export const metadata = {
    title: 'About Us | Oromo Special Zone Administration',
    description: 'Learn about our history, mission, vision, and the leadership of the Oromo Special Zone Administration.',
};

export default async function AboutPage() {
    const leadership = await fetchLeadership();

    return (
        <div className="bg-white min-h-screen">
            {/* ═══════════════════════════════════════════ ABOUT HERO ══ */}
            <section className="relative bg-blue-900 text-white py-24 md:py-32 overflow-hidden">
                <div className="absolute inset-0 z-0">
                    <div className="absolute inset-0 bg-gradient-to-r from-blue-950/80 to-blue-900/40 z-10"></div>
                    <img
                        src="https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=1920&auto=format&fit=crop"
                        className="w-full h-full object-cover scale-105 opacity-40"
                        alt="About Hero"
                    />
                    <div className="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-white to-transparent z-20"></div>
                </div>

                <div className="container mx-auto px-4 relative z-30">
                    <div className="max-w-3xl">
                        <span className="inline-block px-4 py-1.5 bg-[#f5a623] text-blue-900 text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-6 shadow-xl">
                            Our Identity
                        </span>
                        <h1 className="text-5xl md:text-7xl font-black mb-6 leading-none antialiased drop-shadow-2xl italic tracking-tight">
                            About <span className="text-[#f5a623]">the Administration</span>
                        </h1>
                        <p className="text-xl text-gray-200 font-medium opacity-90 leading-relaxed">
                            A gateway to growth, culture, and administrative excellence in the heart of Oromia.
                        </p>
                    </div>
                </div>
            </section>

            {/* ═══════════════════════════════════════════ MISSION & VISION ══ */}
            <section className="py-20 -mt-20 relative z-40">
                <div className="container mx-auto px-4">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
                        {/* Mission */}
                        <div className="bg-white p-10 md:p-12 rounded-[2.5rem] shadow-2xl border-t-8 border-primary hover:translate-y-[-10px] transition-all duration-500 group">
                            <div className="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mb-8 border border-primary/5 group-hover:bg-primary group-hover:text-white transition-colors duration-500">
                                <FaBullseye className="text-3xl" />
                            </div>
                            <h2 className="text-3xl font-black text-gray-900 mb-6 italic tracking-tight">Our Mission</h2>
                            <p className="text-gray-600 text-lg leading-relaxed antialiased">
                                To ensure sustainable development, maintain peace and security, and provide efficient public services to the citizens of the Oromo Special Zone through transparent and accountable governance.
                            </p>
                        </div>

                        {/* Vision */}
                        <div className="bg-white p-10 md:p-12 rounded-[2.5rem] shadow-2xl border-t-8 border-accent hover:translate-y-[-10px] transition-all duration-500 group">
                            <div className="w-16 h-16 bg-accent/10 rounded-2xl flex items-center justify-center mb-8 border border-accent/5 group-hover:bg-accent group-hover:text-blue-900 transition-colors duration-500">
                                <FaEye className="text-3xl" />
                            </div>
                            <h2 className="text-3xl font-black text-gray-900 mb-6 italic tracking-tight">Our Vision</h2>
                            <p className="text-gray-600 text-lg leading-relaxed antialiased">
                                To become a model of economic prosperity, social harmony, and administrative transparency, making the Oromo Special Zone the most livable and investment-friendly region.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {/* ═══════════════════════════════════════════ HISTORY ══ */}
            <section className="py-24 bg-gray-50 overflow-hidden">
                <div className="container mx-auto px-4">
                    <div className="flex flex-col lg:flex-row gap-16 items-center">
                        <div className="lg:w-1/2 relative">
                            <div className="relative z-10 rounded-[3rem] overflow-hidden shadow-2xl border-8 border-white transform -rotate-3 hover:rotate-0 transition-transform duration-700">
                                <img
                                    src="https://images.unsplash.com/photo-1541963463532-d68292c34b19?q=80&w=800&auto=format&fit=crop"
                                    alt="Administrative Building"
                                    className="w-full aspect-[4/5] object-cover"
                                />
                            </div>
                            {/* Decorative blur */}
                            <div className="absolute -top-10 -left-10 w-64 h-64 bg-primary/10 rounded-full blur-3xl -z-0" />
                            <div className="absolute -bottom-10 -right-10 w-48 h-48 bg-accent/10 rounded-full blur-3xl -z-0" />
                        </div>

                        <div className="lg:w-1/2">
                            <div className="inline-flex items-center gap-2 text-primary text-xs font-black uppercase tracking-[0.3em] mb-4">
                                <FaHistory /> Historical Legacy
                            </div>
                            <h2 className="text-4xl md:text-5xl font-black text-gray-900 mb-8 leading-tight italic tracking-tight">
                                A Legacy of <span className="text-primary italic">Service</span> & Growth
                            </h2>
                            <div className="space-y-6 text-gray-600 text-lg leading-relaxed antialiased">
                                <p>
                                    Established with the goal of bringing administration closer to the people, the Oromo Special Zone has grown from a visionary concept into a thriving administrative hub.
                                </p>
                                <p>
                                    Our heritage is deeply rooted in the values of the Gadaa system—fairness, representation, and community-led development. Today, we bridge this ancestral wisdom with modern governance to serve over 1.2 million citizens.
                                </p>
                                <div className="grid grid-cols-2 gap-6 pt-8">
                                    <div className="p-6 bg-white rounded-2xl shadow-sm border border-gray-100 italic">
                                        <div className="text-3xl font-black text-primary mb-1 tracking-tighter">1994</div>
                                        <div className="text-xs font-bold text-gray-400 uppercase tracking-widest">Zone Formation</div>
                                    </div>
                                    <div className="p-6 bg-white rounded-2xl shadow-sm border border-gray-100 italic">
                                        <div className="text-3xl font-black text-primary mb-1 tracking-tighter">6+</div>
                                        <div className="text-xs font-bold text-gray-400 uppercase tracking-widest">Major Woredas</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* ═══════════════════════════════════════════ LEADERSHIP ══ */}
            <section className="py-24 bg-white">
                <div className="container mx-auto px-4">
                    <div className="text-center mb-20">
                        <span className="bg-blue-100 text-blue-900 text-[10px] font-black uppercase tracking-[0.2em] px-4 py-1.5 rounded-full mb-6 inline-block">
                            Our Governance
                        </span>
                        <h2 className="text-4xl md:text-6xl font-black text-gray-900 mb-6 italic tracking-tight">
                            The Leadership <span className="text-primary italic underline decoration-accent/30 underline-offset-8">Cabinet</span>
                        </h2>
                        <p className="text-gray-500 max-w-2xl mx-auto text-lg">
                            Dedicated professionals committed to the prosperity and advancement of our community.
                        </p>
                    </div>

                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        {leadership.map((leader: Leadership) => (
                            <div key={leader.id} className="group">
                                <div className="relative mb-6 overflow-hidden rounded-[2.5rem] shadow-xl aspect-[4/5] bg-gray-100">
                                    {leader.photo_url ? (
                                        <img
                                            src={getFileUrl(leader.photo_url)}
                                            alt={leader.name_en}
                                            className="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700 group-hover:scale-105"
                                        />
                                    ) : (
                                        <div className="w-full h-full flex items-center justify-center text-gray-300">
                                            <FaUsers className="text-6xl" />
                                        </div>
                                    )}
                                    <div className="absolute inset-x-4 bottom-4 bg-white/90 backdrop-blur-md p-6 rounded-3xl shadow-2xl translate-y-4 group-hover:translate-y-0 transition-transform duration-500 border border-white/20">
                                        <h3 className="text-xl font-black text-gray-900 leading-tight mb-1 truncate">
                                            {leader.name_en}
                                        </h3>
                                        <p className="text-primary font-bold text-xs uppercase tracking-widest">
                                            {leader.position_en}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>
        </div>
    );
}
