const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:5000/api';
const BACKEND_URL = process.env.NEXT_PUBLIC_BACKEND_URL || 'http://localhost:5000';

// ─── Interfaces ───────────────────────────────────────────────────────────────

export interface NewsItem {
    id: number;
    title_en: string;
    title_am?: string;
    title_or?: string;
    content_en: string;
    content_am?: string;
    content_or?: string;
    category: string;
    status: string;
    thumbnail_url?: string;
    published_at: string;
    created_at: string;
}

export interface DocumentItem {
    id: number;
    title_en: string;
    file_url: string;
    file_type: string;
    category: string;
    cover_image_url?: string;
    author?: string;
    description_en?: string;
    pages?: number;
    language?: string;
    uploaded_by?: number;
    created_at: string;
}

export interface DashboardStats {
    news: number;
    documents: number;
    alerts: number;
    users: number;
    vacancies?: number;
    tenders?: number;
}

export interface Vacancy {
    id: number;
    title_en: string;
    title_am?: string;
    title_or?: string;
    description_en: string;
    description_am?: string;
    description_or?: string;
    requirements_en?: string;
    requirements_am?: string;
    requirements_or?: string;
    department: string;
    vacancy_type: 'Full-time' | 'Part-time' | 'Contract' | 'Internship';
    location_en: string;
    deadline: string;
    is_active: boolean;
    created_at: string;
}

export interface Tender {
    id: number;
    title_en: string;
    title_am?: string;
    title_or?: string;
    description_en?: string;
    description_am?: string;
    description_or?: string;
    ref_number?: string;
    deadline: string;
    file_url?: string;
    status: 'Open' | 'Closed' | 'Awarded' | 'Cancelled';
    created_at: string;
}

// ─── Helpers ──────────────────────────────────────────────────────────────────

export function getFileUrl(file_url: string): string {
    return `${BACKEND_URL}${file_url}`;
}

// ─── Public: News ─────────────────────────────────────────────────────────────

export async function fetchNews(category?: string): Promise<NewsItem[]> {
    try {
        const url = new URL(`${API_URL}/news`);
        if (category && category !== 'All News') {
            url.searchParams.append('category', category);
        }
        const res = await fetch(url.toString(), { cache: 'no-store' });
        if (!res.ok) return [];
        return res.json();
    } catch (error) {
        console.error('Error fetching news:', error);
        return [];
    }
}

export async function fetchNewsById(id: string): Promise<NewsItem | null> {
    try {
        const res = await fetch(`${API_URL}/news/${id}`, { cache: 'no-store' });
        if (!res.ok) return null;
        return res.json();
    } catch (error) {
        console.error('Error fetching news detail:', error);
        return null;
    }
}

// For admin: fetches all posts regardless of status
export async function fetchAllNewsAdmin(): Promise<NewsItem[]> {
    try {
        const url = new URL(`${API_URL}/news`);
        url.searchParams.append('admin', 'true');
        const res = await fetch(url.toString(), { cache: 'no-store' });
        if (!res.ok) return [];
        return res.json();
    } catch (error) {
        console.error('Error fetching admin news:', error);
        return [];
    }
}

// ─── Public: Documents ────────────────────────────────────────────────────────

export async function fetchDocuments(category?: string, search?: string): Promise<DocumentItem[]> {
    try {
        const url = new URL(`${API_URL}/documents`);
        if (category && category !== 'All Categories') {
            url.searchParams.append('category', category);
        }
        if (search) {
            url.searchParams.append('search', search);
        }
        const res = await fetch(url.toString(), { cache: 'no-store' });
        if (!res.ok) return [];
        return res.json();
    } catch (error) {
        console.error('Error fetching documents:', error);
        return [];
    }
}

// ─── Auth ─────────────────────────────────────────────────────────────────────

export async function login(email: string, password: string): Promise<any> {
    const res = await fetch(`${API_URL}/auth/login`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password }),
    });
    if (!res.ok) {
        const error = await res.json();
        throw new Error(error.message || 'Login failed');
    }
    return res.json();
}

// ─── Admin: Stats ─────────────────────────────────────────────────────────────

