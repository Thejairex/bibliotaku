export enum MediaStatus {
    Watching = 'watching',
    Rewatching = 'rewatching',
    Completed = 'completed',
    OnHold = 'on_hold',
    Dropped = 'dropped',
    PlanToWatch = 'plan_to_watch',
    Reading = 'reading',
}

export enum MediaType {
    Anime = 'anime',
    Manga = 'manga',
    Manhwa = 'manhwa',
    Manhua = 'manhua',
    Novel = 'novel',
}

export interface MediaEntry {
    id: number;
    user_id: number;
    title: string;
    original_title: string | null;
    type: MediaType;
    cover_url: string | null;
    mal_id: number | null;
    status: MediaStatus;
    current_episode: number | null;
    total_episodes: number | null;
    current_chapter: number | null;
    total_chapters: number | null;
    current_volume: number | null;
    total_volumes: number | null;
    rating: number | null;
    notes: string | null;
    created_at: string;
    updated_at: string;
}

export interface MediaEntryFormData {
    title: string;
    original_title?: string;
    type: MediaType;
    cover_url?: string;
    mal_id?: number;
    status: MediaStatus;
    current_episode?: number;
    total_episodes?: number;
    current_chapter?: number;
    total_chapters?: number;
    total_volumes?: number;
    rating?: number;
    notes?: string;
}
