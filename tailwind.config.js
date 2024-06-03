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
            red: colors.red,

            primary:'#515B3A',

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
