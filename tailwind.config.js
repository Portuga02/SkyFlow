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
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50: '#f0f7ff',
                    100: '#e0eefe',
                    200: '#bae0fd',
                    300: '#7cc8fb',
                    400: '#36acf6',
                    500: '#0c8fe6',
                    600: '#0071c4',
                    700: '#00599e',
                    800: '#064c83',
                    900: '#0b406d',
                    950: '#082849',
                },
            },
            boxShadow: {
                soft: '0 2px 10px 0 rgba(11, 64, 109, 0.06)',
                card: '0 1px 3px 0 rgba(11, 64, 109, 0.08), 0 1px 2px -1px rgba(11, 64, 109, 0.08)',
                'card-hover': '0 12px 24px -8px rgba(11, 64, 109, 0.18)',
            },
            animation: {
                'fade-in': 'fadeIn 0.35s ease-out',
                'pop-in': 'popIn 0.25s ease-out',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0', transform: 'translateY(6px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                popIn: {
                    '0%': { opacity: '0', transform: 'scale(0.94)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
            },
        },
    },

    plugins: [forms],
};
