import en from './en';
import km from './km';
import type { Language } from '@/domains/locale';

type LocaleMessages = Record<keyof typeof en, string>;

export const messages = {
    en,
    km,
} satisfies Record<Language, LocaleMessages>;

export type TranslationKey = keyof typeof en;
