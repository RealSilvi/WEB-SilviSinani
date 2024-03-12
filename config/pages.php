<?php

return [
    /**
     * Compilers options
     */
    'compilers' => [
        'image' => [
            /**
             * Enable lazy loading of images and videos. A js library like lozad.js, yall.js or lazysizes is required to handle client-side lazy loading.
             * This package only set data-src instead of src to prevent default loading.
             */
            'lazy_load' => 'native',
            /**
             * Class to add to lazy elements
             */
            'lazy_load_class' => 'lazy',
            /**
             * Image placeholder (default src). Set to null to remove
             * The default is a 1x1px transparent gif.
             */
            'placeholder' => 'data:image/gif;base64,R0lGODlhAQABAIABAP///wAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==',
        ],
    ],

    'utils' => [
        /**
         * Config for ImageUtils class
         */
        'image' => [
            'screens' => [
                'sm' => 640,
                'md' => 768,
                'lg' => 1024,
                'xl' => 1280,
                '2xl' => 1440,
                'fhd' => 1920,
                'qhd' => 2560,
                'uwqhd' => 3440,
                'uhd' => 4096,
            ],
        ],
    ],

    /**
     * Page resolvers used to customize pages parameters resolution and response
     */
    'resolvers' => [

    ],

    /**
     * Route-level middleware configuration, useful for protecting routes with authentication
     */
    'middleware' => [
//        'auth.login' => ['guest'],
//        'auth.register' => ['guest'],
//        'auth.forgot-password' => ['guest'],
//        'auth.reset-password._token' => ['guest'],
//        'cart.checkout' => ['auth'],
//        'profile.index' => ['auth'],
//        'profile.details' => ['auth'],
//        'profile.orders.index' => ['auth'],
//        'profile.orders._order' => ['auth'],
    ],
];
