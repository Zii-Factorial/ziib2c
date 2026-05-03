import { Check } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { ToggleGroup, ToggleGroupItem } from '@/components/ui/toggle-group';
import type { ThemeRadius } from '@/domains/theme';
import { THEME_COLORS, THEME_RADII } from '@/domains/theme';
import { useLanguage } from '@/hooks/use-language';
import { useThemePreferences } from '@/hooks/use-theme-preferences';
import { cn } from '@/lib/utils';

export default function ThemeCustomizer() {
    const { t } = useLanguage();
    const { color, radius, updateColor, updateRadius } = useThemePreferences();

    return (
        <div className="space-y-6">
            <div className="space-y-3">
                <div className="text-sm font-medium">
                    {t('settings.theme.color')}
                </div>
                <div className="grid grid-cols-2 gap-2 sm:grid-cols-5">
                    {THEME_COLORS.map((item) => (
                        <Button
                            key={item.value}
                            type="button"
                            variant="outline"
                            onClick={() => updateColor(item.value)}
                            className={cn(
                                'justify-start gap-2',
                                color === item.value && 'border-primary',
                            )}
                        >
                            <span
                                className={cn(
                                    'size-4 rounded-full',
                                    item.swatch,
                                )}
                            />
                            <span>{t(item.labelKey)}</span>
                            {color === item.value && (
                                <Check className="ml-auto size-4" />
                            )}
                        </Button>
                    ))}
                </div>
            </div>

            <div className="space-y-3">
                <div className="text-sm font-medium">
                    {t('settings.theme.radius')}
                </div>
                <ToggleGroup
                    type="single"
                    variant="outline"
                    value={radius}
                    onValueChange={(value) => {
                        if (value) {
                            updateRadius(value as ThemeRadius);
                        }
                    }}
                    className="inline-flex"
                >
                    {THEME_RADII.map((item) => (
                        <ToggleGroupItem key={item.value} value={item.value}>
                            {t(item.labelKey)}
                        </ToggleGroupItem>
                    ))}
                </ToggleGroup>
            </div>
        </div>
    );
}
