import { User } from './User';
import { MediaEntry } from './MediaEntry';

export interface SharedPageProps {
    auth: {
        user: User;
    };
    errors: Record<string, string>;
    flash?: {
        success?: string;
        error?: string;
        info?: string;
    };
}

export interface DashboardPageProps extends SharedPageProps {
    stats: {
        total_entries: number;
        watching: number;
        completed: number;
        on_hold: number;
        dropped: number;
        plan_to_watch: number;
        reading: number;
    };
    recent_entries: MediaEntry[];
    in_progress_entries: MediaEntry[];
}

export interface SearchPageProps extends SharedPageProps {
    query?: string;
    mode?: 'local' | 'mal';
}

export interface MyListPageProps extends SharedPageProps {
    entries: {
        data: MediaEntry[];
        current_page: number;
        last_page: number;
        total: number;
        per_page: number;
    };
    filters: {
        status?: string;
        type?: string;
        search?: string;
    };
}

export interface MediaDetailPageProps extends SharedPageProps {
    entry: MediaEntry;
}
