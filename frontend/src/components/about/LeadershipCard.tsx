import Image from 'next/image';

interface LeadershipCardProps {
    name: string;
    role: string;
    bio: string;
    image?: string;
}

export default function LeadershipCard({ name, role, bio, image }: LeadershipCardProps) {
    return (
        <div className="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow border border-gray-100 flex flex-col items-center text-center p-6">
            <div className="w-32 h-32 rounded-full overflow-hidden mb-4 bg-gray-200 relative border-4 border-gray-50">
                {/* In production, use next/image with the prop */}
                {image ? (
                    <div className="w-full h-full bg-cover bg-center" style={{ backgroundImage: `url(${image})` }}></div>
                ) : (
                    <div className="w-full h-full flex items-center justify-center text-gray-400">
                        <span className="text-4xl">👤</span>
                    </div>
                )}
            </div>

            <h3 className="text-xl font-bold text-gray-900 mb-1">{name}</h3>
            <p className="text-primary font-medium text-sm mb-4 uppercase tracking-wide">{role}</p>

            <p className="text-gray-500 text-sm leading-relaxed line-clamp-4">
                {bio}
            </p>

            <button className="mt-4 text-primary text-sm font-semibold hover:underline">Read Full Bio</button>
        </div>
    );
}
