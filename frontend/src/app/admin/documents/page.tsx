'use client';
import { useState, useEffect, useCallback, useRef } from 'react';
import AdminLayout from '@/components/admin/AdminLayout';
import { FaTrash, FaUpload, FaFilePdf, FaFileWord, FaFileImage, FaFile } from 'react-icons/fa';
import {
    fetchDocuments,
    uploadDocument,
    deleteDocument,
    DocumentItem,
    getFileUrl,
} from '@/lib/api';

export default function AdminDocumentsPage() {
    const [documents, setDocuments] = useState<DocumentItem[]>([]);
    const [loading, setLoading] = useState(true);
    const [uploading, setUploading] = useState(false);
    const [error, setError] = useState('');
    const [success, setSuccess] = useState('');

    // Upload form state
    const [title, setTitle] = useState('');
    const [category, setCategory] = useState('Planning');
    const [author, setAuthor] = useState('');
    const [description, setDescription] = useState('');
    const [pages, setPages] = useState('');
    const [language, setLanguage] = useState('English');
    const [file, setFile] = useState<File | null>(null);
    const [cover, setCover] = useState<File | null>(null);
    const fileInputRef = useRef<HTMLInputElement>(null);
    const coverInputRef = useRef<HTMLInputElement>(null);

    const loadDocuments = useCallback(async () => {
        setLoading(true);
        const data = await fetchDocuments();
        setDocuments(data);
        setLoading(false);
    }, []);

    useEffect(() => { loadDocuments(); }, [loadDocuments]);

    const getIcon = (type: string) => {
        if (type === 'pdf') return <FaFilePdf className="text-red-500 text-xl" />;
        if (type === 'doc' || type === 'docx') return <FaFileWord className="text-blue-500 text-xl" />;
        if (['jpg', 'jpeg', 'png', 'gif'].includes(type)) return <FaFileImage className="text-green-500 text-xl" />;
        return <FaFile className="text-gray-500 text-xl" />;
    };

    const handleUpload = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!file) { setError('Please select a document file.'); return; }
        if (!title.trim()) { setError('Please enter a title.'); return; }

        setUploading(true);
        setError('');
        setSuccess('');
        const token = localStorage.getItem('adminToken') || '';

        try {
            const formData = new FormData();
            formData.append('file', file);
            if (cover) formData.append('cover', cover);
            formData.append('title_en', title);
            formData.append('category', category);
            formData.append('author', author);
            formData.append('description_en', description);
            formData.append('pages', pages);
            formData.append('language', language);

            await uploadDocument(formData, token);
            setSuccess('Resource uploaded successfully!');
            setTitle('');
            setCategory('Planning');
            setAuthor('');
            setDescription('');
            setPages('');
            setLanguage('English');
            setFile(null);
            setCover(null);
            if (fileInputRef.current) fileInputRef.current.value = '';
            if (coverInputRef.current) coverInputRef.current.value = '';
            loadDocuments();
        } catch (err: any) {
            setError(err.message || 'Upload failed.');
        } finally {
            setUploading(false);
        }
    };

    const handleDelete = async (id: number) => {
        if (!confirm('Delete this resource? This action cannot be undone.')) return;
        const token = localStorage.getItem('adminToken') || '';
        try {
            await deleteDocument(id, token);
            setDocuments((prev) => prev.filter((d) => d.id !== id));
        } catch (err: any) {
            alert(err.message || 'Failed to delete.');
        }
    };

    return (
        <AdminLayout>
            <div className="mb-6">
                <h1 className="text-2xl font-bold text-gray-800">Digital Library & Resources</h1>
                <p className="text-gray-500 text-sm mt-1">Manage books, reports, and official documents for the digital library.</p>
            </div>

            {/* Upload Form */}
            <div className="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                <h2 className="font-bold text-lg text-gray-800 mb-4 flex items-center gap-2">
                    <FaUpload className="text-green-500" /> Add New Resource
                </h2>
                <form onSubmit={handleUpload} className="space-y-4">
                    {error && <p className="text-red-600 bg-red-50 border border-red-200 p-3 rounded-lg text-sm">{error}</p>}
                    {success && <p className="text-green-700 bg-green-50 border border-green-200 p-3 rounded-lg text-sm">{success}</p>}

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div className="lg:col-span-2">
                            <label className="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <input
                                type="text"
                                required
                                value={title}
                                onChange={(e) => setTitle(e.target.value)}
                                placeholder="Book or Document Title"
                                className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 text-sm"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Author / Organization</label>
                            <input
                                type="text"
                                value={author}
                                onChange={(e) => setAuthor(e.target.value)}
                                placeholder="e.g. OSZA Planning Dept"
                                className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 text-sm"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select
                                value={category}
                                onChange={(e) => setCategory(e.target.value)}
                                className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 text-sm"
                            >
                                <option>Planning</option>
                                <option>Finance</option>
                                <option>Education</option>
                                <option>Health</option>
                                <option>Legal</option>
                                <option>Policy</option>
                                <option>Report</option>
                            </select>
                        </div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div className="md:col-span-2">
                            <label className="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                            <textarea
                                value={description}
                                onChange={(e) => setDescription(e.target.value)}
                                placeholder="Brief overview of the content..."
                                className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 text-sm h-20"
                            />
                        </div>
                        <div className="space-y-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Pages</label>
                                <input
                                    type="number"
                                    value={pages}
                                    onChange={(e) => setPages(e.target.value)}
                                    className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 text-sm"
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Language</label>
                                <input
                                    type="text"
                                    value={language}
                                    onChange={(e) => setLanguage(e.target.value)}
                                    className="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 text-sm"
                                />
                            </div>
                        </div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Resource File (PDF, DOC)</label>
                            <input
                                ref={fileInputRef}
                                type="file"
                                accept=".pdf,.doc,.docx"
                                required
                                onChange={(e) => setFile(e.target.files?.[0] || null)}
                                className="w-full border rounded-lg px-3 py-2 text-sm text-gray-600 file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:bg-green-50 file:text-green-700 hover:file:bg-green-100"
                            />
                        </div>
                        <div className="flex items-start gap-4">
                            <div className="flex-1">
                                <label className="block text-sm font-medium text-gray-700 mb-1">Cover Image (Book Cover)</label>
                                <input
                                    ref={coverInputRef}
                                    type="file"
                                    accept="image/*"
                                    onChange={(e) => setCover(e.target.files?.[0] || null)}
                                    className="w-full border rounded-lg px-3 py-2 text-sm text-gray-600 file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:bg-green-50 file:text-green-700 hover:file:bg-green-100"
                                />
                            </div>
                            {cover && (
                                <img
                                    src={URL.createObjectURL(cover)}
                                    className="w-12 h-16 object-cover rounded border bg-gray-50 flex-shrink-0"
                                    alt="Preview"
                                />
                            )}
                        </div>
                    </div>
                    <div className="flex justify-end">
                        <button
                            type="submit"
                            disabled={uploading}
                            className="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 transition flex items-center gap-2 disabled:opacity-50"
                        >
                            <FaUpload />
                            {uploading ? 'Uploading...' : 'Upload Document'}
                        </button>
                    </div>
                </form>
            </div>

            {/* Resources Table */}
            <div className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table className="w-full text-left">
                    <thead className="bg-gray-50 border-b">
                        <tr>
                            <th className="px-6 py-4 font-semibold text-gray-600 text-sm">Resource</th>
                            <th className="px-6 py-4 font-semibold text-gray-600 text-sm md:table-cell hidden">Author</th>
                            <th className="px-6 py-4 font-semibold text-gray-600 text-sm">Category</th>
                            <th className="px-6 py-4 font-semibold text-gray-600 text-sm">Date</th>
                            <th className="px-6 py-4 font-semibold text-gray-600 text-sm text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-gray-100">
                        {loading ? (
                            <tr><td colSpan={5} className="px-6 py-8 text-center text-gray-400">Loading library...</td></tr>
                        ) : documents.length === 0 ? (
                            <tr><td colSpan={5} className="px-6 py-8 text-center text-gray-400">No resources found.</td></tr>
                        ) : documents.map((doc) => (
                            <tr key={doc.id} className="hover:bg-gray-50 transition">
                                <td className="px-6 py-4">
                                    <div className="flex items-center gap-3">
                                        {doc.cover_image_url ? (
                                            <img
                                                src={getFileUrl(doc.cover_image_url)}
                                                className="w-10 h-14 object-cover rounded shadow-sm"
                                                alt="Cover"
                                            />
                                        ) : (
                                            <div className="w-10 h-14 bg-gray-100 rounded border flex items-center justify-center text-gray-300">
                                                {getIcon(doc.file_type)}
                                            </div>
                                        )}
                                        <div>
                                            <span className="font-medium text-gray-900 block line-clamp-1">{doc.title_en}</span>
                                            <span className="text-xs text-gray-400 uppercase font-bold">{doc.file_type} Resource</span>
                                        </div>
                                    </div>
                                </td>
                                <td className="px-6 py-4 text-sm text-gray-600 md:table-cell hidden">
                                    {doc.author || '-'}
                                </td>
                                <td className="px-6 py-4">
                                    <span className="bg-gray-100 text-gray-700 py-1 px-3 rounded-full text-xs font-medium">
                                        {doc.category}
                                    </span>
                                </td>
                                <td className="px-6 py-4 text-sm text-gray-500">
                                    {new Date(doc.created_at).toLocaleDateString()}
                                </td>
                                <td className="px-6 py-4 text-right flex items-center justify-end gap-3">
                                    <a
                                        href={getFileUrl(doc.file_url)}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="text-blue-500 hover:text-blue-700 text-sm font-medium"
                                    >
                                        View
                                    </a>
                                    <button
                                        onClick={() => handleDelete(doc.id)}
                                        className="text-red-500 hover:text-red-700"
                                        title="Delete"
                                    >
                                        <FaTrash />
                                    </button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
                <div className="p-4 border-t bg-gray-50 text-sm text-gray-500">
                    {documents.length} resource{documents.length !== 1 ? 's' : ''} total in digital library
                </div>
            </div>
        </AdminLayout>
    );
}
