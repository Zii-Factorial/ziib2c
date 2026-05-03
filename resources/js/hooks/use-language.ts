import { router, usePage } from '@inertiajs/react';
import { useSyncExternalStore } from 'react';
import { useTranslation } from 'react-i18next';
import {
    PREFERENCE_STORAGE_KEYS,
    setPreferenceCookie,
} from '@/config/preferences';
import type { Language } from '@/domains/locale';
import { DEFAULT_LANGUAGE, isLanguage } from '@/domains/locale';
import i18n from '@/i18n';
import type { TranslationKey } from '@/locales';
import { addUrlDefault } from '@/wayfinder';

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

    const language = window.localStorage.getItem(
        PREFERENCE_STORAGE_KEYS.language,
    );

    return isLanguage(language) ? language : DEFAULT_LANGUAGE;
};

const applyLanguage = (language: Language): void => {
    if (typeof document === 'undefined') {
        return;
    }

    document.documentElement.lang = language;
    addUrlDefault('locale', language);
    void i18n.changeLanguage(language);
};

export function initializeLanguage(): void {
    currentLanguage = getStoredLanguage();
    applyLanguage(currentLanguage);
}

const syncLanguage = (language: Language): void => {
    currentLanguage = language;
    window.localStorage.setItem(PREFERENCE_STORAGE_KEYS.language, language);
    setPreferenceCookie(PREFERENCE_STORAGE_KEYS.language, language);
    setPreferenceCookie('locale', language);
    applyLanguage(language);
    notify();
};

export function useLanguage() {
    const { locale } = usePage().props;
    const { t: translate } = useTranslation();
    const language = useSyncExternalStore<Language>(
        subscribe,
        () => {
            const pageLanguage = isLanguage(locale.current)
                ? locale.current
                : DEFAULT_LANGUAGE;

            if (currentLanguage !== pageLanguage) {
                currentLanguage = pageLanguage;
                applyLanguage(pageLanguage);
            }

            return currentLanguage;
        },
        () => DEFAULT_LANGUAGE,
    );

    const updateLanguage = (value: Language): void => {
        syncLanguage(value);

        const supportedLocale = locale.supported.find(
            (item) => item.code === value,
        );

        if (supportedLocale) {
            router.visit(supportedLocale.url);
        }
    };

    const t = (key: TranslationKey): string => translate(key);

    return { language, updateLanguage, t } as const;
}

export type { Language, TranslationKey };
