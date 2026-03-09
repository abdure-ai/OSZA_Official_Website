import { fetchGalleryCategories, getFileUrl } from '@/lib/api';
import GalleryPageClient from '@/components/GalleryPageClient';

export const metadata = {
    title: 'Photo Gallery | Oromo Special Zone Administration',
    description: 'Browse photos from across the Oromo Special Zone — events, infrastructure, agriculture, culture and more.',
};

export default async function GalleryPage() {
    const categories = await fetchGalleryCategories();

    return (
        <div className="min-h-screen bg-gray-50 pb-20">
            {/* ═══════════════════════════════════════════ GALLERY HERO ══ */}
            <section className="relative bg-blue-900 text-white py-20 md:py-32 overflow-hidden">
                <div className="absolute inset-0 z-0">
                    <div className="absolute inset-0 bg-gradient-to-r from-blue-950/80 to-blue-900/40 z-10"></div>
                    <img
                        src="https://images.unsplash.com/photo-1452421822248-d4c2b47f0c81?q=80&w=1920&auto=format&fit=crop"
                        className="w-full h-full object-cover scale-105 opacity-60"
                        alt="Gallery Hero"
                    />
                </div>

                <div className="container mx-auto px-4 relative z-20">
                    <div className="max-w-3xl">
                        <span className="inline-block px-4 py-1.5 bg-[#f5a623] text-blue-900 text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-6 shadow-xl">
                            Visual Archive
                        </span>
                        <h1 className="text-5xl md:text-7xl font-black mb-4 leading-none antialiased drop-shadow-2xl italic tracking-tight">
                            Photo <span className="text-[#f5a623]">Gallery</span>
                        </h1>
                        <p className="text-lg md:text-xl text-gray-200 font-medium opacity-90">
                            A window into the lives, landscapes, and development projects defining the Oromo Special Zone.
                        </p>
                    </div>
                </div>
                {/* Bottom fade */}
                <div className="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-gray-50 to-transparent"></div>
            </section>

            <div className="container mx-auto px-4 -mt-10 relative z-30">
                <GalleryPageClient initialCategories={categories.map(c => c.category)} />
            </div>
        </div>
    );
}
