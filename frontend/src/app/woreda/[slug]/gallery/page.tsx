import { fetchWoredaBySlug, fetchGalleryCategories } from '@/lib/api';
import { notFound } from 'next/navigation';
import WoredaGalleryClient from '@/components/WoredaGalleryClient';
import { FaImages } from 'react-icons/fa';

export async function generateMetadata({ params }: { params: { slug: string } }) {
    const woreda = await fetchWoredaBySlug(params.slug);
    if (!woreda) return {};
    return { title: `Gallery — ${woreda.name_en} | Oromo Special Zone` };
}

export default async function WoredaGalleryPage({ params }: { params: { slug: string } }) {
    const woreda = await fetchWoredaBySlug(params.slug);
    if (!woreda) notFound();

    const categories = await fetchGalleryCategories(woreda.id);

    return (
        <div className="container mx-auto px-4 py-10 max-w-5xl space-y-8">
            {/* Page Title */}
            <div className="border-b border-gray-200 pb-6 flex items-center gap-3">
                <div className="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
                    <FaImages className="text-green-700" />
                </div>
                <div>
                    <h2 className="text-3xl font-bold text-gray-900">Photo Gallery</h2>
                    <p className="text-gray-500 mt-1">{woreda.name_en} Woreda</p>
                </div>
            </div>

            <WoredaGalleryClient categories={categories} woredaId={woreda.id} />
        </div>
    );
}
