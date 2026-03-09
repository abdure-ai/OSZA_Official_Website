import { fetchNews } from '@/lib/api';
import NewsPageClient from '@/components/NewsPageClient';

export const metadata = {
    title: 'News & Updates | Oromo Special Zone Administration',
    description: 'Latest news, press releases, and official updates from the Oromo Special Zone administration.',
};

export default async function NewsPage() {
    const newsItems = await fetchNews();

    // Extract unique categories
    const categories = Array.from(new Set(newsItems.map(item => item.category))).filter(Boolean);

    return (
        <div className="bg-gray-50 min-h-screen pb-20">
            {/* ═══════════════════════════════════════════ NEWS HERO ══ */}
            <section className="relative bg-blue-900 text-white py-20 md:py-32 overflow-hidden">
                <div className="absolute inset-0 z-0">
                    <div className="absolute inset-0 bg-gradient-to-r from-blue-950/80 to-blue-900/40 z-10"></div>
                    <img
                        src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?q=80&w=1920&auto=format&fit=crop"
                        className="w-full h-full object-cover scale-105 opacity-60"
                        alt="News Hero"
                    />
                </div>

                <div className="container mx-auto px-4 relative z-20">
                    <div className="max-w-3xl">
                        <span className="inline-block px-4 py-1.5 bg-[#f5a623] text-blue-900 text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-6 shadow-xl">
                            Communications
                        </span>
                        <h1 className="text-5xl md:text-7xl font-black mb-4 leading-none antialiased drop-shadow-2xl italic tracking-tight">
                            Latest <span className="text-[#f5a623]">News</span>
                        </h1>
                        <p className="text-lg md:text-xl text-gray-200 font-medium opacity-90">
                            Stay informed with official updates, policy changes, and community stories from across the Zone.
                        </p>
                    </div>
                </div>
                {/* Bottom fade */}
                <div className="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-gray-50 to-transparent"></div>
            </section>

            <div className="container mx-auto px-4 relative z-30">
                <NewsPageClient initialNews={newsItems} categories={categories} />
            </div>
        </div>
    );
}
