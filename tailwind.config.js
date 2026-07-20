/** @type {import('tailwindcss').Config} */
// tailwind.config.js — Taruh di ROOT project Laravel

export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans:    ['Plus Jakarta Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                display: ['Space Grotesk', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
            colors: {
                navy: {
                    DEFAULT: '#0F172A',
                    700:     '#334155',
                    800:     '#1E293B',
                },
                orange: {
                    DEFAULT: '#F97316',
                    hover:   '#EA6C0A',
                },
            },
            borderRadius: {
                '2xl': '1rem',
                '3xl': '1.5rem',
            },
            boxShadow: {
                card:   '0 4px 24px rgba(15,23,42,0.07)',
                orange: '0 8px 20px rgba(249,115,22,0.35)',
            },
        },
    },
    plugins: [],
};
