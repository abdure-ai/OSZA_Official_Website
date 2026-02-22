import type { Metadata } from 'next'
import { Inter } from 'next/font/google'
import './globals.css'
import Navbar from '../components/global/Navbar'
import Footer from '../components/global/Footer'
import EmergencyAlert from '../components/global/EmergencyAlert'

const inter = Inter({ subsets: ['latin'] })

export const metadata: Metadata = {
    title: 'Oromo Special Zone Administration',
    description: 'Official Website of the Oromo Special Zone Administration',
}

export default function RootLayout({
    children,
}: {
    children: React.ReactNode
}) {
    return (
        <html lang="en">
            <body className={`${inter.className} bg-gray-50 text-gray-900 min-h-screen flex flex-col`}>
                <EmergencyAlert />
                <Navbar />
                <main className="flex-grow">
                    {children}
                </main>
                <Footer />
            </body>
        </html>
    )
}
