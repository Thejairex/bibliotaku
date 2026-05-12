import { PageProps } from '@inertiajs/core';
import { User } from '@/types/User';

export interface Props extends PageProps {
    auth: {
        user: User | null;
    };
    errors: Record<string, string>;
}
