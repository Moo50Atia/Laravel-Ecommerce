import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            }, colors: {
                primary: '#1D4ED8', // الأزرق الأساسي
                secondary: '#E0F2FE', // أزرق فاتح مريح
                dark: '#1E293B',
                light: '#F8FAFC',
            },
        },
    },

    plugins: [forms],
};
