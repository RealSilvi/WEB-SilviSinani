const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');
const _ = require('lodash');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/views/**/*.blade.php',
        './resources/svg/**/*.svg',
        './resources/frontend/js/**/*.js',
        './resources/frontend/js/**/*.ts',
    ],
    safelist: [
        'fill-current',
    ],
    future: {
        hoverOnlyWhenSupported: true,
        respectDefaultRingColorOpacity: true,
    },
    theme: {
        screens: {
            sm: '640px',
            md: '768px',
            lg: '1024px',
            xl: '1280px',
            '2xl': '1440px',
        },
        colors: {
            transparent: 'transparent',
            current: 'currentColor',

            black: colors.black,
            white: colors.white,

            gray: colors.stone,

            primary: {
                light: '#DE0A42',
                DEFAULT: '#BF2D43',
                dark: '#993333',
            },

            success: {
                light: colors.green[100],
                DEFAULT: colors.green[500],
                dark: colors.green[800],
            },
            error: {
                light: colors.red[100],
                DEFAULT: colors.red[500],
                dark: colors.red[800],
            },
            warning: {
                light: colors.amber[100],
                DEFAULT: colors.amber[500],
                dark: colors.amber[800],
            },
            info: {
                light: colors.blue[100],
                DEFAULT: colors.blue[500],
                dark: colors.blue[800],
            },
        },
        extend: {
            fontFamily: {
                sans: ['MavenPro', ...defaultTheme.fontFamily.sans],
            },
            spacing: {
                15: '3.75rem',
                22: '5.5rem',
                30: '7.5rem',
                112: '28rem',
            },
            typography: ({ theme }) => {
                const themeFontSizes = Object.entries(theme('fontSize'))
                    .reduce((acc, current) => {
                        acc[current[0]] = {
                            css: {
                                '--tw-prose-font-size': current[1][0],
                                '--tw-prose-line-height': current[1][1].lineHeight,
                                fontSize: 'var(--tw-prose-font-size)',
                                lineHeight: 'var(--tw-prose-line-height)',
                            },
                        };
                        return acc;
                    }, {});

                return _.merge(themeFontSizes, {
                    DEFAULT: {
                        css: {
                            '--tw-prose-body': 'currentColor',
                            '--tw-prose-headings': 'var(--tw-prose-body)',
                            '--tw-prose-links': theme('colors.gray.700'),
                            '--tw-prose-bold': 'var(--tw-prose-body)',

                            maxWidth: '100%',
                            a: {
                                color: 'inherit',
                                fontWeight: 'inherit',
                            },
                            // p: {
                            //     fontWeight: '400',
                            // },
                            'b, strong': {
                                fontWeight: '700',
                            },
                            // 'h1, h2, h3, h4, h5, h6': {
                            //     fontWeight: '700',
                            // },
                        },
                    },
                    'marker-primary': {
                        css: {
                            '--tw-prose-bullets': theme('colors.primary.DEFAULT'),
                            'ul > li::marker': {
                                fontSize: '2em',
                                lineHeight: '0.5em',
                            },
                        },
                    },
                });
            },
        },
    },
    corePlugins: {
        aspectRatio: false,
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/aspect-ratio'),
        require('@tailwindcss/typography'),
        require('tailwindcss-3d')({ legacy: true }),
        require('./tailwindcss/external'),
    ],
};
