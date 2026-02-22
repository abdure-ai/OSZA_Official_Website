import Link from 'next/link';
import { FaCalendarAlt, FaTag } from 'react-icons/fa';
import { fetchNews, getFileUrl } from '@/lib/api';

export const metadata = {
    title: 'News & Updates | Oromo Special Zone',
    description: 'Latest news, press releases, and updates from the zone administration',
};

export default async function NewsPage() {
    const newsItems = await fetchNews();

    return (
        <div className="bg-gray-50 min-h-screen pb-16">
            <div className="bg-white border-b py-12 mb-8">
                <div className="container mx-auto px-4">
                    <h1 className="text-3xl md:text-4xl font-bold text-gray-900 mb-3">News & Updates</h1>
                    <p className="text-gray-600">Stay informed about the latest developments in our zone.</p>
                </div>
            </div>

            <div className="container mx-auto px-4">
                <div className="grid grid-cols-1 lg:grid-cols-4 gap-8">

                    {/* Main Content */}
                    <div className="lg:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-6">
                        {newsItems.length > 0 ? (
                            newsItems.map((item) => (
                                <article key={item.id} className="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow border border-gray-100 flex flex-col">
                                    <div className="h-48 bg-gray-200 relative w-full flex items-center justify-center text-gray-400">
                                        <span className="text-3xl">News Image</span>
                                        {item.thumbnail_url && (
                                            <img src={getFileUrl(item.thumbnail_url)} alt={item.title_en} className="w-full h-full object-cover absolute top-0 left-0" />
                                        )}
                                    </div>
                                    <div className="p-6 flex flex-col flex-grow">
                                        <div className="flex items-center text-sm text-gray-500 mb-3 gap-4">
                                            <span className="flex items-center gap-1"><FaCalendarAlt /> {new Date(item.published_at).toLocaleDateString()}</span>
                                            <span className="flex items-center gap-1 text-primary"><FaTag /> {item.category}</span>
                                        </div>
                                        <h2 className="text-xl font-bold text-gray-900 mb-3 hover:text-primary transition-colors">
                                            <Link href={`/news/${item.id}`}>{item.title_en}</Link>
                                        </h2>
                                        <p className="text-gray-600 line-clamp-3 mb-4 flex-grow">{item.content_en.substring(0, 150)}...</p>
                                        <Link href={`/news/${item.id}`} className="text-primary font-medium hover:underline mt-auto inline-block">
                                            Read More &rarr;
                                        </Link>
                                    </div>
                                </article>
                            ))
                        ) : (
                            <p className="col-span-2 text-center text-gray-500 py-10">No news available at the moment. Please check back later.</p>
                        )}
                    </div>

                    {/* Sidebar Filters */}
                    <aside className="space-y-8">
                        <div className="bg-white p-6 rounded-lg shadow-sm border border-gray-100 sticky top-24">
                            <h3 className="font-bold text-lg mb-4 text-gray-900 border-b pb-2">Categories</h3>
                            <ul className="space-y-2">
                                {['All News', 'Development', 'Administration', 'Health', 'Education', 'Agriculture', 'Sports'].map((cat, i) => (
                                    <li key={i}>
                                        <button className="text-gray-600 hover:text-primary hover:bg-blue-50 w-full text-left px-3 py-2 rounded transition-colors flex justify-between items-center group">
                                            <span>{cat}</span>
                                        </button>
                                    </li>
                                ))}
                            </ul>
                        </div>
                    </aside>

                </div>
            </div>
        </div>
    );
}
