import type { Language } from './type';

export function isLanguage(value: string | null): value is Language {
    return value === 'en' || value === 'km';
}
