'use client';
import { useState, useEffect } from 'react';
import { FaBullhorn, FaTimes, FaInfoCircle, FaExclamationTriangle } from 'react-icons/fa';
import { fetchActiveAlerts, AlertItem } from '@/lib/api';

const severityConfig: Record<string, { bg: string; icon: React.ReactNode }> = {
    critical: { bg: 'bg-red-600', icon: <FaBullhorn className="text-xl animate-pulse" /> },
    warning: { bg: 'bg-amber-500', icon: <FaExclamationTriangle className="text-xl" /> },
    info: { bg: 'bg-blue-600', icon: <FaInfoCircle className="text-xl" /> },
};

export default function EmergencyAlert() {
    const [alerts, setAlerts] = useState<AlertItem[]>([]);
    const [dismissed, setDismissed] = useState<Set<number>>(new Set());

    useEffect(() => {
        fetchActiveAlerts().then(setAlerts);
    }, []);

    // Filter out dismissed alerts
    const visible = alerts.filter((a) => !dismissed.has(a.id));
    if (visible.length === 0) return null;

    return (
        <>
            {visible.map((alert) => {
                const config = severityConfig[alert.severity] || severityConfig.info;
                return (
                    <div key={alert.id} className={`${config.bg} text-white px-4 py-3 shadow-md relative z-50`}>
                        <div className="container mx-auto flex items-center justify-between">
                            <div className="flex items-center gap-3">
                                {config.icon}
                                <span className="font-bold">{alert.message_en}</span>
                            </div>
                            <button
                                onClick={() => setDismissed((prev) => new Set(prev).add(alert.id))}
                                className="text-white hover:text-gray-200 transition-colors"
                                aria-label="Close alert"
                            >
                                <FaTimes size={20} />
                            </button>
                        </div>
                    </div>
                );
            })}
        </>
    );
}
