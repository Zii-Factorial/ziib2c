export type ThemeColor = 'slate' | 'blue' | 'green' | 'rose' | 'orange';
export type ThemeRadius = 'compact' | 'default' | 'rounded';

export type ThemePreferences = {
    color: ThemeColor;
    radius: ThemeRadius;
};

export type ThemeColorDefinition = {
    primary: string;
    primaryForeground: string;
    ring: string;
};
