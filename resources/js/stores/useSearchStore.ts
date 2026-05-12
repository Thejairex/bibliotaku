import { create } from 'zustand';

export type SearchMode = 'local' | 'mal';

interface SearchState {
    query: string;
    mode: SearchMode;
    results: unknown[];
    loading: boolean;
    searched: boolean;
    error: string | null;
    setQuery: (query: string) => void;
    setMode: (mode: SearchMode) => void;
    setResults: (results: unknown[]) => void;
    setLoading: (loading: boolean) => void;
    setSearched: (searched: boolean) => void;
    setError: (error: string | null) => void;
    reset: () => void;
}

export const useSearchStore = create<SearchState>((set) => ({
    query: '',
    mode: 'local',
    results: [],
    loading: false,
    searched: false,
    error: null,
    setQuery: (query) => set({ query }),
    setMode: (mode) => set({ mode }),
    setResults: (results) => set({ results }),
    setLoading: (loading) => set({ loading }),
    setSearched: (searched) => set({ searched }),
    setError: (error) => set({ error }),
    reset: () => set({ query: '', results: [], loading: false, searched: false, error: null }),
}));
