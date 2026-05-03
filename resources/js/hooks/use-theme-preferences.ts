import { useSyncExternalStore } from 'react';
import {
    PREFERENCE_STORAGE_KEYS,
    setPreferenceCookie,
} from '@/config/preferences';
import {
    DEFAULT_THEME_PREFERENCES,
    isThemeColor,
    isThemeRadius,
    THEME_COLOR_DEFINITIONS,
    THEME_RADIUS_DEFINITIONS,
} from '@/domains/theme';
import type {
    ThemeColor,
    ThemePreferences,
    ThemeRadius,
} from '@/domains/theme';

const listeners = new Set<() => void>();
let currentPreferences: ThemePreferences = DEFAULT_THEME_PREFERENCES;

const subscribe = (callback: () => void) => {
    listeners.add(callback);

    return () => listeners.delete(callback);
};

const notify = (): void => listeners.forEach((listener) => listener());

const getStoredPreferences = (): ThemePreferences => {
    if (typeof window === 'undefined') {
        return currentPreferences;
    }

    const color = localStorage.getItem(PREFERENCE_STORAGE_KEYS.themeColor);
    const radius = localStorage.getItem(PREFERENCE_STORAGE_KEYS.themeRadius);

    return {
        color: isThemeColor(color) ? color : DEFAULT_THEME_PREFERENCES.color,
        radius: isThemeRadius(radius)
            ? radius
            : DEFAULT_THEME_PREFERENCES.radius,
    };
};

const applyPreferences = ({ color, radius }: ThemePreferences): void => {
    if (typeof document === 'undefined') {
        return;
    }

    const root = document.documentElement;
    const colorDefinition = THEME_COLOR_DEFINITIONS[color];

    root.style.setProperty('--primary', colorDefinition.primary);
    root.style.setProperty(
        '--primary-foreground',
        colorDefinition.primaryForeground,
    );
    root.style.setProperty('--ring', colorDefinition.ring);
    root.style.setProperty('--sidebar-primary', colorDefinition.primary);
    root.style.setProperty(
        '--sidebar-primary-foreground',
        colorDefinition.primaryForeground,
    );
    root.style.setProperty('--radius', THEME_RADIUS_DEFINITIONS[radius]);
};

const storePreferences = ({ color, radius }: ThemePreferences): void => {
    localStorage.setItem(PREFERENCE_STORAGE_KEYS.themeColor, color);
    localStorage.setItem(PREFERENCE_STORAGE_KEYS.themeRadius, radius);
    setPreferenceCookie(PREFERENCE_STORAGE_KEYS.themeColor, color);
    setPreferenceCookie(PREFERENCE_STORAGE_KEYS.themeRadius, radius);
};

export function initializeThemePreferences(): void {
    currentPreferences = getStoredPreferences();
    applyPreferences(currentPreferences);
}

export function useThemePreferences() {
    const preferences = useSyncExternalStore(
        subscribe,
        () => currentPreferences,
        () => currentPreferences,
    );

    const updateColor = (color: ThemeColor): void => {
        currentPreferences = { ...currentPreferences, color };
        storePreferences(currentPreferences);
        applyPreferences(currentPreferences);
        notify();
    };

    const updateRadius = (radius: ThemeRadius): void => {
        currentPreferences = { ...currentPreferences, radius };
        storePreferences(currentPreferences);
        applyPreferences(currentPreferences);
        notify();
    };

    return { ...preferences, updateColor, updateRadius } as const;
}

export type { ThemeColor, ThemeRadius };
