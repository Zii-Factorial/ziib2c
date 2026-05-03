import type {
    ThemeColor,
    ThemeColorDefinition,
    ThemePreferences,
    ThemeRadius,
} from './type';

export const DEFAULT_THEME_PREFERENCES: ThemePreferences = {
    color: 'slate',
    radius: 'default',
};

export const THEME_COLOR_DEFINITIONS: Record<ThemeColor, ThemeColorDefinition> =
    {
        slate: {
            primary: 'oklch(0.205 0 0)',
            primaryForeground: 'oklch(0.985 0 0)',
            ring: 'oklch(0.556 0 0)',
        },
        blue: {
            primary: 'oklch(0.55 0.21 260)',
            primaryForeground: 'oklch(0.985 0 0)',
            ring: 'oklch(0.68 0.17 250)',
        },
        green: {
            primary: 'oklch(0.55 0.15 150)',
            primaryForeground: 'oklch(0.985 0 0)',
            ring: 'oklch(0.68 0.14 150)',
        },
        rose: {
            primary: 'oklch(0.58 0.2 20)',
            primaryForeground: 'oklch(0.985 0 0)',
            ring: 'oklch(0.7 0.16 20)',
        },
        orange: {
            primary: 'oklch(0.62 0.18 55)',
            primaryForeground: 'oklch(0.145 0 0)',
            ring: 'oklch(0.74 0.16 60)',
        },
    };

export const THEME_RADIUS_DEFINITIONS: Record<ThemeRadius, string> = {
    compact: '0.25rem',
    default: '0.625rem',
    rounded: '1rem',
};

export const THEME_COLORS: {
    value: ThemeColor;
    labelKey:
        | 'settings.theme.slate'
        | 'settings.theme.blue'
        | 'settings.theme.green'
        | 'settings.theme.rose'
        | 'settings.theme.orange';
    swatch: string;
}[] = [
    { value: 'slate', labelKey: 'settings.theme.slate', swatch: 'bg-zinc-900' },
    { value: 'blue', labelKey: 'settings.theme.blue', swatch: 'bg-blue-600' },
    {
        value: 'green',
        labelKey: 'settings.theme.green',
        swatch: 'bg-emerald-600',
    },
    { value: 'rose', labelKey: 'settings.theme.rose', swatch: 'bg-rose-600' },
    {
        value: 'orange',
        labelKey: 'settings.theme.orange',
        swatch: 'bg-orange-500',
    },
];

export const THEME_RADII: {
    value: ThemeRadius;
    labelKey:
        | 'settings.theme.compact'
        | 'settings.theme.default'
        | 'settings.theme.rounded';
}[] = [
    { value: 'compact', labelKey: 'settings.theme.compact' },
    { value: 'default', labelKey: 'settings.theme.default' },
    { value: 'rounded', labelKey: 'settings.theme.rounded' },
];
