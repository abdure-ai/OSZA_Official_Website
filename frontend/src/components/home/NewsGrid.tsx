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
                            <article key={item.id} className="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow border border-gray-100 flex flex-col group">
                                <div className="h-48 bg-gray-100 relative overflow-hidden">
                                    {item.thumbnail_url ? (
                                        <img
                                            src={getFileUrl(item.thumbnail_url)}
                                            alt={(item as any)[`title_${currentLang}`] || item.title_en}
                                            className="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                        />
                                    ) : (
                                        <div className="w-full h-full flex items-center justify-center text-gray-300">
                                            <svg className="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    )}
                                </div>

                                <div className="p-6 flex flex-col flex-grow">
                                    <div className="flex items-center gap-3 text-xs text-gray-500 mb-3">
                                        <span className="bg-blue-100 text-blue-800 px-2 py-0.5 rounded font-medium uppercase">{item.category}</span>
                                        <span>{new Date(item.published_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</span>
                                    </div>
                                    <h3 className="text-lg font-bold text-gray-900 mb-3 line-clamp-2 hover:text-primary transition-colors">
                                        <Link href={`/news/${item.id}`}>{(item as any)[`title_${currentLang}`] || item.title_en}</Link>
                                    </h3>
                                    <p className="text-gray-600 text-sm mb-4 line-clamp-3 flex-grow">
                                        {((item as any)[`content_${currentLang}`] || item.content_en).replace(/<[^>]*>?/gm, '').substring(0, 160)}...
                                    </p>
                                    <Link href={`/news/${item.id}`} className="text-primary font-medium hover:underline text-sm mt-auto">
                                        {t('read_full_story', 'Read Full Story')} →
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
