'use client';
import { useEffect, useCallback } from 'react';
import { FaTimes, FaChevronLeft, FaChevronRight } from 'react-icons/fa';

const BACKEND_URL = process.env.NEXT_PUBLIC_BACKEND_URL || 'http://localhost:5000';

interface GalleryImage {
    id: number;
    title: string;
    image_url: string;
}

interface Props {
    images: GalleryImage[];
    currentIndex: number;
    onClose: () => void;
    onNext: () => void;
    onPrev: () => void;
}

export default function GallerySlideshow({ images, currentIndex, onClose, onNext, onPrev }: Props) {
    const handleKeyDown = useCallback((e: KeyboardEvent) => {
        if (e.key === 'Escape') onClose();
        if (e.key === 'ArrowRight') onNext();
        if (e.key === 'ArrowLeft') onPrev();
    }, [onClose, onNext, onPrev]);

    useEffect(() => {
        document.addEventListener('keydown', handleKeyDown);
        document.body.style.overflow = 'hidden';
        return () => {
            document.removeEventListener('keydown', handleKeyDown);
            document.body.style.overflow = '';
        };
    }, [handleKeyDown]);

    if (!images.length) return null;
    const current = images[currentIndex];

    return (
        <div
            className="fixed inset-0 z-50 flex items-center justify-center bg-black/95"
            onClick={onClose}
        >
            {/* Close button */}
            <button
                onClick={onClose}
                className="absolute top-4 right-4 text-white/70 hover:text-white z-10 bg-white/10 hover:bg-white/20 rounded-full p-2 transition"
                aria-label="Close slideshow"
            >
                <FaTimes size={20} />
            </button>

            {/* Counter */}
            <div className="absolute top-4 left-1/2 -translate-x-1/2 text-white/60 text-sm font-medium z-10 bg-black/40 px-3 py-1 rounded-full">
                {currentIndex + 1} / {images.length}
            </div>

            {/* Previous button */}
            {images.length > 1 && (
                <button
                    onClick={(e) => { e.stopPropagation(); onPrev(); }}
                    className="absolute left-4 text-white/70 hover:text-white z-10 bg-white/10 hover:bg-white/20 rounded-full p-3 transition"
                    aria-label="Previous image"
                >
                    <FaChevronLeft size={22} />
                </button>
            )}

            {/* Image */}
            <div
                className="flex flex-col items-center gap-4 max-w-4xl w-full px-16"
                onClick={(e) => e.stopPropagation()}
            >
                {/* eslint-disable-next-line @next/next/no-img-element */}
                <img
                    key={current.id}
                    src={`${BACKEND_URL}${current.image_url}`}
                    alt={current.title}
                    className="max-h-[75vh] max-w-full object-contain rounded-lg shadow-2xl animate-fade-in"
                    style={{ animation: 'fadeIn 0.3s ease-in-out' }}
                />
                <p className="text-white text-center font-medium text-lg">{current.title}</p>
            </div>

            {/* Next button */}
            {images.length > 1 && (
                <button
                    onClick={(e) => { e.stopPropagation(); onNext(); }}
                    className="absolute right-4 text-white/70 hover:text-white z-10 bg-white/10 hover:bg-white/20 rounded-full p-3 transition"
                    aria-label="Next image"
                >
                    <FaChevronRight size={22} />
                </button>
            )}

            <style jsx>{`
                @keyframes fadeIn {
                    from { opacity: 0; transform: scale(0.97); }
                    to   { opacity: 1; transform: scale(1); }
                }
            `}</style>
        </div>
    );
}
