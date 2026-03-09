'use client';
import { useState, useEffect, useCallback } from 'react';
import { fetchGallery, GalleryItem, getFileUrl } from '@/lib/api';
import GallerySlideshow from '@/components/GallerySlideshow';
import { FaImages, FaSearch } from 'react-icons/fa';
import { useTranslation } from 'react-i18next';

interface Props {
    initialCategories: string[];
}

export default function GalleryPageClient({ initialCategories }: Props) {
    const { t } = useTranslation();
    const [activeCategory, setActiveCategory] = useState<string>('All');
    const [images, setImages] = useState<GalleryItem[]>([]);
    const [loading, setLoading] = useState(true);
    const [slideshowIndex, setSlideshowIndex] = useState<number | null>(null);

    const loadImages = useCallback(async () => {
        setLoading(true);
        const fetched = await fetchGallery(undefined, activeCategory === 'All' ? undefined : activeCategory);
        setImages(fetched);
        setLoading(false);
    }, [activeCategory]);

    useEffect(() => {
        loadImages();
    }, [loadImages]);

    const openSlideshow = (index: number) => setSlideshowIndex(index);
    const closeSlideshow = () => setSlideshowIndex(null);
    const onNext = () => setSlideshowIndex(prev => (prev !== null ? (prev + 1) % images.length : null));
    const onPrev = () => setSlideshowIndex(prev => (prev !== null ? (prev - 1 + images.length) % images.length : null));

    return (
        <div className="max-w-[1440px] mx-auto">
            {/* Category Tabs Refined */}
            <div className="flex flex-wrap justify-center gap-3 mb-16 relative z-30">
                <button
                    onClick={() => setActiveCategory('All')}
                    className={`px-8 py-3 rounded-full text-xs font-black uppercase tracking-widest border transition-all shadow-xl ${activeCategory === 'All' ? 'bg-blue-900 text-white border-blue-900 shadow-blue-200' : 'bg-white text-gray-400 border-gray-100 hover:border-blue-900 hover:text-blue-900'}`}
                >
                    All
                </button>
                {initialCategories.map(cat => (
                    <button
                        key={cat}
                        onClick={() => setActiveCategory(cat)}
                        className={`px-8 py-3 rounded-full text-xs font-black uppercase tracking-widest border transition-all shadow-xl ${activeCategory === cat ? 'bg-blue-900 text-white border-blue-900 shadow-blue-200' : 'bg-white text-gray-400 border-gray-100 hover:border-blue-900 hover:text-blue-900'}`}
                    >
                        {cat}
                    </button>
                ))}
            </div>

            {/* Photo Grid with Masonry-like effect */}
            {loading ? (
                <div className="flex flex-col items-center justify-center py-20 grayscale opacity-50">
                    <div className="w-12 h-12 border-4 border-blue-900/20 border-t-blue-900 rounded-full animate-spin mb-4" />
                    <p className="text-blue-900 font-black text-xs uppercase tracking-widest">Loading Visuals...</p>
                </div>
            ) : images.length > 0 ? (
                <div className="columns-2 sm:columns-3 lg:columns-4 gap-4 space-y-4">
                    {images.map((item, index) => (
                        <div
                            key={item.id}
                            className="break-inside-avoid group cursor-pointer relative rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500"
                            onClick={() => openSlideshow(index)}
                        >
                            <img
                                src={getFileUrl(item.image_url)}
                                alt={item.title}
                                className="w-full object-cover rounded-2xl group-hover:scale-105 transition-transform duration-700"
                            />

                            {/* Interactive Overlay */}
                            <div className="absolute inset-0 bg-blue-900/0 group-hover:bg-blue-900/20 transition-all duration-500 flex items-center justify-center">
                                <FaSearch className="text-white text-2xl opacity-0 group-hover:opacity-100 transform scale-50 group-hover:scale-100 transition-all duration-500" />
                            </div>

                            {/* Info Overlay */}
                            <div className="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-5 translate-y-full group-hover:translate-y-0 transition-transform duration-500">
                                <p className="text-white text-xs font-black uppercase tracking-widest mb-1">{item.category}</p>
                                <h3 className="text-white font-bold text-sm leading-tight line-clamp-2">{item.title}</h3>
                            </div>
                        </div>
                    ))}
                </div>
            ) : (
                <div className="text-center py-32 border-2 border-dashed border-gray-200 rounded-[3rem]">
                    <FaImages className="text-6xl text-gray-100 mx-auto mb-6" />
                    <p className="text-gray-400 font-black text-xs uppercase tracking-widest">No Captures in this Category</p>
                </div>
            )}

            {/* Slideshow Lightbox */}
            {slideshowIndex !== null && (
                <GallerySlideshow
                    images={images.map(i => ({ id: i.id, title: i.title, image_url: i.image_url }))}
                    currentIndex={slideshowIndex}
                    onClose={closeSlideshow}
                    onNext={onNext}
                    onPrev={onPrev}
                />
            )}
        </div>
    );
}

