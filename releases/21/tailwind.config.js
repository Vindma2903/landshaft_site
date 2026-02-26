const defaultTheme = require('tailwindcss/defaultTheme')

// 'sm': '640px',
// 'md': '768px',
// 'lg': '1024px',
// 'xl': '1280px',
// '2xl': '1536px',

const customScreens = defaultTheme.screens;
delete customScreens['2xl'];

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./assets/**/*.js",
        "./assets/svelte_components/**/*.svelte",
        "./templates/**/*.html.twig",
        "./var/purging_html/**/*.html",
    ],
    theme: {
        screens: customScreens,
        container: {
            center: true,
            padding: '1rem',
            screens: customScreens
        },
        extend: {
            colors: {
                black: '#111111',
                green: '#4A7A25',
            },
            fontFamily: {
                'sans': ['Articulat CF', ...defaultTheme.fontFamily.sans],
                'helvetica': ['Helvetica', ...defaultTheme.fontFamily.mono]
            },
        },
    },
    plugins: [
        // require('@tailwindcss/typography'),
    ],
}

