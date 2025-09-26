<?php

return [
    'version' => '0.0.1',

    /*
    |--------------------------------------------------------------------------
    | Homepage Types
    | Rows in the `homepages` table considered "homepages"
    |--------------------------------------------------------------------------
    */
    'homepage_types' => ['homepage', 'active_homepage'],

    /*
    |--------------------------------------------------------------------------
    | Page Types
    | Rows in the `homepages` table considered "pages"
    |--------------------------------------------------------------------------
    */
    'page_types' => ['index', 'page'], // you can add more, e.g. ['page', 'landing', 'article']


    'structures' => [
        'homepage' => [
            'index' => ['id' => null],
            'fonts' => ['fontset' => 'default'],
            'colors' => ['colorset' => 'default'],
        ],
        'index' => [
            'header'  => ['id' => null, 'is_visible' => true],
            'content' => [],
            'footer'  => ['id' => null, 'is_visible' => true],
        ],
        'page' => [
            'header'  => ['id' => null, 'is_visible' => true],
            'content' => [],
            'footer'  => ['id' => null, 'is_visible' => true],
        ],

    ],

];
