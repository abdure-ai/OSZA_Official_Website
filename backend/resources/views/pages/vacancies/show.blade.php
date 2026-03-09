@extends('layouts.app')
@section('title', ($vacancy->title_en) . ' — OSZA')
@section('content')
    @php $locale = session('locale', 'en'); @endphp

    <section class="bg-gray-50 min-h-screen pb-20 pt-10">
        <div class="max-w-[1440px] mx-auto px-4">
            {{-- Breadcrumb --}}
            <nav class="flex mb-8 text-xs font-black uppercase tracking-widest text-gray-400">
                <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Home</a>
                <span class="mx-3">/</span>
                <a href="{{ route('vacancies.index') }}" class="hover:text-blue-600 transition">Careers</a>
                <span class="mx-3">/</span>
                <span class="text-blue-900 italic">Job Detail</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                {{-- Main Content --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[3rem] p-8 md:p-12 shadow-2xl shadow-blue-900/5 border border-gray-100">
                        <div class="flex flex-wrap items-center gap-4 mb-8">
                            <span
                                class="bg-blue-50 text-blue-600 text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest shadow-sm">
                                {{ $vacancy->department }}
                            </span>
                            <span
                                class="bg-gray-50 text-gray-400 px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest">
                                {{ $vacancy->vacancy_type ?: 'Full-time' }}
                            </span>
                        </div>

                        <h1 class="text-3xl md:text-5xl font-black text-gray-900 mb-8 italic tracking-tight leading-none">
                            {{ $vacancy->{'title_' . $locale} ?? $vacancy->title_en }}
                        </h1>

                        <div class="mb-12">
                            <h2
                                class="text-sm font-black text-blue-900 uppercase tracking-widest mb-6 flex items-center gap-3">
                                <span class="w-8 h-px bg-blue-900"></span> Role Description
                            </h2>
                            <div class="prose prose-blue max-w-none text-gray-600 font-medium leading-relaxed">
                                {!! nl2br(e($vacancy->{'description_' . $locale} ?? $vacancy->description_en)) !!}
                            </div>
                        </div>

                        @if($vacancy->{'requirements_' . $locale} ?? $vacancy->requirements_en)
                            <div>
                                <h2
                                    class="text-sm font-black text-blue-900 uppercase tracking-widest mb-6 flex items-center gap-3">
                                    <span class="w-8 h-px bg-blue-900"></span> Candidate Requirements
                                </h2>
                                <div class="prose prose-blue max-w-none text-gray-600 font-medium leading-relaxed">
                                    {!! nl2br(e($vacancy->{'requirements_' . $locale} ?? $vacancy->requirements_en)) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-1 space-y-8">
                    {{-- Application Card --}}
                    <div class="bg-blue-900 text-white p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
                        <h3 class="text-lg font-black mb-8 italic tracking-tight relative z-10">Join the Team</h3>

                        <div class="space-y-6 relative z-10">
                            <div>
                                <span
                                    class="block text-[10px] font-black uppercase tracking-widest opacity-40 mb-1">Applcation
                                    Deadline</span>
                                <span class="text-lg font-bold text-[#f5a623]">
                                    {{ $vacancy->deadline instanceof \Carbon\Carbon ? $vacancy->deadline->format('F d, Y') : $vacancy->deadline }}
                                </span>
                            </div>

                            <div>
                                <span class="block text-[10px] font-black uppercase tracking-widest opacity-40 mb-1">Work
                                    Location</span>
                                <span class="text-lg font-bold">
                                    {{ $vacancy->{'location_' . $locale} ?? $vacancy->location_en ?? 'Zone-wide' }}
                                </span>
                            </div>

                            @if($vacancy->document_url)
                                <div class="pt-6 border-t border-white/10">
                                    <a href="{{ config('app.url') . $vacancy->document_url }}" download
                                        class="block w-full text-center bg-[#f5a623] text-blue-900 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:scale-105 transition-all shadow-xl shadow-black/20">
                                        Apply for Position
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Future Ops Card --}}
                    <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-xl">
                        <h4 class="text-sm font-black text-blue-900 uppercase tracking-widest mb-4">Talent Database</h4>
                        <p class="text-xs text-gray-500 font-medium leading-relaxed mb-6">
                            Even if this isn't the perfect role for you, share your credentials for future strategic
                            opportunities.
                        </p>
                        <a href="/contact"
                            class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline">Contact
                            HR →</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection