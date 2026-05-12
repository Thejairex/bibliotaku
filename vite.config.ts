import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";
import react from "@vitejs/plugin-react";
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.tsx'],
            refresh: [
                'resources/views/**/*.blade.php',
                'app/Livewire/**/*.php',
            ],
        }),
        tailwindcss(),
        react(),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
        },
    },
    css: {
        external: [
            /^https:\/\/fonts\.googleapis\.com/,
            /^https:\/\/fonts\.gstatic\.com/,
        ],
    },
    build: {
        reportCompressedSize: false,
        chunkSizeWarningLimit: 2000,
    },
    server: {
        cors: true,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
