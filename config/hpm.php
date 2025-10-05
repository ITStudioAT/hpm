<?php

return [
    'version' => '0.0.7',

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

    /*
    |--------------------------------------------------------------------------
    | Menu Types
    | Rows in the `homepages` table considered "menus"
    |--------------------------------------------------------------------------
    */
    'menu_types' => ['menu'],

    /*
    |--------------------------------------------------------------------------
    | Folders Types
    | Rows in the `homepages` table considered "folders"
    |--------------------------------------------------------------------------
    */
    'folder_types' => ['page_folders'],


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
        'menu' => [
            'root' => [
                'id' => 0,
                'title' => 'root',
                'children' => [],
            ],
        ],
        'page_folders' => [
            'folders' => ['/'],
        ],

    ],

];
