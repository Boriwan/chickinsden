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
                    DEFAULT: '#f99d34',
                    dark: '#e88c2a',
                    light: '#fdbd45',
                },
                shell: {
                    DEFAULT: '#f28647',
                },
                page: '#fff9b9',
                surface: {
                    DEFAULT: '#fff9d6',
                    border: '#d6b98c',
                },
                tint: {
                    male: '#e8f0ff',
                    female: '#fff0f5',
                },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
