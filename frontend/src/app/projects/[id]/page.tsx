'use client';

import { useEffect, useState, use } from 'react';
import { fetchProjectById, Project, getFileUrl } from '@/lib/api';
import {
    FaMapMarkerAlt,
    FaCalendarAlt,
    FaBuilding,
    FaMoneyBillWave,
    FaSpinner,
    FaArrowLeft,
    FaCheckCircle,
    FaHourglassHalf,
    FaPauseCircle,
    FaLayerGroup
} from 'react-icons/fa';
import Link from 'next/link';

const STATUS_CONFIG: Record<string, { bg: string; text: string; icon: any; bar: string }> = {
    'Planning': { bg: 'bg-purple-100', text: 'text-purple-700', icon: FaHourglassHalf, bar: 'bg-purple-500' },
    'Ongoing': { bg: 'bg-blue-100', text: 'text-blue-700', icon: FaSpinner, bar: 'bg-blue-500' },
    'In Progress': { bg: 'bg-blue-100', text: 'text-blue-700', icon: FaSpinner, bar: 'bg-blue-500' },
    'On Hold': { bg: 'bg-yellow-100', text: 'text-yellow-700', icon: FaPauseCircle, bar: 'bg-yellow-500' },
    'Completed': { bg: 'bg-green-100', text: 'text-green-700', icon: FaCheckCircle, bar: 'bg-green-500' },
    'Cancelled': { bg: 'bg-red-100', text: 'text-red-700', icon: FaPauseCircle, bar: 'bg-red-500' },
};

