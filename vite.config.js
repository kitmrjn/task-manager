import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/css/dashboard.css',  // ← dashboard styles
                'resources/js/dashboard.js',    // ← dashboard scripts
                'resources/css/tasks.css',      // tasks/board styles
                'resources/js/tasks.js',        // tasks/board scripts
                'resources/css/calendar.css',    // calendar styles
                'resources/js/calendar.js',      // calendar scripts
            ],
            refresh: true,
        }),
    ],
});