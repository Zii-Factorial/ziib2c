import type { ImgHTMLAttributes } from 'react';

export default function AppLogoIcon({
    className,
    ...props
}: ImgHTMLAttributes<HTMLImageElement>) {
    return (
        <img
            src="/storage/asset/img/zf_polygon_icon_180.png"
            alt="ZF Logo"
            className={className}
            style={{ objectFit: 'contain', display: 'block' }}
            {...props}
        />
    );
}
