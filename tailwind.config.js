import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

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
                    blue: '#1E40AF',
                    gold: '#D4A106',
                    green: '#16A34A',
                    ink: '#0F172A',
                    muted: '#475569',
                    bg: '#F5F7FB',
                    border: '#E6EAF2',
                    white: '#FFFFFF',
                    sidebar: '#0B1F3A',
                    nav: '#93C5FD',
                },
                primary: '#1E40AF',
                accent: '#D4A106',
                success: '#16A34A',
            },
        },
    },

    plugins: [forms, typography],
};
