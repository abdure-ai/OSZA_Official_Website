'use client';
import { useState, useCallback } from 'react';
import { GalleryCategory, GalleryItem, fetchGallery } from '@/lib/api';
import GallerySlideshow from '@/components/GallerySlideshow';
import { FaImages } from 'react-icons/fa';

const BACKEND_URL = process.env.NEXT_PUBLIC_BACKEND_URL || 'http://localhost:5000';

interface Props {
    categories: GalleryCategory[];
    woredaId: number;
}

export default function WoredaGalleryClient({ categories, woredaId }: Props) {
    const [slideshowImages, setSlideshowImages] = useState<GalleryItem[]>([]);
    const [slideshowIndex, setSlideshowIndex] = useState(0);
    const [loadingAlbum, setLoadingAlbum] = useState('');

    const openAlbum = useCallback(async (category: string) => {
        setLoadingAlbum(category);
        const images = await fetchGallery(woredaId, category);
        setSlideshowImages(images);
        setSlideshowIndex(0);
        setLoadingAlbum('');
    }, [woredaId]);

    const closeSlideshow = useCallback(() => setSlideshowImages([]), []);
    const onNext = useCallback(() => setSlideshowIndex(i => (i + 1) % slideshowImages.length), [slideshowImages.length]);
    const onPrev = useCallback(() => setSlideshowIndex(i => (i - 1 + slideshowImages.length) % slideshowImages.length), [slideshowImages.length]);

    if (categories.length === 0) {
        return (
            <div className="bg-white rounded-xl border border-gray-100 shadow-sm p-16 text-center">
                <FaImages className="text-4xl text-gray-300 mx-auto mb-3" />
                <p className="text-gray-400 font-medium">No photos have been added for this woreda yet</p>
            </div>
        );
    }

    return (
        <>
            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                {categories.map(cat => (
                    <button
                        key={cat.category}
                        onClick={() => openAlbum(cat.category)}
                        disabled={loadingAlbum === cat.category}
                        className="group relative rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 aspect-[4/3] bg-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500"
                    >
                        {cat.cover_url ? (
                            // eslint-disable-next-line @next/next/no-img-element
                            <img
                                src={`${BACKEND_URL}${cat.cover_url}`}
                                alt={cat.category}
                                className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            />
                        ) : (
                            <div className="w-full h-full bg-gradient-to-br from-green-700 to-green-500 flex items-center justify-center">
                                <FaImages className="text-white/30 text-5xl" />
                            </div>
                        )}
                        <div className="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent" />

                        {loadingAlbum === cat.category && (
                            <div className="absolute inset-0 bg-black/60 flex items-center justify-center">
                                <div className="w-8 h-8 border-4 border-white/30 border-t-white rounded-full animate-spin" />
                            </div>
                        )}

                        <div className="absolute bottom-0 left-0 right-0 p-4 text-left">
                            <p className="text-white font-bold text-lg">{cat.category}</p>
                            <p className="text-white/70 text-sm mt-0.5">{cat.count} {cat.count === 1 ? 'photo' : 'photos'}</p>
                        </div>
                    </button>
                ))}
            </div>

            {slideshowImages.length > 0 && (
                <GallerySlideshow
                    images={slideshowImages.map(i => ({ id: i.id, title: i.title, image_url: i.image_url }))}
                    currentIndex={slideshowIndex}
                    onClose={closeSlideshow}
                    onNext={onNext}
                    onPrev={onPrev}
                />
            )}
        </>
    );
}
