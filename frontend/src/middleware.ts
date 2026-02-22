import { NextResponse } from 'next/server';
import type { NextRequest } from 'next/server';

const PROTECTED_PATHS = ['/admin/dashboard', '/admin/news', '/admin/documents', '/admin/users', '/admin/alerts'];

export function middleware(request: NextRequest) {
    const { pathname } = request.nextUrl;

    // Check if the path is a protected admin route
    const isProtected = PROTECTED_PATHS.some((path) => pathname.startsWith(path));

    if (isProtected) {
        const token = request.cookies.get('adminToken')?.value;

        if (!token) {
            const loginUrl = new URL('/admin/login', request.url);
            loginUrl.searchParams.set('redirect', pathname);
            return NextResponse.redirect(loginUrl);
        }
    }

    return NextResponse.next();
}

export const config = {
    matcher: ['/admin/:path*'],
};
