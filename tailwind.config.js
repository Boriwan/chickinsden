import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                brand: {
                    50: '#fff8ed',
                    100: '#ffedcf',
                    200: '#ffd9a3',
                    300: '#fdc078',
                    400: '#f9b04a',
                    500: '#f99d34',
                    600: '#e88c2a',
                    700: '#c96f14',
                },
                shell: '#eea34f',
                page: '#fdfaf3',
                surface: {
                    DEFAULT: '#fffdf8',
                    border: '#e9e0cf',
                },
                gender: {
                    male: '#3b82f6',
                    female: '#ec4899',
                },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
