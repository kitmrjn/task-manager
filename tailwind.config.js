import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    // ADD THIS SECTION HERE
    safelist: [
        'bg-gray-400', 'bg-blue-400', 'bg-green-400', 'bg-yellow-400', 'bg-red-400',
        'text-gray-400', 'text-blue-400', 'text-green-400', 'text-yellow-400', 'text-red-400',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};