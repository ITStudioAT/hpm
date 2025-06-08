<?php

namespace Itstudioat\Hpm\Http\Controllers\Homepage;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Request;

class HomepageController extends Controller
{

    public function getConfig()
    {
        $data = [
            'version' => config('hpm.version'),
        ];
        return response()->json($data, 200);
    }

    public function loadHomepage(Request $request)
    {
        $elements = [
            // Homepage
            [
                'id' => 1,
                'type' => 'homepage',
                'name' => 'Homepage',
                'is_active' => true,
                'is_root' => true,
                'properties' =>  [
                    'bg_color' => '#FFFFFF',
                ],
                'parts' => [
                    'header' => ['id' => 2],
                    'main' => ['id' => 3],
                    'footer' => ['id' => 4],
                ]
            ],

            // Header
            [
                'id' => 2,
                'type' => 'homepage_header',
                'name' => 'Kopfzeile',
                'is_active' => true,
                'properties' =>  [
                    'is_fluid' => false,
                    'max_width' => '1280px',
                    'bg_color' => 'white',
                    'text_color' => 'black',
                    'density' => 'prominent', // default, prominent, comfortable, compact
                    'is_flat' => true,
                    'is_tile' => true,
                    'scroll_behavior' => 'hide',
                ],
                'parts' => [
                    'left' => ['id' => 5],
                    'center' => [],
                    'right' => ['id' => 8],
                ]
            ],

            // Main
            [
                'id' => 3,
                'type' => 'homepage_main',
                'name' => 'Hauptbereich',
                'is_active' => true,
                'properties' =>  [
                    'bg-color' => '#C5A60E',
                ],
                'parts' => [
                    '1' => ['id' => 10],
                ]
            ],

            // Footer
            [
                'id' => 4,
                'type' => 'homepage_footer',
                'name' => 'Fußzeile',
                'is_active' => true,
                'properties' =>  [
                    'is_app' => false,
                    'is_fluid' => true,
                    'bg-color' => '#FFFFFF',
                    'text-color' => '#000000',
                    'bg_color' => '#C0D385',
                    'density' => 'prominent',
                    'is_tile' => true,
                ],
                'parts' => [
                    // 'left' => ['id' => 5],
                    'center' => ['id' => 9],
                    // 'right' => ['id' => 6],
                ]
            ],

            // Header: Bild & Text
            [
                'id' => 5,
                'type' => 'header_image&text',
                'name' => 'Bild und Text',
                'is_active' => true,
                'is_root' => false,
                'properties' =>  [
                    'logo_url' => 'images/logo.png',
                    'logo_size' => 'small',
                    'text' => 'HPMaker'
                ],
                'parts' => []
            ],

            // Header: Sandwich
            [
                'id' => 6,
                'type' => 'sandwich',
                'name' => 'Header-Menü',
                'is_active' => true,
                'properties' =>  [
                    'icon' => 'mdi-dots-vertical',
                    'justify' => 'justify-end',
                    'menu_items' => [
                        [
                            'title' => 'Item 1',
                            'children' => [
                                [
                                    'title' => 'Item 1.1',
                                    'children' => [
                                        ['title' => 'Item 1.1.1'],
                                        ['title' => 'Item 1.1.2']
                                    ]
                                ],
                                [
                                    'title' => 'Item 1.2'
                                ]
                            ]
                        ],
                        [
                            'title' => 'Item 2',
                            'children' => [
                                ['title' => 'Item 2.1']
                            ]
                        ],
                        [
                            'title' => 'Item 3'
                        ]
                    ]
                ],
                'parts' => []
            ],

            // Kurzes Textstück für Header
            [
                'id' => 7,
                'type' => 'text',
                'name' => 'Header-Text',
                'is_active' => true,
                'properties' =>  [
                    'text' => 'Das ist ein beliebiger Text.',
                    'color' => '',
                    'size' => 'text-body-2',
                    'justify' => 'text-center'
                ],
                'parts' => []
            ],

            // Simple Menu
            [
                'id' => 8,
                'type' => 'simple_menu',
                'name' => 'Header Menü',
                'is_active' => true,
                'properties' =>  [
                    'icon' => 'mdi-menu',
                    'justify_text' => 'justify-center',
                    'justify_icon' => 'justify-center',
                    'menu_items' => [
                        [
                            'title' => 'Home',
                            'icon' => '',
                            'to' => '/'
                        ],
                        [
                            'title' => 'Impressum',
                            'icon' => '',
                            'to' => '/impressum'
                        ],
                        [
                            'title' => 'Impressum',
                            'icon' => '',
                            'to' => '/impressum'
                        ],
                        [
                            'title' => 'Impressum',
                            'icon' => '',
                            'to' => '/impressum'
                        ],
                        [
                            'title' => 'Impressum',
                            'icon' => '',
                            'to' => '/impressum'
                        ],

                    ]
                ],
                'parts' => []
            ],

            // Kurzes Textstück für Footer
            [
                'id' => 9,
                'type' => 'text',
                'name' => 'Footer-Text',
                'is_active' => true,
                'properties' =>  [
                    'text' => '(c) 2025 by itstudio.at - DI Günther Kron',
                    'color' => '',
                    'size' => 'text-body-2',
                    'justify' => 'text-center'
                ],
                'parts' => []
            ],

            // Hero-Text
            [
                'id' => 10,
                'type' => 'hero_text',
                'name' => 'Hero-Text',
                'is_active' => true,
                'properties' =>  [
                    'density' => 'comfortable', // full, prominent, comfortable, default, compact
                    'bg_color' => 'black',
                    'color' => 'white',
                    'align' => 'above', // default, obove, below
                    'caption' => 'The better school.',
                    'title' => 'Christian-Doppler-Gymnasium',
                    'subtitle' => 'Salzburg',
                ],
                'parts' => []
            ],



        ];

        $data = ['homepage' => $elements];

        return response()->json($data, 200);
    }
}