export default function ProjectDetailPage({ params }: { params: Promise<{ id: string }> }) {
    const { id } = use(params);
    const [project, setProject] = useState<Project | null>(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const loadProject = async () => {
            setLoading(true);
            const data = await fetchProjectById(id);
            setProject(data);
            setLoading(false);
        };
        loadProject();
    }, [id]);

    if (loading) {
        return (
            <div className="min-h-screen flex items-center justify-center bg-gray-50">
                <div className="text-center">
                    <FaSpinner className="animate-spin text-4xl text-primary mx-auto mb-4" />
                    <p className="text-gray-500 font-medium">Loading project details...</p>
                </div>
            </div>
        );
    }

    if (!project) {
        return (
            <div className="min-h-screen flex items-center justify-center bg-gray-50">
                <div className="text-center bg-white p-8 rounded-2xl shadow-sm border">
                    <div className="text-6xl mb-4">🔍</div>
                    <h2 className="text-2xl font-bold text-gray-800 mb-2">Project Not Found</h2>
                    <p className="text-gray-500 mb-6">The project you are looking for does not exist or has been removed.</p>
                    <Link
                        href="/projects"
                        className="inline-flex items-center gap-2 bg-primary text-white px-6 py-2 rounded-full font-bold hover:bg-blue-700 transition-colors"
                    >
                        <FaArrowLeft /> Back to Projects
                    </Link>
                </div>
            </div>
        );
    }

    const statusInfo = STATUS_CONFIG[project.status] || STATUS_CONFIG['Planning'];
    const StatusIcon = statusInfo.icon;

    return (
        <div className="bg-gray-50 min-h-screen pb-20">
            {/* Navigation & Header */}
            <div className="bg-white border-b sticky top-0 z-10">
                <div className="container mx-auto px-4 py-4 flex items-center justify-between">
                    <Link href="/projects" className="text-gray-500 hover:text-primary flex items-center gap-2 font-medium transition-colors">
                        <FaArrowLeft /> Back to All Projects
                    </Link>
                    <div className="flex items-center gap-3">
                        <span className={`px-4 py-1.5 rounded-full text-xs font-bold flex items-center gap-2 ${statusInfo.bg} ${statusInfo.text}`}>
                            <StatusIcon className={project.status === 'Ongoing' ? 'animate-spin' : ''} />
                            {project.status}
                        </span>
                    </div>
                </div>
            </div>

            {/* Hero Section */}
            <div className="relative h-[400px] md:h-[500px] w-full overflow-hidden bg-blue-900">
                {project.cover_image_url ? (
                    <>
                        <img
                            src={getFileUrl(project.cover_image_url)}
                            alt={project.title_en}
                            className="w-full h-full object-cover opacity-60"
                        />
                        <div className="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent" />
                    </>
                ) : (
                    <div className="absolute inset-0 bg-gradient-to-br from-blue-900 to-primary flex items-center justify-center">
                        <FaLayerGroup className="text-8xl text-blue-800 opacity-20" />
                    </div>
                )}

                <div className="absolute bottom-0 left-0 w-full p-8 md:p-16">
                    <div className="container mx-auto">
                        <div className="max-w-4xl">
                            <h1 className="text-3xl md:text-5xl font-bold text-white mb-4 leading-tight">
                                {project.title_en}
                            </h1>
                            {project.location_en && (
                                <div className="flex items-center gap-2 text-blue-100 text-lg md:text-xl mb-6">
                                    <FaMapMarkerAlt className="text-blue-300" />
                                    <span>{project.location_en}</span>
                                </div>
                            )}

                            {/* Quick Stats Grid */}
                            <div className="flex flex-wrap gap-4 md:gap-8 mt-8">
                                <div className="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20 min-w-[140px]">
                                    <div className="text-blue-200 text-xs uppercase font-bold tracking-wider mb-1">Progress</div>
                                    <div className="text-white text-2xl font-bold">{project.progress}%</div>
                                    <div className="w-full bg-white/20 rounded-full h-1.5 mt-2 overflow-hidden">
                                        <div
                                            className={`h-full ${statusInfo.bar} transition-all duration-1000`}
                                            style={{ width: `${project.progress}%` }}
                                        />
                                    </div>
                                </div>
                                {project.budget && (
                                    <div className="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20 min-w-[140px]">
                                        <div className="text-blue-200 text-xs uppercase font-bold tracking-wider mb-1">Total Budget</div>
                                        <div className="text-white text-2xl font-bold">
                                            {Number(project.budget).toLocaleString()} <span className="text-sm font-normal">ETB</span>
                                        </div>
                                    </div>
                                )}
                                <div className="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20 min-w-[140px]">
                                    <div className="text-blue-200 text-xs uppercase font-bold tracking-wider mb-1">Duration</div>
                                    <div className="text-white text-lg font-bold">
                                        {new Date(project.start_date).getFullYear()} – {project.end_date ? new Date(project.end_date).getFullYear() : 'Active'}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div className="container mx-auto px-4 mt-8">
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {/* Main Content */}
                    <div className="lg:col-span-2 space-y-8">
                        {/* Description */}
                        <section className="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                            <h2 className="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <span className="w-1.5 h-8 bg-primary rounded-full" />
                                Project Overview
                            </h2>

                            <div className="prose prose-blue max-w-none text-gray-600 leading-relaxed space-y-6">
                                <div>
                                    <h3 className="text-sm font-bold text-gray-400 uppercase tracking-widest mb-3">English</h3>
                                    <p className="text-lg">{project.description_en}</p>
                                </div>

                                {project.description_am && (
                                    <div className="pt-6 border-t border-gray-50">
                                        <h3 className="text-sm font-bold text-gray-400 uppercase tracking-widest mb-3">Amharic / አማርኛ</h3>
                                        <p className="text-lg leading-loose">{project.description_am}</p>
                                    </div>
                                )}

                                {project.description_or && (
                                    <div className="pt-6 border-t border-gray-50">
                                        <h3 className="text-sm font-bold text-gray-400 uppercase tracking-widest mb-3">Afaan Oromo</h3>
                                        <p className="text-lg leading-relaxed">{project.description_or}</p>
                                    </div>
                                )}
                            </div>
                        </section>

                        {/* Visual Impact Section (Optional/Future) */}
                        {/* You could add a gallery here if the database had one */}
                    </div>

                    {/* Sidebar Details */}
                    <div className="space-y-6">
                        {/* Details Card */}
                        <div className="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                            <h3 className="text-xl font-bold text-gray-900 mb-6">Technical Specifications</h3>

                            <div className="space-y-6">
                                <div className="flex items-start gap-4">
                                    <div className="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-primary flex-shrink-0">
                                        <FaBuilding />
                                    </div>
                                    <div>
                                        <div className="text-sm text-gray-500 font-medium">Contractor / Executing Agency</div>
                                        <div className="text-gray-900 font-bold mt-0.5">{project.contractor || 'Oromo Special Zone Administration'}</div>
                                    </div>
                                </div>

                                <div className="flex items-start gap-4">
                                    <div className="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-600 flex-shrink-0">
                                        <FaMoneyBillWave />
                                    </div>
                                    <div>
                                        <div className="text-sm text-gray-500 font-medium">Funding Source</div>
                                        <div className="text-gray-900 font-bold mt-0.5">{project.funding_source || 'Government Funded'}</div>
                                    </div>
                                </div>

                                <div className="flex items-start gap-4">
                                    <div className="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center text-purple-600 flex-shrink-0">
                                        <FaCalendarAlt />
                                    </div>
                                    <div>
                                        <div className="text-sm text-gray-500 font-medium">Project Timeline</div>
                                        <div className="text-gray-900 font-bold mt-0.5">
                                            {new Date(project.start_date).toLocaleDateString(undefined, { dateStyle: 'long' })}
                                        </div>
                                        {project.end_date && (
                                            <div className="text-gray-500 text-sm mt-1">
                                                Expected Completion: {new Date(project.end_date).toLocaleDateString(undefined, { dateStyle: 'long' })}
                                            </div>
                                        )}
                                    </div>
                                </div>

                                <div className="flex items-start gap-4">
                                    <div className="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center text-orange-600 flex-shrink-0">
                                        <FaMapMarkerAlt />
                                    </div>
                                    <div>
                                        <div className="text-sm text-gray-500 font-medium">Exact Location</div>
                                        <div className="text-gray-900 font-bold mt-0.5">{project.location_en}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Localized Titles */}
                        <div className="bg-gray-900 rounded-2xl p-6 text-white shadow-xl shadow-blue-900/10">
                            <h3 className="text-lg font-bold mb-4 flex items-center gap-2">
                                <FaLayerGroup className="text-blue-400" />
                                Regional Titles
                            </h3>
                            <div className="space-y-4">
                                {project.title_am && (
                                    <div>
                                        <div className="text-[10px] uppercase font-bold text-blue-400 tracking-wider mb-1">Amharic</div>
                                        <div className="text-sm leading-relaxed">{project.title_am}</div>
                                    </div>
                                )}
                                {project.title_or && (
                                    <div>
                                        <div className="text-[10px] uppercase font-bold text-blue-400 tracking-wider mb-1">Afaan Oromo</div>
                                        <div className="text-sm leading-relaxed">{project.title_or}</div>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Contact/Support Info */}
                        <div className="bg-blue-50 border border-blue-100 rounded-2xl p-6">
                            <h3 className="font-bold text-blue-900 mb-2">Have questions?</h3>
                            <p className="text-sm text-blue-700 mb-4">For inquiries about this project, please contact the Oromo Special Zone infrastructure department.</p>
                            <a
                                href="mailto:info@oromospecialzone.gov.et"
                                className="block w-full text-center bg-white text-primary border border-blue-200 py-2 rounded-xl text-sm font-bold hover:bg-blue-100 transition-colors"
                            >
                                Contact Department
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
