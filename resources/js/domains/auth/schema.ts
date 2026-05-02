import { z } from 'zod';

export const registerSchema = z
    .object({
        name: z.string().trim().min(1, 'The name field is required.').max(255),
        email: z.email('Enter a valid email address.').max(255),
        password: z
            .string()
            .min(8, 'The password must be at least 8 characters.'),
        password_confirmation: z
            .string()
            .min(1, 'Please confirm your password.'),
    })
    .refine((data) => data.password === data.password_confirmation, {
        message: 'The password confirmation does not match.',
        path: ['password_confirmation'],
    });

export type RegisterFormData = z.infer<typeof registerSchema>;
