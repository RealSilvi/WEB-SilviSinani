const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/views/**/*.blade.php',
        './resources/svg/**/*.svg',
        './resources/frontend/js/**/*.js',
        './resources/frontend/js/**/*.ts'
    ],
    safelist: [
        'fill-current'
    ],
    future: {
        hoverOnlyWhenSupported: true,
        respectDefaultRingColorOpacity: true
    },
    theme: {
        screens: {
            sm: '640px',
            md: '768px',
            lg: '1024px',
            xl: '1280px',
            '2xl': '1440px'
        },
        colors: {
            transparent: 'transparent',
            current: 'currentColor',

            black: colors.black,
            white: colors.white,

            gray: colors.stone,

            primary:'#515B3A',

            success: {
                light: colors.green[100],
                DEFAULT: colors.green[500],
                dark: colors.green[800]
            },
            error: {
                light: colors.red[100],
                DEFAULT: colors.red[500],
                dark: colors.red[800]
            },
            warning: {
                light: colors.amber[100],
                DEFAULT: colors.amber[500],
                dark: colors.amber[800]
            },
            info: {
                light: colors.blue[100],
                DEFAULT: colors.blue[500],
                dark: colors.blue[800]
            }
        },
        extend: {
            fontFamily: {
                sans: ['MavenPro', ...defaultTheme.fontFamily.sans]
            },
            spacing: {
                15: '3.75rem',//60px
                17: '4.25rem',//68px
                22: '5.5rem',
                30: '7.5rem',
                112: '28rem'
            }
        }
    },
    corePlugins: {
        aspectRatio: true
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/aspect-ratio'),
        require('@tailwindcss/typography')
    ]
};
