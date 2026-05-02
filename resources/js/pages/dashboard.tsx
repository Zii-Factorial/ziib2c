import { Head } from '@inertiajs/react';
import {
    Activity,
    ArrowUpRight,
    CreditCard,
    PackageCheck,
    TrendingUp,
    Users,
} from 'lucide-react';
import type { LucideIcon } from 'lucide-react';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import type { TranslationKey } from '@/hooks/use-language';
import { useLanguage } from '@/hooks/use-language';
import { dashboard } from '@/routes';

type Metric = {
    label: TranslationKey;
    value: string;
    change: string;
    icon: LucideIcon;
};

const metrics: Metric[] = [
    {
        label: 'dashboard.revenue',
        value: '$24,820',
        change: '+12.4%',
        icon: TrendingUp,
    },
    {
        label: 'dashboard.orders',
        value: '1,284',
        change: '+8.2%',
        icon: PackageCheck,
    },
    {
        label: 'dashboard.customers',
        value: '6,420',
        change: '+4.7%',
        icon: Users,
    },
    {
        label: 'dashboard.conversion',
        value: '7.8%',
        change: '+1.1%',
        icon: CreditCard,
    },
];

const chartBars = [42, 68, 54, 76, 61, 88, 72, 94, 83, 98, 86, 104];

export default function Dashboard() {
    const { t } = useLanguage();

    return (
        <>
            <Head title={t('dashboard.title')} />

            <div className="flex h-full flex-1 flex-col gap-6 p-4">
                <div className="flex flex-col gap-2">
                    <h1 className="text-2xl font-semibold">
                        {t('dashboard.title')}
                    </h1>
                    <p className="text-sm text-muted-foreground">
                        {t('dashboard.description')}
                    </p>
                </div>

                <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    {metrics.map(({ label, value, change, icon: Icon }) => (
                        <Card key={label}>
                            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle className="text-sm font-medium">
                                    {t(label)}
                                </CardTitle>
                                <Icon className="size-4 text-muted-foreground" />
                            </CardHeader>
                            <CardContent>
                                <div className="text-2xl font-semibold">
                                    {value}
                                </div>
                                <div className="mt-1 flex items-center gap-1 text-xs text-emerald-600 dark:text-emerald-400">
                                    <ArrowUpRight className="size-3" />
                                    {change}
                                </div>
                            </CardContent>
                        </Card>
                    ))}
                </div>

                <div className="grid gap-4 xl:grid-cols-[2fr_1fr]">
                    <Card>
                        <CardHeader>
                            <CardTitle>{t('dashboard.performance')}</CardTitle>
                            <CardDescription>
                                {t('dashboard.quickView')}
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div className="flex h-72 items-end gap-3">
                                {chartBars.map((height, index) => (
                                    <div
                                        key={index}
                                        className="flex flex-1 items-end rounded-sm bg-muted"
                                    >
                                        <div
                                            className="w-full rounded-sm bg-primary"
                                            style={{ height: `${height}%` }}
                                        />
                                    </div>
                                ))}
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>{t('dashboard.activity')}</CardTitle>
                            <CardDescription>
                                {t('dashboard.quickView')}
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-5">
                            {[1, 2, 3, 4].map((item) => (
                                <div
                                    key={item}
                                    className="flex items-center gap-3"
                                >
                                    <div className="flex size-9 items-center justify-center rounded-full bg-primary/10 text-primary">
                                        <Activity className="size-4" />
                                    </div>
                                    <div className="min-w-0 flex-1">
                                        <div className="truncate text-sm font-medium">
                                            {t('dashboard.activity')} #{item}
                                        </div>
                                        <div className="text-xs text-muted-foreground">
                                            {item * 12} min ago
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </CardContent>
                    </Card>
                </div>
            </div>
        </>
    );
}

Dashboard.layout = {
    breadcrumbs: [
        {
            title: 'Dashboard',
            href: dashboard(),
        },
    ],
};
