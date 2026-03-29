@extends('layouts.reader')
@section('title', ($document->title_en ?? 'Document') . ' — OSZA Reader')


@section('content')
    @php
        $locale = session('locale', 'en');
        $title = $document->{'title_' . $locale} ?? $document->title_en ?? 'Document';
        $fileUrl = asset($document->file_url);
    @endphp

    {{-- ── Reader Toolbar ─────────────────────────────────────────────────────── --}}
    <div class="h-16 bg-white border-b border-gray-100 shadow-sm flex items-center justify-between px-6 flex-shrink-0 z-10">
        <div class="flex items-center gap-4">
            <a href="{{ route('documents.index') }}"
                class="flex items-center gap-2 text-gray-400 hover:text-blue-600 transition text-xs font-black uppercase tracking-widest group">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Library
            </a>
            <div class="h-5 w-px bg-gray-100"></div>
            <h1 class="text-sm font-black text-gray-900 italic tracking-tight truncate max-w-lg">{{ $title }}</h1>
            @if($document->category)
                <span
                    class="hidden md:inline-block px-3 py-1 bg-blue-50 text-blue-600 text-[9px] font-black uppercase tracking-widest rounded-full">
                    {{ $document->category }}
                </span>
            @endif
        </div>
        <div class="flex items-center gap-3">
            @if($document->pages)
                <span class="hidden sm:flex text-[10px] font-bold text-gray-400 italic">{{ $document->pages }} pages</span>
            @endif
            <a href="{{ $fileUrl }}" download
                class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 transition shadow-lg shadow-blue-500/20 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download
            </a>
            <a href="{{ $fileUrl }}" target="_blank"
                class="flex items-center gap-2 px-5 py-2.5 bg-gray-50 text-gray-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-100 transition active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                Open Tab
            </a>
        </div>
    </div>

    {{-- ── PDF Embed ───────────────────────────────────────────────────────────── --}}
    <div class="reader-container bg-gray-100">
        {{-- Try native browser PDF viewer (works for all PDF files hosted on the same domain or with CORS) --}}
        @php
            $ext = strtolower(pathinfo($document->file_url, PATHINFO_EXTENSION));
        @endphp

        @if(in_array($ext, ['pdf', '']))
            <iframe src="{{ $fileUrl }}#toolbar=1&navpanes=1&scrollbar=1" class="w-full h-full border-0" title="{{ $title }}">
                <div class="flex flex-col items-center justify-center h-full bg-gray-100"
                    style="padding: 4rem; text-align:center">
                    <svg class="w-16 h-16 text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <p style="font-weight:900;font-size:0.75rem;letter-spacing:0.2em;text-transform:uppercase;color:#9ca3af">
                        Your browser doesn't support inline PDF viewing.</p>
                    <a href="{{ $fileUrl }}" target="_blank"
                        style="margin-top:1rem;padding:0.75rem 2rem;background:#2563eb;color:white;border-radius:9999px;font-weight:900;font-size:0.625rem;letter-spacing:0.15em;text-transform:uppercase;text-decoration:none;">
                        Open in New Tab
                    </a>
                </div>
            </iframe>
        @else
            {{-- Non-PDF file (docx, xlsx, etc.) - use Microsoft Office Online Viewer --}}
            @php $viewerUrl = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode($fileUrl); @endphp
            <iframe src="{{ $viewerUrl }}" class="w-full h-full border-0" title="{{ $title }}">
            </iframe>
        @endif
    </div>
@endsection