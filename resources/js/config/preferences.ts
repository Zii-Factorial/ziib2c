export const PREFERENCE_COOKIE_DAYS = 365;

export const PREFERENCE_STORAGE_KEYS = {
    language: 'language',
    themeColor: 'theme-color',
    themeRadius: 'theme-radius',
} as const;

export function setPreferenceCookie(
    name: string,
    value: string,
    days = PREFERENCE_COOKIE_DAYS,
): void {
    if (typeof document === 'undefined') {
        return;
    }

    document.cookie = `${name}=${value};path=/;max-age=${days * 24 * 60 * 60};SameSite=Lax`;
}