export async function fetchStats(token: string): Promise<DashboardStats | null> {
    try {
        const res = await fetch(`${API_URL}/stats`, {
            headers: { Authorization: `Bearer ${token}` },
            cache: 'no-store',
        });
        if (!res.ok) return null;
        return res.json();
    } catch (error) {
        console.error('Error fetching stats:', error);
        return null;
    }
}

// ─── Admin: News CRUD ─────────────────────────────────────────────────────────

export async function createNews(formData: FormData, token: string): Promise<any> {
    const res = await fetch(`${API_URL}/news`, {
        method: 'POST',
        headers: {
            Authorization: `Bearer ${token}`,
        },
        body: formData,
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to create news');
    return res.json();
}

export async function updateNews(id: number, formData: FormData, token: string): Promise<any> {
    const res = await fetch(`${API_URL}/news/${id}`, {
        method: 'PUT',
        headers: {
            Authorization: `Bearer ${token}`,
        },
        body: formData,
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to update news');
    return res.json();
}

export async function deleteNews(id: number, token: string): Promise<void> {
    const res = await fetch(`${API_URL}/news/${id}`, {
        method: 'DELETE',
        headers: { Authorization: `Bearer ${token}` },
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to delete news');
}

// ─── Admin: Documents ─────────────────────────────────────────────────────────

export async function uploadDocument(formData: FormData, token: string): Promise<any> {
    const res = await fetch(`${API_URL}/documents/upload`, {
        method: 'POST',
        headers: { Authorization: `Bearer ${token}` },
        body: formData,
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Upload failed');
    return res.json();
}

export async function deleteDocument(id: number, token: string): Promise<void> {
    const res = await fetch(`${API_URL}/documents/${id}`, {
        method: 'DELETE',
        headers: { Authorization: `Bearer ${token}` },
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to delete document');
}

// ─── Alerts ───────────────────────────────────────────────────────────────────

export interface AlertItem {
    id: number;
    message_en: string;
    message_am?: string;
    message_or?: string;
    severity: 'info' | 'warning' | 'critical';
    is_active: boolean;
    expires_at?: string;
    created_at: string;
}

/** Public: returns only active, non-expired alerts */
export async function fetchActiveAlerts(): Promise<AlertItem[]> {
    try {
        const res = await fetch(`${API_URL}/alerts`, { cache: 'no-store' });
        if (!res.ok) return [];
        return res.json();
    } catch (error) {
        console.error('Error fetching active alerts:', error);
        return [];
    }
}

/** Admin: returns ALL alerts regardless of status */
export async function fetchAllAlerts(token: string): Promise<AlertItem[]> {
    try {
        const res = await fetch(`${API_URL}/alerts/all`, {
            headers: { Authorization: `Bearer ${token}` },
            cache: 'no-store',
        });
        if (!res.ok) return [];
        return res.json();
    } catch (error) {
        console.error('Error fetching all alerts:', error);
        return [];
    }
}

export async function createAlert(data: Partial<AlertItem>, token: string): Promise<any> {
    const res = await fetch(`${API_URL}/alerts`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify(data),
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to create alert');
    return res.json();
}

export async function toggleAlert(id: number, token: string): Promise<void> {
    const res = await fetch(`${API_URL}/alerts/${id}/toggle`, {
        method: 'PUT',
        headers: { Authorization: `Bearer ${token}` },
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to toggle alert');
}

export async function deleteAlert(id: number, token: string): Promise<void> {
    const res = await fetch(`${API_URL}/alerts/${id}`, {
        method: 'DELETE',
        headers: { Authorization: `Bearer ${token}` },
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to delete alert');
}

// ─── Hero Slides ───────────────────────────────────────────────────────────────

export interface HeroSlide {
    id: number;
    title_en: string;
    title_am?: string;
    title_or?: string;
    subtitle_en: string;
    subtitle_am?: string;
    subtitle_or?: string;
    media_url: string;
    media_type: 'image' | 'video';
    cta_text: string;
    cta_text_am?: string;
    cta_text_or?: string;
    cta_url: string;
    sort_order: number;
    is_active: boolean;
}

/** Public: returns only active slides ordered by sort_order */
export async function fetchHeroSlides(): Promise<HeroSlide[]> {
    try {
        const res = await fetch(`${API_URL}/hero`, { cache: 'no-store' });
        if (!res.ok) return [];
        return res.json();
    } catch (error) {
        console.error('Error fetching hero slides:', error);
        return [];
    }
}

// ─── Woredas ──────────────────────────────────────────────────────────────────

export interface WoredaItem {
    id: number;
    name_en: string;
    name_am?: string;
    name_or?: string;
    slug: string;
    description_en?: string;
    description_am?: string;
    description_or?: string;
    population?: string;
    area_km2?: string;
    established_year?: string;
    capital_en?: string;
    capital_am?: string;
    capital_or?: string;
    administrator_name?: string;
    administrator_title?: string;
    administrator_photo_url?: string;
    contact_phone?: string;
    contact_email?: string;
    address_en?: string;
    address_am?: string;
    address_or?: string;
    banner_url?: string;
    logo_url?: string;
    is_active: boolean;
    created_at: string;
}

export interface GalleryItem {
    id: number;
    title: string;
    image_url: string;
    category: string;
    woreda_id?: number | null;
    woreda_name?: string;
    woreda_slug?: string;
    sort_order: number;
    is_active: boolean;
    created_at: string;
}

export interface GalleryCategory {
    category: string;
    count: number;
    cover_url?: string;
}

/** Public: all active woredas */
export async function fetchWoredas(): Promise<WoredaItem[]> {
    try {
        const res = await fetch(`${API_URL}/woredas`, { cache: 'no-store' });
        if (!res.ok) return [];
        return res.json();
    } catch (error) {
        console.error('Error fetching woredas:', error);
        return [];
    }
}

/** Public: single woreda by slug */
export async function fetchWoredaBySlug(slug: string): Promise<WoredaItem | null> {
    try {
        const res = await fetch(`${API_URL}/woredas/${slug}`, { cache: 'no-store' });
        if (!res.ok) return null;
        return res.json();
    } catch (error) {
        console.error('Error fetching woreda:', error);
        return null;
    }
}

/** Admin: all woredas including inactive */
export async function fetchAllWoredas(token: string): Promise<WoredaItem[]> {
    try {
        const res = await fetch(`${API_URL}/woredas/all`, {
            headers: { Authorization: `Bearer ${token}` },
            cache: 'no-store',
        });
        if (!res.ok) return [];
        return res.json();
    } catch (error) {
        console.error('Error fetching all woredas:', error);
        return [];
    }
}

export async function createWoreda(formData: FormData, token: string): Promise<any> {
    const res = await fetch(`${API_URL}/woredas`, {
        method: 'POST',
        headers: { Authorization: `Bearer ${token}` },
        body: formData,
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to create woreda');
    return res.json();
}

export async function updateWoreda(id: number, formData: FormData, token: string): Promise<any> {
    const res = await fetch(`${API_URL}/woredas/${id}`, {
        method: 'PUT',
        headers: { Authorization: `Bearer ${token}` },
        body: formData,
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to update woreda');
    return res.json();
}

export async function deleteWoreda(id: number, token: string): Promise<void> {
    const res = await fetch(`${API_URL}/woredas/${id}`, {
        method: 'DELETE',
        headers: { Authorization: `Bearer ${token}` },
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to delete woreda');
}

// ─── Gallery ──────────────────────────────────────────────────────────────────

/** Public: gallery items with optional filters */
export async function fetchGallery(woredaId?: number, category?: string): Promise<GalleryItem[]> {
    try {
        const url = new URL(`${API_URL}/gallery`);
        if (woredaId) url.searchParams.append('woreda_id', String(woredaId));
        if (category) url.searchParams.append('category', category);
        const res = await fetch(url.toString(), { cache: 'no-store' });
        if (!res.ok) return [];
        return res.json();
    } catch (error) {
        console.error('Error fetching gallery:', error);
        return [];
    }
}

/** Public: category albums with cover image and count */
export async function fetchGalleryCategories(woredaId?: number): Promise<GalleryCategory[]> {
    try {
        const url = new URL(`${API_URL}/gallery/categories`);
        if (woredaId) url.searchParams.append('woreda_id', String(woredaId));
        const res = await fetch(url.toString(), { cache: 'no-store' });
        if (!res.ok) return [];
        return res.json();
    } catch (error) {
        console.error('Error fetching gallery categories:', error);
        return [];
    }
}

/** Admin: all gallery items including inactive */
export async function fetchAllGallery(token: string): Promise<GalleryItem[]> {
    try {
        const res = await fetch(`${API_URL}/gallery/all`, {
            headers: { Authorization: `Bearer ${token}` },
            cache: 'no-store',
        });
        if (!res.ok) return [];
        return res.json();
    } catch (error) {
        console.error('Error fetching all gallery:', error);
        return [];
    }
}

export async function createGalleryItem(formData: FormData, token: string): Promise<any> {
    const res = await fetch(`${API_URL}/gallery`, {
        method: 'POST',
        headers: { Authorization: `Bearer ${token}` },
        body: formData,
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to create gallery item');
    return res.json();
}

export async function updateGalleryItem(id: number, formData: FormData, token: string): Promise<any> {
    const res = await fetch(`${API_URL}/gallery/${id}`, {
        method: 'PUT',
        headers: { Authorization: `Bearer ${token}` },
        body: formData,
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to update gallery item');
    return res.json();
}

export async function deleteGalleryItem(id: number, token: string): Promise<void> {
    const res = await fetch(`${API_URL}/gallery/${id}`, {
        method: 'DELETE',
        headers: { Authorization: `Bearer ${token}` },
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to delete gallery item');
}

// ─── Vacancies ───────────────────────────────────────────────────────────────

export async function fetchVacancies(params?: { department?: string, type?: string, active?: string }): Promise<Vacancy[]> {
    try {
        const url = new URL(`${API_URL}/vacancies`);
        if (params?.department) url.searchParams.append('department', params.department);
        if (params?.type) url.searchParams.append('type', params.type);
        if (params?.active) url.searchParams.append('active', params.active);

        const res = await fetch(url.toString(), { cache: 'no-store' });
        if (!res.ok) return [];
        return res.json();
    } catch (error) {
        console.error('Error fetching vacancies:', error);
        return [];
    }
}

export async function fetchVacancyById(id: string): Promise<Vacancy | null> {
    try {
        const res = await fetch(`${API_URL}/vacancies/${id}`, { cache: 'no-store' });
        if (!res.ok) return null;
        return res.json();
    } catch (error) {
        console.error('Error fetching vacancy:', error);
        return null;
    }
}

export async function createVacancy(data: Partial<Vacancy>, token: string): Promise<any> {
    const res = await fetch(`${API_URL}/vacancies`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify(data),
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to create vacancy');
    return res.json();
}

export async function updateVacancy(id: number, data: Partial<Vacancy>, token: string): Promise<any> {
    const res = await fetch(`${API_URL}/vacancies/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify(data),
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to update vacancy');
    return res.json();
}

export async function deleteVacancy(id: number, token: string): Promise<void> {
    const res = await fetch(`${API_URL}/vacancies/${id}`, {
        method: 'DELETE',
        headers: { Authorization: `Bearer ${token}` },
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to delete vacancy');
}

// ─── Tenders ─────────────────────────────────────────────────────────────────

export async function fetchTenders(status?: string): Promise<Tender[]> {
    try {
        const url = new URL(`${API_URL}/tenders`);
        if (status) url.searchParams.append('status', status);

        const res = await fetch(url.toString(), { cache: 'no-store' });
        if (!res.ok) return [];
        return res.json();
    } catch (error) {
        console.error('Error fetching tenders:', error);
        return [];
    }
}

export async function createTender(formData: FormData, token: string): Promise<any> {
    const res = await fetch(`${API_URL}/tenders`, {
        method: 'POST',
        headers: { Authorization: `Bearer ${token}` },
        body: formData,
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to create tender');
    return res.json();
}

export async function updateTender(id: number, formData: FormData, token: string): Promise<any> {
    const res = await fetch(`${API_URL}/tenders/${id}`, {
        method: 'PUT',
        headers: { Authorization: `Bearer ${token}` },
        body: formData,
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to update tender');
    return res.json();
}

export async function deleteTender(id: number, token: string): Promise<void> {
    const res = await fetch(`${API_URL}/tenders/${id}`, {
        method: 'DELETE',
        headers: { Authorization: `Bearer ${token}` },
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to delete tender');
}

// ─────────────────────────────────────────
// Projects
// ─────────────────────────────────────────

export interface Project {
    id: number;
    title_en: string;
    description_en: string;
    location_en: string;
    start_date: string;
    end_date: string | null;
    status: 'Planning' | 'In Progress' | 'On Hold' | 'Completed' | 'Cancelled';
    budget: number | null;
    budget_currency: string;
    progress: number;
    contractor: string | null;
    funding_source: string | null;
    is_published: boolean;
    cover_image_url: string | null;
    created_at: string;
    updated_at: string;
}

export async function fetchProjects(status?: string): Promise<Project[]> {
    const params = status ? `?status=${status}` : '';
    const res = await fetch(`${API_URL}/projects${params}`, { cache: 'no-store' });
    if (!res.ok) return [];
    return res.json();
}

export async function fetchAllProjectsAdmin(token: string): Promise<Project[]> {
    const res = await fetch(`${API_URL}/projects/admin/all`, {
        headers: { Authorization: `Bearer ${token}` },
        cache: 'no-store',
    });
    if (!res.ok) return [];
    return res.json();
}

export async function createProject(formData: FormData, token: string): Promise<any> {
    const res = await fetch(`${API_URL}/projects`, {
        method: 'POST',
        headers: { Authorization: `Bearer ${token}` },
        body: formData,
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to create project');
    return res.json();
}

export async function updateProject(id: number, formData: FormData, token: string): Promise<any> {
    const res = await fetch(`${API_URL}/projects/${id}`, {
        method: 'PUT',
        headers: { Authorization: `Bearer ${token}` },
        body: formData,
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to update project');
    return res.json();
}

export async function deleteProject(id: number, token: string): Promise<void> {
    const res = await fetch(`${API_URL}/projects/${id}`, {
        method: 'DELETE',
        headers: { Authorization: `Bearer ${token}` },
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to delete project');
}

// ─────────────────────────────────────────
// Admin Message
// ─────────────────────────────────────────

export interface AdminMessage {
    id: number;
    name: string;
    title_position: string;
    message_en: string;
    message_am?: string;
    message_or?: string;
    photo_url: string | null;
    is_active: boolean;
    updated_at: string;
}

export async function fetchAdminMessage(): Promise<AdminMessage | null> {
    try {
        const res = await fetch(`${API_URL}/admin-message`, { cache: 'no-store' });
        if (!res.ok) return null;
        return res.json();
    } catch {
        return null;
    }
}

export async function updateAdminMessage(formData: FormData, token: string): Promise<any> {
    const res = await fetch(`${API_URL}/admin-message`, {
        method: 'PUT',
        headers: { Authorization: `Bearer ${token}` },
        body: formData,
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to update message');
    return res.json();
}

// ─────────────────────────────────────────
// Directory
// ─────────────────────────────────────────

export interface DirectoryContact {
    id: number;
    name_en: string;
    name_am?: string;
    name_or?: string;
    position_en: string;
    position_am?: string;
    position_or?: string;
    department_en?: string;
    phone?: string;
    email?: string;
    office_location?: string;
    photo_url?: string;
    category: string;
    sort_order: number;
    created_at: string;
}

export async function fetchDirectory(category?: string): Promise<DirectoryContact[]> {
    const params = category && category !== 'All' ? `?category=${category}` : '';
    const res = await fetch(`${API_URL}/directory${params}`, { cache: 'no-store' });
    if (!res.ok) return [];
    return res.json();
}

export async function createContact(formData: FormData, token: string): Promise<any> {
    const res = await fetch(`${API_URL}/directory`, {
        method: 'POST',
        headers: { Authorization: `Bearer ${token}` },
        body: formData,
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to create contact');
    return res.json();
}

export async function updateContact(id: number, formData: FormData, token: string): Promise<any> {
    const res = await fetch(`${API_URL}/directory/${id}`, {
        method: 'PUT',
        headers: { Authorization: `Bearer ${token}` },
        body: formData,
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to update contact');
    return res.json();
}

export async function deleteContact(id: number, token: string): Promise<void> {
    const res = await fetch(`${API_URL}/directory/${id}`, {
        method: 'DELETE',
        headers: { Authorization: `Bearer ${token}` },
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to delete contact');
}

// ─────────────────────────────────────────
// Investment
// ─────────────────────────────────────────

export interface InvestmentOpportunity {
    id: number;
    title_en: string;
    title_am?: string;
    title_or?: string;
    description_en?: string;
    description_am?: string;
    description_or?: string;
    category?: string;
    location?: string;
    budget?: string;
    incentives_en?: string;
    incentives_am?: string;
    incentives_or?: string;
    contact_name?: string;
    contact_phone?: string;
    contact_email?: string;
    thumbnail_url?: string;
    status: 'Open' | 'In Progress' | 'Closed';
    created_at: string;
}

export async function fetchInvestments(category?: string): Promise<InvestmentOpportunity[]> {
    const params = category && category !== 'All' ? `?category=${category}` : '';
    const res = await fetch(`${API_URL}/investments${params}`, { cache: 'no-store' });
    if (!res.ok) return [];
    return res.json();
}

export async function createInvestment(formData: FormData, token: string): Promise<any> {
    const res = await fetch(`${API_URL}/investments`, {
        method: 'POST',
        headers: { Authorization: `Bearer ${token}` },
        body: formData,
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to create investment');
    return res.json();
}

export async function updateInvestment(id: number, formData: FormData, token: string): Promise<any> {
    const res = await fetch(`${API_URL}/investments/${id}`, {
        method: 'PUT',
        headers: { Authorization: `Bearer ${token}` },
        body: formData,
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to update investment');
    return res.json();
}

export async function deleteInvestment(id: number, token: string): Promise<void> {
    const res = await fetch(`${API_URL}/investments/${id}`, {
        method: 'DELETE',
        headers: { Authorization: `Bearer ${token}` },
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to delete investment');
}

// ─────────────────────────────────────────
// Contact
// ─────────────────────────────────────────

export interface ContactMessage {
    id?: number;
    name: string;
    email: string;
    phone?: string;
    subject?: string;
    message: string;
    status?: 'new' | 'read' | 'replied';
    created_at?: string;
}

export async function submitContactForm(data: ContactMessage): Promise<{ message: string }> {
    const res = await fetch(`${API_URL}/contact`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    });
    const result = await res.json();
    if (!res.ok) throw new Error(result.message || 'Failed to submit contact form');
    return result;
}

export async function fetchContactMessages(token: string): Promise<ContactMessage[]> {
    try {
        const res = await fetch(`${API_URL}/contact`, {
            headers: { Authorization: `Bearer ${token}` },
            cache: 'no-store',
        });
        if (!res.ok) return [];
        return res.json();
    } catch (error) {
        console.error('Error fetching contact messages:', error);
        return [];
    }
}

// ─────────────────────────────────────────
// Office Settings
// ─────────────────────────────────────────

export interface OfficeSettings {
    id: number;
    phone: string;
    email: string;
    address: string;
    working_hours?: string;
    map_url?: string;
    facebook_url?: string;
    twitter_url?: string;
    linkedin_url?: string;
    youtube_url?: string;
    updated_at: string;
}

export async function fetchOfficeSettings(): Promise<OfficeSettings | null> {
    try {
        const res = await fetch(`${API_URL}/settings`, { cache: 'no-store' });
        if (!res.ok) return null;
        return res.json();
    } catch (error) {
        console.error('Error fetching office settings:', error);
        return null;
    }
}

export async function updateOfficeSettings(data: Partial<OfficeSettings>, token: string): Promise<any> {
    const res = await fetch(`${API_URL}/settings`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            Authorization: `Bearer ${token}`
        },
        body: JSON.stringify(data),
    });
    if (!res.ok) throw new Error((await res.json()).message || 'Failed to update settings');
    return res.json();
}
