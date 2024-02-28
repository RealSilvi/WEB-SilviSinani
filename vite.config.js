import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from 'tailwindcss';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/frontend/css/app.css', 'resources/frontend/js/app.ts'],
            refresh: true
        })
    ],
    css: {
        postcss: {
            plugins: [
                tailwindcss
            ]
        }
    }
});
