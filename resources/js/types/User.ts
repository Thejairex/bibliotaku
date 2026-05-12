export interface User {
    id: number;
    name: string;
    email: string;
    initials: () => string;
    avatar_url?: string;
}
