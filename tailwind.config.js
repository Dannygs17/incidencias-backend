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
            colors:{
                'smart': {
                    'cobalto': '#0033FF',
                    'niebla': '#CCEEFF',
                    'interface-bg': '#F5F8FA',
                    'text': '#212121',
                    'line': '#B0BEC5',
                    'action': '#007BFF',
                    'success': '#1ABC9C',
                    'error': '#E74C3C',
                    'warning': '#F39C12',
                },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
