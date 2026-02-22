import Hero from '@/components/home/Hero';
import QuickAccess from '@/components/home/QuickAccess';
import AdminMessage from '@/components/home/AdminMessage';
import NewsGrid from '@/components/home/NewsGrid';
import WoredaGrid from '@/components/home/WoredaGrid';
import HomeGallery from '@/components/home/HomeGallery';

export default function Home() {
    return (
        <>
            <Hero />
            <QuickAccess />
            <AdminMessage />
            <NewsGrid />
            <WoredaGrid />
            <HomeGallery />
        </>
    )
}
