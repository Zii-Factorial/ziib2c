import type { FormEvent } from 'react';
import type { z } from 'zod';

export type ZodFormErrors<TSchema extends z.ZodType> = Partial<
    Record<Extract<keyof z.infer<TSchema>, string>, string>
>;
type ZodFormField<TSchema extends z.ZodType> = Extract<
    keyof z.infer<TSchema>,
    string
>;

export function validateFormWithSchema<TSchema extends z.ZodType>(
    event: FormEvent<HTMLFormElement>,
    schema: TSchema,
    setErrors: (errors: ZodFormErrors<TSchema>) => void,
): boolean {
    const values = Object.fromEntries(new FormData(event.currentTarget));
    const result = schema.safeParse(values);

    if (result.success) {
        setErrors({});

        return true;
    }

    event.preventDefault();
    setErrors(toFormErrors(result.error));

    return false;
}

function toFormErrors<TSchema extends z.ZodType>(
    error: z.ZodError<z.infer<TSchema>>,
): ZodFormErrors<TSchema> {
    return error.issues.reduce<ZodFormErrors<TSchema>>((errors, issue) => {
        const key = issue.path[0];

        if (typeof key === 'string') {
            const field = key as ZodFormField<TSchema>;

            if (errors[field] === undefined) {
                errors[field] = issue.message;
            }
        }

        return errors;
    }, {});
}
