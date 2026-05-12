export interface JikanData {
    mal_id: number;
    title: string;
    title_japanese: string | null;
    cover_url: string | null;
    type: string | null;
    status: string | null;
    total_episodes: number | null;
    total_chapters: number | null;
    total_volumes: number | null;
    score: number | null;
    synopsis: string | null;
    mal_url: string | null;
}

export interface JikanSearchParams {
    type: 'anime' | 'manga';
    query: string;
    limit?: number;
}
