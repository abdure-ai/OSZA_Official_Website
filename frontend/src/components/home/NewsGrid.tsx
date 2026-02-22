'use client';
import Link from 'next/link';
import { useEffect, useState } from 'react';
import { fetchNews, NewsItem, getFileUrl } from '@/lib/api';
import { useTranslation } from 'react-i18next';

export default function NewsGrid() {
    const { t, i18n } = useTranslation();
    const currentLang = i18n.language;
    const [newsItems, setNewsItems] = useState<NewsItem[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const loadNews = async () => {
            try {
                const data = await fetchNews();
                setNewsItems(data.slice(0, 3)); // Show latest 3
            } catch (error) {
                console.error(error);
            } finally {
                setLoading(false);
            }
        };
        loadNews();
    }, []);

    if (loading) return <div className="text-center py-20">{t('loading_news', 'Loading news...')}</div>;

    return (
        <section className="py-16 bg-gray-50">
            <div className="container mx-auto px-4">
                <div className="flex justify-between items-end mb-10">
                    <div>
                        <h2 className="text-3xl font-bold text-gray-900 border-l-4 border-primary pl-4">{t('latest_news')}</h2>
                        <p className="text-gray-500 mt-2 ml-5">{t('news_subtitle', 'Stay informed about the current affairs.')}</p>
                    </div>
                    <Link href="/news" className="text-primary font-semibold hover:underline hidden md:block">
                        {t('view_all')} &rarr;
                    </Link>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    {newsItems.length > 0 ? (
                        newsItems.map((item) => (
                            <article key={item.id} className="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col h-full border border-gray-100">
                                {/* Image Placeholder */}
                                <div className="h-48 bg-gray-200 relative w-full flex items-center justify-center text-gray-400">
                                    <span className="text-4xl">🖼️</span>
                                    {item.thumbnail_url && (
                                        <img src={getFileUrl(item.thumbnail_url)} alt={(item as any)[`title_${currentLang}`] || item.title_en} className="w-full h-full object-cover absolute top-0 left-0" />
                                    )}
                                </div>

                                <div className="p-6 flex flex-col flex-grow">
                                    <div className="flex items-center text-sm text-gray-500 mb-3 space-x-4">
                                        <span className="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs font-medium uppercase">{item.category}</span>
                                        <span>{new Date(item.published_at).toLocaleDateString()}</span>
                                    </div>
                                    <h3 className="text-xl font-bold text-gray-900 mb-3 hover:text-primary transition-colors cursor-pointer">
                                        <Link href={`/news/${item.id}`}>{(item as any)[`title_${currentLang}`] || item.title_en}</Link>
                                    </h3>
                                    <p className="text-gray-600 mb-4 line-clamp-3 flex-grow">
                                        {((item as any)[`content_${currentLang}`] || item.content_en).substring(0, 150)}...
                                    </p>
                                    <Link href={`/news/${item.id}`} className="text-primary font-medium hover:underline inline-block mt-auto">
                                        {t('read_full_story', 'Read Full Story')}
                                    </Link>
                                </div>
                            </article>
                        ))
                    ) : (
                        <p className="col-span-3 text-center text-gray-500">No news updates available.</p>
                    )}
                </div>

                <div className="mt-8 text-center md:hidden">
                    <Link href="/news" className="text-primary font-semibold hover:underline">
                        {t('view_all')} &rarr;
                    </Link>
                </div>
            </div>
        </section>
    );
}
