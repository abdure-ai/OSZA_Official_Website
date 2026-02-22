'use client';
import { useState, useEffect, useCallback } from 'react';
import Link from 'next/link';
import { FaChevronLeft, FaChevronRight, FaCircle } from 'react-icons/fa';
import { useTranslation } from 'react-i18next';

const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:5000/api';
const BACKEND_URL = process.env.NEXT_PUBLIC_BACKEND_URL || 'http://localhost:5000';
const SLIDE_INTERVAL = 8000; // 8 seconds for image slides

interface HeroSlide {
    id: number;
    title_en: string;
    subtitle_en: string;
    media_url: string;
    media_type: 'image' | 'video';
    cta_text: string;
    cta_url: string;
    sort_order: number;
    is_active: boolean;
}

export default function Hero() {
    const { t, i18n } = useTranslation();
    const currentLang = i18n.language;

    const FALLBACK_SLIDE: HeroSlide = {
        id: 0,
        title_en: t('hero_title', 'Building a Prosperous & United Community'),
        subtitle_en: t('hero_subtitle', 'Welcome to the official portal of the Oromo Special Zone Administration. Access government services, latest updates, and development reports transparently.'),
        media_url: '',
        media_type: 'image',
        cta_text: t('our_services', 'Our Services'),
        cta_url: '/services',
        sort_order: 0,
        is_active: true,
    };

    const [slides, setSlides] = useState<HeroSlide[]>([FALLBACK_SLIDE]);
    const [current, setCurrent] = useState(0);
    const [isAnimating, setIsAnimating] = useState(false);

    // Fetch live slides from API
    useEffect(() => {
        fetch(`${API_URL}/hero`, { cache: 'no-store' })
            .then((r) => (r.ok ? r.json() : []))
            .then((data: HeroSlide[]) => {
                if (Array.isArray(data) && data.length > 0) {
                    setSlides(data);
                }
            })
            .catch(() => {/* keep fallback */ });
    }, []);

    const goTo = useCallback(
        (index: number) => {
            if (isAnimating) return;
            setIsAnimating(true);
            setCurrent((index + slides.length) % slides.length);
            setTimeout(() => setIsAnimating(false), 500);
        },
        [isAnimating, slides.length],
    );

    const next = useCallback(() => goTo(current + 1), [current, goTo]);
    const prev = useCallback(() => goTo(current - 1), [current, goTo]);

    // Auto-advance: image slides use a fixed interval;
    // video slides wait for the `onEnded` event instead (no timer).
    const isVideoSlide = slides[current]?.media_type === 'video' && !!slides[current]?.media_url;
    useEffect(() => {
        if (slides.length <= 1 || isVideoSlide) return;
        const timer = setTimeout(next, SLIDE_INTERVAL);
        return () => clearTimeout(timer);
    }, [slides.length, current, isVideoSlide, next]);

    const slide = slides[current];
    const hasMedia = slide.media_url && slide.media_url.length > 0;

    return (
        <div className="relative bg-primary text-white overflow-hidden" style={{ minHeight: '600px' }}>
            {/* Background: media or gradient */}
            <div className="absolute inset-0">
                {hasMedia && slide.media_type === 'video' ? (
                    <video
                        key={slide.id}
                        src={`${BACKEND_URL}${slide.media_url}`}
                        className="w-full h-full object-cover"
                        autoPlay
                        muted
                        playsInline
                        onEnded={() => slides.length > 1 && next()}
                    />
                ) : hasMedia ? (
                    // eslint-disable-next-line @next/next/no-img-element
                    <img
                        key={slide.id}
                        src={`${BACKEND_URL}${slide.media_url}`}
                        alt={slide.title_en}
                        className="w-full h-full object-cover transition-opacity duration-500"
                    />
                ) : (
                    <div className="absolute inset-0 bg-blue-900" />
                )}
            </div>

            {/* Slide Content */}
            <div className="container mx-auto px-4 py-24 md:py-32 relative z-10">
                <div
                    key={slide.id}
                    className={`max-w-3xl transition-all duration-500 ${isAnimating ? 'opacity-0 translate-y-2' : 'opacity-100 translate-y-0'}`}
                >
                    <h1 className="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                        {(() => {
                            const title = (slide as any)[`title_${currentLang}`] || slide.title_en;
                            if (title.includes('&')) {
                                return (
                                    <>
                                        {title.split('&')[0].trim()} &{' '}
                                        <span className="text-accent">
                                            {title.split('&')[1]?.trim()}
                                        </span>
                                    </>
                                );
                            }
                            return title;
                        })()}
                    </h1>
                    <p className="text-lg md:text-xl mb-8 text-gray-200 leading-relaxed">
                        {(slide as any)[`subtitle_${currentLang}`] || slide.subtitle_en}
                    </p>
                    {slide.cta_text && slide.cta_url && (
                        <div className="flex flex-wrap gap-4">
                            <Link
                                href={slide.cta_url}
                                className="bg-accent text-blue-900 font-bold py-3 px-8 rounded-full hover:bg-yellow-400 transition transform hover:scale-105 shadow-lg"
                            >
                                {(slide as any)[`cta_text_${currentLang}`] || slide.cta_text}
                            </Link>
                            <Link
                                href="/about"
                                className="bg-transparent border-2 border-white text-white font-bold py-3 px-8 rounded-full hover:bg-white hover:text-blue-900 transition transform hover:scale-105"
                            >
                                {t('learn_more', 'Learn More')}
                            </Link>
                        </div>
                    )}
                </div>
            </div>

            {/* Navigation Arrows (only when multiple slides) */}
            {slides.length > 1 && (
                <>
                    <button
                        onClick={prev}
                        aria-label="Previous slide"
                        className="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-white/20 hover:bg-white/40 backdrop-blur-sm text-white rounded-full p-3 transition"
                    >
                        <FaChevronLeft />
                    </button>
                    <button
                        onClick={next}
                        aria-label="Next slide"
                        className="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-white/20 hover:bg-white/40 backdrop-blur-sm text-white rounded-full p-3 transition"
                    >
                        <FaChevronRight />
                    </button>
                </>
            )}

            {/* Dot Indicators */}
            {slides.length > 1 && (
                <div className="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex gap-2">
                    {slides.map((_, i) => (
                        <button
                            key={i}
                            onClick={() => goTo(i)}
                            aria-label={`Go to slide ${i + 1}`}
                            className={`transition-all duration-300 ${i === current
                                ? 'text-accent scale-125'
                                : 'text-white/50 hover:text-white/80'
                                }`}
                        >
                            <FaCircle size={8} />
                        </button>
                    ))}
                </div>
            )}

            {/* Bottom gradient fade */}
            <div className="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-gray-50 to-transparent z-10" />
        </div>
    );
}
