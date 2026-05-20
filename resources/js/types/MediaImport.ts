import { MediaStatus, MediaType } from '@/types/MediaEntry';

export type DupStatus = 'new' | 'update' | 'skip';

export interface ParsedEntry {
    index: number;
    title: string;
    original_title: string | null;
    cover_url: string | null;
    total_chapters: number | null;
    current_chapter: number;
    inferred_status: MediaStatus;
    notes: string | null;
    category_ids: string[];
    dup_status: DupStatus;
}

export interface ParsedCategory {
    id: string;
    name: string;
    count: number;
}

export interface ImportStats {
    total: number;
    new: number;
    update: number;
    skip: number;
}

export interface ParseResponse {
    categories: ParsedCategory[];
    entries: ParsedEntry[];
    stats: ImportStats;
}

export type CategoryTypeMap = Record<string, MediaType>;
