import Link from 'next/link';
import { FaCalendarAlt, FaTag, FaChevronLeft } from 'react-icons/fa';
import { fetchNewsById, getFileUrl } from '@/lib/api';
import { notFound } from 'next/navigation';

export async function generateMetadata({ params }: { params: { id: string } }) {
    const news = await fetchNewsById(params.id);
    if (!news) return { title: 'News Not Found' };
    return {
        title: `${news.title_en} | Oromo Special Zone`,
        description: news.content_en.substring(0, 160),
    };
}

export default async function NewsDetailPage({ params }: { params: { id: string } }) {
    const news = await fetchNewsById(params.id);

    if (!news) {
        notFound();
    }

    return (
        <div className="bg-gray-50 min-h-screen pb-20">
            {/* Header / Breadcrumb */}
            <div className="bg-white border-b py-6">
                <div className="container mx-auto px-4">
                    <Link
                        href="/news"
                        className="inline-flex items-center text-primary font-medium hover:underline mb-4 gap-2"
                    >
                        <FaChevronLeft size={14} /> Back to News
                    </Link>
                </div>
            </div>

            <article className="container mx-auto px-4 mt-8">
                <div className="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    {/* News Image */}
                    {news.thumbnail_url && (
                        <div className="w-full h-[400px] relative">
                            <img
                                src={getFileUrl(news.thumbnail_url)}
                                alt={news.title_en}
                                className="w-full h-full object-cover"
                            />
                        </div>
                    )}

                    <div className="p-8 md:p-12">
                        {/* Meta */}
                        <div className="flex flex-wrap items-center gap-6 text-sm text-gray-500 mb-6 border-b pb-6">
                            <div className="flex items-center gap-2">
                                <FaCalendarAlt className="text-primary" />
                                <span>{new Date(news.published_at).toLocaleDateString(undefined, { dateStyle: 'long' })}</span>
                            </div>
                            <div className="flex items-center gap-2">
                                <FaTag className="text-primary" />
                                <span className="capitalize">{news.category.replace('_', ' ')}</span>
                            </div>
                        </div>

                        {/* Content */}
                        <h1 className="text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 mb-8 leading-tight">
                            {news.title_en}
                        </h1>

                        <div className="prose prose-lg max-w-none text-gray-700 leading-relaxed whitespace-pre-wrap">
                            {news.content_en}
                        </div>
                    </div>
                </div>
            </article>
        </div>
    );
}
