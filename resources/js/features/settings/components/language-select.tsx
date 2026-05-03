import { Languages } from 'lucide-react';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import type { Language } from '@/domains/locale';
import { LANGUAGES } from '@/domains/locale';
import { useLanguage } from '@/hooks/use-language';

export default function LanguageSelect() {
    const { language, updateLanguage, t } = useLanguage();

    return (
        <Select
            value={language}
            onValueChange={(value) => updateLanguage(value as Language)}
        >
            <SelectTrigger className="w-full sm:w-56">
                <Languages className="size-4" />
                <SelectValue />
            </SelectTrigger>
            <SelectContent>
                {LANGUAGES.map((item) => (
                    <SelectItem key={item.value} value={item.value}>
                        {t(item.labelKey)}
                    </SelectItem>
                ))}
            </SelectContent>
        </Select>
    );
}
