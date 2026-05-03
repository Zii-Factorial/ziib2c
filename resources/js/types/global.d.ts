import type { Auth } from '@/types/auth';

declare module '@inertiajs/core' {
    export interface InertiaConfig {
        sharedPageProps: {
            name: string;
            auth: Auth;
            locale: {
                current: string;
                default: string;
                fallback: string;
                supported: {
                    code: string;
                    name: string;
                    native: string;
                    url: string;
                }[];
            };
            sidebarOpen: boolean;
            [key: string]: unknown;
        };
    }
}
