import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';
import type { Language } from '@/domains/locale';
import { DEFAULT_LANGUAGE } from '@/domains/locale';
import { messages } from '@/locales';

const resources = {
    en: { translation: messages.en },
    km: { translation: messages.km },
} satisfies Record<Language, { translation: Record<string, string> }>;

void i18n.use(initReactI18next).init({
    resources,
    lng: DEFAULT_LANGUAGE,
    fallbackLng: DEFAULT_LANGUAGE,
    interpolation: {
        escapeValue: false,
    },
});

export default i18n;
