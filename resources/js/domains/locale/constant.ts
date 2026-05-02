import type { Language } from './type';

export const DEFAULT_LANGUAGE: Language = 'en';

export const LANGUAGES: {
    value: Language;
    labelKey: 'settings.language.english' | 'settings.language.khmer';
}[] = [
    { value: 'en', labelKey: 'settings.language.english' },
    { value: 'km', labelKey: 'settings.language.khmer' },
];
