'use client';

import { useEffect, useState } from 'react';
import { useParams, useRouter } from 'next/navigation';
import { fetchVacancyById, Vacancy } from '@/lib/api';
import { FaArrowLeft, FaMapMarkerAlt, FaCalendarAlt, FaBriefcase, FaUserGraduate, FaPaperPlane, FaClock, FaSpinner } from 'react-icons/fa';

export default function VacancyDetailPage() {
    const { id } = useParams();
    const router = useRouter();
    const [vacancy, setVacancy] = useState<Vacancy | null>(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        if (id) {
            fetchVacancyById(id as string).then(data => {
                setVacancy(data);
                setLoading(false);
            });
        }
    }, [id]);

    if (loading) {
        return (
            <div className="min-h-screen bg-gray-50 flex items-center justify-center">
                <FaSpinner className="animate-spin text-4xl text-primary" />
            </div>
        );
    }

    if (!vacancy) {
        return (
            <div className="min-h-screen bg-gray-50 py-20 text-center">
                <h1 className="text-2xl font-bold">Vacancy Not Found</h1>
                <button
                    onClick={() => router.push('/vacancies')}
                    className="mt-4 text-primary font-bold"
                >
                    Back to Listings
                </button>
            </div>
        );
    }

    return (
        <div className="bg-gray-50 min-h-screen pb-20">
            {/* Header / Breadcrumb */}
            <div className="bg-white border-b sticky top-20 z-10">
                <div className="container mx-auto px-4 py-4 flex items-center justify-between">
                    <button
                        onClick={() => router.back()}
                        className="flex items-center gap-2 text-gray-500 hover:text-primary transition-colors text-sm font-medium"
                    >
                        <FaArrowLeft /> Back to Vacancies
                    </button>
                    <span className="text-xs text-gray-400">Published {new Date(vacancy.created_at).toLocaleDateString()}</span>
                </div>
            </div>

            <div className="container mx-auto px-4 py-8">
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    {/* Main Content */}
                    <div className="lg:col-span-2 space-y-8">
                        <div className="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
                            <span className="bg-blue-50 text-primary text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-4 inline-block">
                                {vacancy.department}
                            </span>
                            <h1 className="text-3xl font-bold text-gray-900 mb-6">{vacancy.title_en}</h1>

                            <div className="grid grid-cols-2 md:grid-cols-4 gap-6 py-6 border-y border-gray-100 mb-8">
                                <div className="flex flex-col gap-1">
                                    <span className="text-gray-400 flex items-center gap-1 text-xs uppercase font-bold tracking-tight">
                                        <FaBriefcase className="text-xs" /> Job Type
                                    </span>
                                    <span className="font-bold text-gray-800">{vacancy.vacancy_type}</span>
                                </div>
                                <div className="flex flex-col gap-1">
                                    <span className="text-gray-400 flex items-center gap-1 text-xs uppercase font-bold tracking-tight">
                                        <FaMapMarkerAlt className="text-xs" /> Location
                                    </span>
                                    <span className="font-bold text-gray-800">{vacancy.location_en}</span>
                                </div>
                                <div className="flex flex-col gap-1">
                                    <span className="text-gray-400 flex items-center gap-1 text-xs uppercase font-bold tracking-tight">
                                        <FaCalendarAlt className="text-xs" /> Deadline
                                    </span>
                                    <span className="font-bold text-red-600">{new Date(vacancy.deadline).toLocaleDateString()}</span>
                                </div>
                                <div className="flex flex-col gap-1">
                                    <span className="text-gray-400 flex items-center gap-1 text-xs uppercase font-bold tracking-tight">
                                        <FaClock className="text-xs" /> Status
                                    </span>
                                    <span className="font-bold text-green-600">Accepting Applications</span>
                                </div>
                            </div>

                            <div className="prose max-w-none">
                                <h3 className="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                                    <FaBriefcase className="text-primary text-lg" /> Job Description
                                </h3>
                                <div className="text-gray-700 whitespace-pre-wrap leading-relaxed mb-8">
                                    {vacancy.description_en}
                                </div>

                                {vacancy.requirements_en && (
                                    <>
                                        <h3 className="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                                            <FaUserGraduate className="text-primary text-lg" /> Requirements
                                        </h3>
                                        <div className="text-gray-700 whitespace-pre-wrap leading-relaxed">
                                            {vacancy.requirements_en}
                                        </div>
                                    </>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* Sidebar / Application */}
                    <div className="lg:col-span-1 space-y-6">
                        <div className="bg-white p-6 rounded-2xl border-2 border-primary shadow-xl sticky top-40">
                            <h3 className="text-xl font-bold text-gray-900 mb-4">Apply for this position</h3>
                            <p className="text-sm text-gray-500 mb-6">
                                Please send your CV, application letter, and supporting documents to our HR department.
                            </p>

                            <div className="space-y-4">
                                <div className="p-4 bg-gray-50 rounded-xl border">
                                    <span className="block text-xs text-gray-400 uppercase font-bold mb-1 tracking-wider">Email Application</span>
                                    <a href="mailto:hr@oromospecialzone.gov.et" className="text-primary font-bold hover:underline">hr@oromospecialzone.gov.et</a>
                                </div>
                                <div className="p-4 bg-gray-50 rounded-xl border">
                                    <span className="block text-xs text-gray-400 uppercase font-bold mb-1 tracking-wider">In-Person Application</span>
                                    <span className="text-sm text-gray-800">Administration Building, Block B, Floor 2, HR Office, Kemise</span>
                                </div>
                            </div>

                            <button className="w-full mt-6 bg-primary text-white py-4 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-opacity-90 transition transform hover:-translate-y-1">
                                <FaPaperPlane /> Apply Now
                            </button>

                            <p className="mt-4 text-[10px] text-gray-400 text-center uppercase tracking-widest">
                                Reference Code: OSZ/VAC/{vacancy.id}
                            </p>
                        </div>

                        <div className="bg-gray-900 text-white p-6 rounded-2xl shadow-lg">
                            <h4 className="font-bold mb-2">Equality Statement</h4>
                            <p className="text-xs text-gray-400 leading-relaxed">
                                The Oromo Special Zone Administration is an equal opportunity employer. We value diversity and are committed to creating an inclusive environment for all employees.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    );
}

