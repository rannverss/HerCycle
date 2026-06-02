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
                pink: {
                    50: '#FFF0F3',
                    100: '#FFE4E9',
                    200: '#FFC0CB',
                    300: '#FFB6C1',
                    400: '#FF9AAE',
                    500: '#FF7A95',
                    600: '#FF5277',
                    700: '#E6365A',
                    800: '#BF2D4B',
                    900: '#99243C',
                },
                accent: {
                    purple: '#E6D6FF',
                    'purple-dark': '#D4B8FF',
                    lavender: '#F3EAFF',
                },
                her: {
                    bg: '#FFF8FA',
                    card: '#FFFFFF',
                    text: '#444444',
                    'text-light': '#888888',
                    period: '#FF6B8A',
                    fertile: '#C4A0FF',
                    normal: '#7BD4A0',
                    predict: '#7BB8FF',
                },
            },
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            borderRadius: {
                'cute': '1.25rem',
                'cute-lg': '1.5rem',
            },
            boxShadow: {
                'cute': '0 4px 20px rgba(255, 182, 193, 0.15)',
                'cute-hover': '0 8px 30px rgba(255, 182, 193, 0.3)',
                'cute-lg': '0 10px 40px rgba(255, 182, 193, 0.2)',
            },
            animation: {
                'bounce-soft': 'bounce-soft 0.5s ease-in-out',
                'fade-in': 'fade-in 0.4s ease-out',
                'slide-up': 'slide-up 0.4s ease-out',
                'pulse-soft': 'pulse-soft 2s ease-in-out infinite',
            },
            keyframes: {
                'bounce-soft': {
                    '0%, 100%': { transform: 'scale(1)' },
                    '50%': { transform: 'scale(1.08)' },
                },
                'fade-in': {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                'slide-up': {
                    '0%': { opacity: '0', transform: 'translateY(10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'pulse-soft': {
                    '0%, 100%': { opacity: '1' },
                    '50%': { opacity: '0.7' },
                },
            },
        },
    },

    plugins: [forms],
};
