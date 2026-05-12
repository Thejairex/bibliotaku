import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: [
                'resources/views/**/*.blade.php',
                'resources/views/*.blade.php',
                'app/Livewire/**/*.php',
            ],
        }),
        tailwindcss(),
    ],
    css: {
        external: [
            /^https:\/\/fonts\.googleapis\.com/,
            /^https:\/\/fonts\.gstatic\.com/,
        ],
    },
    // build: {
    //     reportCompressedSize: false,
    //     chunkSizeWarningLimit: 2000,
    // },
    server: {
        cors: true,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
