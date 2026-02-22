'use client';

import { useEffect, useState } from 'react';
import { fetchProjects, Project, getFileUrl } from '@/lib/api';
import { FaMapMarkerAlt, FaCalendarAlt, FaBuilding, FaMoneyBillWave, FaSpinner, FaFilter } from 'react-icons/fa';

const STATUS_COLORS: Record<string, { bg: string; text: string; bar: string }> = {
    'Planning': { bg: 'bg-purple-100', text: 'text-purple-700', bar: 'bg-purple-500' },
    'In Progress': { bg: 'bg-blue-100', text: 'text-blue-700', bar: 'bg-blue-500' },
    'On Hold': { bg: 'bg-yellow-100', text: 'text-yellow-700', bar: 'bg-yellow-500' },
    'Completed': { bg: 'bg-green-100', text: 'text-green-700', bar: 'bg-green-500' },
    'Cancelled': { bg: 'bg-red-100', text: 'text-red-700', bar: 'bg-red-500' },
};

const STATUSES = ['All', 'Planning', 'In Progress', 'On Hold', 'Completed', 'Cancelled'];

export default function ProjectsPage() {
    const [projects, setProjects] = useState<Project[]>([]);
    const [loading, setLoading] = useState(true);
    const [activeStatus, setActiveStatus] = useState('All');

    useEffect(() => {
        loadProjects();
    }, [activeStatus]);

    async function loadProjects() {
        setLoading(true);
        const data = await fetchProjects(activeStatus === 'All' ? undefined : activeStatus);
        setProjects(data);
        setLoading(false);
    }

    return (
        <div className="bg-gray-50 min-h-screen">
            {/* Hero */}
            <div className="bg-gradient-to-r from-blue-900 to-primary text-white py-20">
                <div className="container mx-auto px-4 text-center">
                    <h1 className="text-4xl font-bold mb-4">Development Projects</h1>
                    <p className="text-blue-100 max-w-2xl mx-auto text-lg">
                        Tracking progress on infrastructure, healthcare, education, and other key development initiatives across the Oromo Special Zone.
                    </p>
                </div>
            </div>

            {/* Stats Bar */}
            <div className="bg-white border-b shadow-sm">
                <div className="container mx-auto px-4 py-4 flex flex-wrap gap-6">
                    {STATUSES.filter(s => s !== 'All').map(s => {
                        const count = projects.filter(p => p.status === s).length;
                        const colors = STATUS_COLORS[s];
                        return (
                            <button
                                key={s}
                                onClick={() => setActiveStatus(s === activeStatus ? 'All' : s)}
                                className={`px-4 py-2 rounded-full text-sm font-bold transition-all ${activeStatus === s ? colors.bg + ' ' + colors.text : 'text-gray-500 hover:bg-gray-100'}`}
                            >
                                {s} ({count})
                            </button>
                        );
                    })}
                    <button
                        onClick={() => setActiveStatus('All')}
                        className={`ml-auto px-4 py-2 rounded-full text-sm font-bold transition-all ${activeStatus === 'All' ? 'bg-gray-900 text-white' : 'text-gray-500 hover:bg-gray-100'}`}
                    >
                        All Projects ({projects.length})
                    </button>
                </div>
            </div>

            <div className="container mx-auto px-4 py-12">
                {loading ? (
                    <div className="py-20 text-center text-gray-400">
                        <FaSpinner className="animate-spin text-4xl mx-auto mb-4" />
                        <p>Loading projects...</p>
                    </div>
                ) : projects.length > 0 ? (
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        {projects.map(p => {
                            const colors = STATUS_COLORS[p.status] || STATUS_COLORS['Planning'];
                            return (
                                <div key={p.id} className="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition-shadow overflow-hidden group">
                                    {p.cover_image_url && (
                                        <div className="h-48 overflow-hidden">
                                            <img
                                                src={getFileUrl(p.cover_image_url)}
                                                alt={p.title_en}
                                                className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                            />
                                        </div>
                                    )}
                                    <div className="p-6">
                                        <div className="flex items-center justify-between mb-4">
                                            <span className={`px-3 py-1 rounded-full text-xs font-bold ${colors.bg} ${colors.text}`}>
                                                {p.status}
                                            </span>
                                            {p.funding_source && (
                                                <span className="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded">
                                                    {p.funding_source}
                                                </span>
                                            )}
                                        </div>

                                        <h2 className="text-xl font-bold text-gray-900 mb-3 leading-snug">{p.title_en}</h2>

                                        {p.description_en && (
                                            <p className="text-gray-600 text-sm mb-5 line-clamp-2">{p.description_en}</p>
                                        )}

                                        {/* Progress Bar */}
                                        <div className="mb-5">
                                            <div className="flex justify-between text-xs font-bold text-gray-500 mb-1">
                                                <span>Progress</span>
                                                <span className={colors.text}>{p.progress}%</span>
                                            </div>
                                            <div className="bg-gray-100 rounded-full h-2.5">
                                                <div
                                                    className={`h-2.5 rounded-full ${colors.bar} transition-all duration-700`}
                                                    style={{ width: `${p.progress}%` }}
                                                />
                                            </div>
                                        </div>

                                        {/* Meta Grid */}
                                        <div className="grid grid-cols-2 gap-3 text-sm">
                                            {p.location_en && (
                                                <div className="flex items-center gap-2 text-gray-500">
                                                    <FaMapMarkerAlt className="text-gray-400 flex-shrink-0" />
                                                    <span className="truncate">{p.location_en}</span>
                                                </div>
                                            )}
                                            <div className="flex items-center gap-2 text-gray-500">
                                                <FaCalendarAlt className="text-gray-400 flex-shrink-0" />
                                                <span>{new Date(p.start_date).toLocaleDateString(undefined, { year: 'numeric', month: 'short' })}</span>
                                                {p.end_date && <><span>–</span><span>{new Date(p.end_date).toLocaleDateString(undefined, { year: 'numeric', month: 'short' })}</span></>}
                                            </div>
                                            {p.budget && (
                                                <div className="flex items-center gap-2 text-gray-500 col-span-2">
                                                    <FaMoneyBillWave className="text-gray-400 flex-shrink-0" />
                                                    <span className="font-semibold">Budget: {Number(p.budget).toLocaleString()} {p.budget_currency}</span>
                                                </div>
                                            )}
                                            {p.contractor && (
                                                <div className="flex items-center gap-2 text-gray-500 col-span-2">
                                                    <FaBuilding className="text-gray-400 flex-shrink-0" />
                                                    <span className="truncate">{p.contractor}</span>
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                ) : (
                    <div className="py-20 text-center bg-white rounded-2xl border">
                        <p className="text-2xl text-gray-300 mb-4">🏗️</p>
                        <h3 className="text-xl font-bold text-gray-800">No Projects Found</h3>
                        <p className="text-gray-500 mt-2">No projects match the selected filter.</p>
                    </div>
                )}
            </div>
        </div>
    );
}
