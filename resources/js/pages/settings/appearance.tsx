import { Head } from '@inertiajs/react';
import AppearanceTabs from '@/components/appearance-tabs';
import Heading from '@/components/heading';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import LanguageSelect from '@/features/settings/components/language-select';
import ThemeCustomizer from '@/features/settings/components/theme-customizer';
import { useLanguage } from '@/hooks/use-language';
import { edit as editAppearance } from '@/routes/appearance';

export default function Appearance() {
    const { t } = useLanguage();

    return (
        <>
            <Head title={t('settings.appearance.title')} />

            <h1 className="sr-only">{t('settings.appearance.title')}</h1>

            <div className="space-y-6">
                <Heading
                    variant="small"
                    title={t('settings.appearance.title')}
                    description={t('settings.appearance.description')}
                />

                <Card>
                    <CardHeader>
                        <CardTitle>{t('settings.appearance.title')}</CardTitle>
                        <CardDescription>
                            {t('settings.appearance.description')}
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <AppearanceTabs />
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>{t('settings.language.title')}</CardTitle>
                        <CardDescription>
                            {t('settings.language.description')}
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <LanguageSelect />
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>{t('settings.theme.title')}</CardTitle>
                        <CardDescription>
                            {t('settings.theme.description')}
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <ThemeCustomizer />
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

Appearance.layout = {
    breadcrumbs: [
        {
            title: 'Appearance settings',
            href: editAppearance(),
        },
    ],
};
