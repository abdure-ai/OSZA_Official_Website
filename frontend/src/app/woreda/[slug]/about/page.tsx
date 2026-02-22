import { fetchWoredaBySlug } from '@/lib/api';
import { notFound } from 'next/navigation';
import { FaMapMarkerAlt, FaUsers, FaRulerCombined, FaCalendarAlt, FaUserTie, FaBuilding } from 'react-icons/fa';

const BACKEND_URL = process.env.NEXT_PUBLIC_BACKEND_URL || 'http://localhost:5000';

export async function generateMetadata({ params }: { params: { slug: string } }) {
    const woreda = await fetchWoredaBySlug(params.slug);
    if (!woreda) return {};
    return { title: `About ${woreda.name_en} | Oromo Special Zone` };
}

export default async function WoredaAbout({ params }: { params: { slug: string } }) {
    const woreda = await fetchWoredaBySlug(params.slug);
    if (!woreda) notFound();

    return (
        <div className="container mx-auto px-4 py-10 max-w-4xl space-y-8">
            {/* Page Title */}
            <div className="border-b border-gray-200 pb-6">
                <h2 className="text-3xl font-bold text-gray-900">About the Woreda</h2>
                <p className="text-gray-500 mt-2">Historical background and key information</p>
            </div>

            {/* Description */}
            {woreda.description_en && (
                <section className="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                    <p className="text-gray-700 leading-8 text-lg">{woreda.description_en}</p>
                </section>
            )}

            {/* Key Facts Grid */}
            <section>
                <h3 className="text-xl font-bold text-gray-800 mb-4">Key Facts</h3>
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {[
                        { icon: FaMapMarkerAlt, label: 'Capital Town', value: woreda.capital_en },
                        { icon: FaUsers, label: 'Population', value: woreda.population },
                        { icon: FaRulerCombined, label: 'Area (km²)', value: woreda.area_km2 },
                        { icon: FaCalendarAlt, label: 'Established', value: woreda.established_year },
                        { icon: FaBuilding, label: 'Physical Address', value: woreda.address_en },
                        { icon: FaMapMarkerAlt, label: 'Zone', value: 'Oromo Special Zone' },
                    ].filter(f => f.value).map(({ icon: Icon, label, value }) => (
                        <div key={label} className="flex items-start gap-4 bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                            <div className="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0">
                                <Icon className="text-green-600" />
                            </div>
                            <div>
                                <p className="text-xs font-medium text-gray-400 uppercase tracking-wide">{label}</p>
                                <p className="text-gray-900 font-semibold mt-0.5">{value}</p>
                            </div>
                        </div>
                    ))}
                </div>
            </section>

            {/* Administrator */}
            {woreda.administrator_name && (
                <section className="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                    <h3 className="text-xl font-bold text-gray-800 mb-4">Leadership</h3>
                    <div className="flex items-center gap-4">
                        {woreda.administrator_photo_url ? (
                            // eslint-disable-next-line @next/next/no-img-element
                            <img
                                src={`${BACKEND_URL}${woreda.administrator_photo_url}`}
                                alt={woreda.administrator_name}
                                className="w-20 h-20 rounded-full object-cover border-2 border-green-100 flex-shrink-0"
                            />
                        ) : (
                            <div className="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center text-2xl flex-shrink-0">
                                <FaUserTie className="text-green-700" />
                            </div>
                        )}
                        <div>
                            <p className="text-lg font-bold text-gray-900">{woreda.administrator_name}</p>
                            <p className="text-gray-500">{woreda.administrator_title || 'Woreda Administrator'}</p>
                            <p className="text-sm text-gray-400 mt-1">{woreda.name_en} Woreda Administration</p>
                        </div>
                    </div>
                </section>
            )}

            {/* Map placeholder */}
            <section className="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div className="bg-green-800 text-white px-6 py-3 font-semibold text-sm">Location</div>
                <div className="h-52 bg-green-50 flex items-center justify-center text-gray-400">
                    <div className="text-center">
                        <FaMapMarkerAlt className="text-4xl text-green-400 mx-auto mb-2" />
                        <p>Map coming soon</p>
                        {woreda.address_en && <p className="text-sm mt-1">{woreda.address_en}</p>}
                    </div>
                </div>
            </section>
        </div>
    );
}
