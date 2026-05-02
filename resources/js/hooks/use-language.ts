import { useSyncExternalStore } from 'react';
import {
    PREFERENCE_STORAGE_KEYS,
    setPreferenceCookie,
} from '@/config/preferences';
import type { Language } from '@/domains/locale';
import { DEFAULT_LANGUAGE, isLanguage } from '@/domains/locale';
import type { TranslationKey } from '@/locales';
import { messages } from '@/locales';

const listeners = new Set<() => void>();
let currentLanguage: Language = DEFAULT_LANGUAGE;

const subscribe = (callback: () => void) => {
    listeners.add(callback);

    return () => listeners.delete(callback);
};

const notify = (): void => listeners.forEach((listener) => listener());

const getStoredLanguage = (): Language => {
    if (typeof window === 'undefined') {
        return DEFAULT_LANGUAGE;
    }

    const language = localStorage.getItem(PREFERENCE_STORAGE_KEYS.language);

    return isLanguage(language) ? language : DEFAULT_LANGUAGE;
};

const applyLanguage = (language: Language): void => {
    if (typeof document === 'undefined') {
        return;
    }

    document.documentElement.lang = language;
};

export function initializeLanguage(): void {
    currentLanguage = getStoredLanguage();
    applyLanguage(currentLanguage);
}

export function useLanguage() {
    const language = useSyncExternalStore<Language>(
        subscribe,
        () => currentLanguage,
        () => DEFAULT_LANGUAGE,
    );

    const updateLanguage = (value: Language): void => {
        currentLanguage = value;
        localStorage.setItem(PREFERENCE_STORAGE_KEYS.language, value);
        setPreferenceCookie(PREFERENCE_STORAGE_KEYS.language, value);
        applyLanguage(value);
        notify();
    };

    const t = (key: TranslationKey): string => messages[language][key];

    return { language, updateLanguage, t } as const;
}

export type { Language, TranslationKey };
