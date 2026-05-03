import { THEME_COLOR_DEFINITIONS, THEME_RADIUS_DEFINITIONS } from './constant';
import type { ThemeColor, ThemeRadius } from './type';

export function isThemeColor(value: string | null): value is ThemeColor {
    return value !== null && value in THEME_COLOR_DEFINITIONS;
}

export function isThemeRadius(value: string | null): value is ThemeRadius {
    return value !== null && value in THEME_RADIUS_DEFINITIONS;
}
