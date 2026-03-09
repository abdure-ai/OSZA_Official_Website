import Link from 'next/link';
import { FaCalendarAlt, FaTag, FaChevronLeft, FaHome, FaNewspaper } from 'react-icons/fa';
import { fetchNewsById, fetchNews, getFileUrl } from '@/lib/api';
import { notFound } from 'next/navigation';

export async function generateMetadata({ params }: { params: { id: string } }) {
    const news = await fetchNewsById(params.id);
    if (!news) return { title: 'News Not Found' };
    return {
        title: `${news.title_en} | Oromo Special Zone Administration`,
        description: news.content_en.substring(0, 160),
    };
}

export default async function NewsDetailPage({ params }: { params: { id: string } }) {
    const news = await fetchNewsById(params.id);

    if (!news) {
        notFound();
    }

    // Fetch related news (same category)
    const allNews = await fetchNews(news.category);
    const related = allNews.filter(n => n.id !== news.id).slice(0, 3);

    return (
        <div className="bg-white min-h-screen pb-24">
            {/* ═══════════════════════════════════════════ BREADCRUMBS ══ */}
            <div className="bg-gray-50 border-b border-gray-100 py-4 mb-12">
                <div className="container mx-auto px-4">
                    <nav className="flex items-center gap-3 text-[10px] font-black uppercase tracking-widest text-gray-400">
                        <Link href="/" className="hover:text-blue-600 transition-colors flex items-center gap-1.5">
                            <FaHome /> Home
                        </Link>
                        <span>/</span>
                        <Link href="/news" className="hover:text-blue-600 transition-colors flex items-center gap-1.5">
                            <FaNewspaper /> News
                        </Link>
                        <span>/</span>
                        <span className="text-gray-900 truncate max-w-[200px] md:max-w-md italic">{news.title_en}</span>
                    </nav>
                </div>
            </div>

            <article className="container mx-auto px-4">
                <div className="max-w-4xl mx-auto">
                    {/* Header Meta */}
                    <div className="mb-10">
                        <div className="flex flex-wrap items-center gap-4 mb-6">
                            <span className="bg-blue-900 text-white px-5 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-xl shadow-blue-100">
                                {news.category}
                            </span>
                            <div className="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-gray-400">
                                <FaCalendarAlt className="text-blue-600/50" />
                                <span>{new Date(news.published_at).toLocaleDateString(undefined, { dateStyle: 'long' })}</span>
                            </div>
                        </div>

                        <h1 className="text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 mb-8 leading-[1.1] antialiased italic tracking-tight">
                            {news.title_en}
                        </h1>
                    </div>

                    {/* Featured Image */}
                    {news.thumbnail_url && (
                        <div className="mb-16 relative group">
                            <div className="absolute -inset-4 bg-blue-50 rounded-[3rem] -z-10 group-hover:bg-blue-100 transition-colors duration-500"></div>
                            <div className="rounded-[2.5rem] overflow-hidden shadow-2xl aspect-video border-8 border-white">
                                <img
                                    src={getFileUrl(news.thumbnail_url)}
                                    alt={news.title_en}
                                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                />
                            </div>
                        </div>
                    )}

                    {/* Content Body */}
                    <div className="prose prose-lg md:prose-xl max-w-none text-gray-600 leading-relaxed font-medium antialiased mb-20 whitespace-pre-wrap">
                        {news.content_en}
                    </div>

                    {/* Related Articles Section */}
                    {related.length > 0 && (
                        <div className="pt-20 border-t border-gray-100">
                            <div className="flex items-center justify-between mb-10">
                                <h2 className="text-2xl md:text-3xl font-black text-gray-900 italic tracking-tight">
                                    Related <span className="text-blue-600">Updates</span>
                                </h2>
                                <Link
                                    href="/news"
                                    className="text-[10px] font-black uppercase tracking-widest text-blue-600 hover:gap-3 transition-all flex items-center gap-2"
                                >
                                    View All News <span>→</span>
                                </Link>
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                                {related.map((post) => (
                                    <Link
                                        key={post.id}
                                        href={`/news/${post.id}`}
                                        className="group"
                                    >
                                        <div className="rounded-3xl overflow-hidden aspect-[4/3] bg-gray-100 mb-4 shadow-sm group-hover:shadow-xl transition-all duration-500">
                                            {post.thumbnail_url ? (
                                                <img
                                                    src={getFileUrl(post.thumbnail_url)}
                                                    alt={post.title_en}
                                                    className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                                />
                                            ) : (
                                                <div className="w-full h-full flex items-center justify-center text-gray-300">
                                                    <FaNewspaper className="text-4xl opacity-20" />
                                                </div>
                                            )}
                                        </div>
                                        <h3 className="font-bold text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-2 leading-tight">
                                            {post.title_en}
                                        </h3>
                                    </Link>
                                ))}
                            </div>
                        </div>
                    )}
                </div>
            </article>
        </div>
    );
}
