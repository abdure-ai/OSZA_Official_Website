import { fetchGalleryCategories } from '@/lib/api';
import GalleryPageClient from '@/components/GalleryPageClient';
import { FaImages } from 'react-icons/fa';

export const metadata = {
    title: 'Photo Gallery | Oromo Special Zone Administration',
    description: 'Browse photos from across the Oromo Special Zone — events, infrastructure, agriculture, culture and more.',
};

export default async function GalleryPage() {
    const categories = await fetchGalleryCategories();

    return (
        <div className="min-h-screen bg-gray-50">
            {/* Header */}
            <div className="bg-gradient-to-r from-green-800 to-green-600 text-white py-16">
                <div className="container mx-auto px-4 text-center">
                    <div className="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mx-auto mb-4">
                        <FaImages className="text-white text-3xl" />
                    </div>
                    <h1 className="text-4xl font-bold">Photo Gallery</h1>
                    <p className="text-green-200 mt-2 text-lg">
                        Visual stories from across the Oromo Special Zone
                    </p>
                    <p className="text-green-300 text-sm mt-1">
                        {categories.length > 0
                            ? `${categories.length} albums · ${categories.reduce((sum, c) => sum + Number(c.count), 0)} photos`
                            : 'No photos yet'}
                    </p>
                </div>
            </div>

            {/* Albums Grid */}
            <div className="container mx-auto px-4 py-12">
                <GalleryPageClient categories={categories} />
            </div>
        </div>
    );
}
