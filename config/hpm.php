<?php

// config for Itstudioat/HPM
return [
    'version' => '0.0.3',
    'copyright' => '(c) 2025 ITStudio.at by Günther Kron',
    'title' => 'HPM',
    'company' => 'ItStudio.at',

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
        'header' => [
            'props'  => ['border' => false, 'color' => 'first', 'density' => 'default', 'elevation' => 0, 'flat' => true, 'height' => 0, 'scroll_behavior' => 'hide'],
            'rows' => [
                'count' => 1,
                'row_1' => [
                    'desktop' => [
                        'fluid' => false,
                        'max_width' => null,
                        'columns' => [
                            'count' => 3,
                            'col_1' => [
                                'justify' => 'justify-start',
                                'has_menu' => false,
                                'menu_id'   => null,
                                'menu_name' => null,
                                'has_image' => false,
                                'image' => null,
                                'image_height' => 32,
                                'image_width' => null,
                                'has_text' => true,
                                'text' => 'HPM',
                                'text_variant' => 'text-caption',
                            ],
                            'col_2' => [
                                'justify' => 'justify-start',
                            ],
                            'col_3' => [
                                'justify' => 'justify-start',
                            ],
                        ]
                    ],
                    'tablet' => [
                        'fluid' => false,
                        'max_width' => null,
                        'columns' => [
                            'count' => 2,
                            'col_1' => [
                                'justify' => 'justify-start',
                            ],
                            'col_2' => [
                                'justify' => 'justify-start',
                            ],
                            'col_3' => [
                                'justify' => 'justify-start',
                            ],
                        ]
                    ],
                    'handy' => [
                        'fluid' => false,
                        'max_width' => null,
                        'columns' => [
                            'count' => 1,
                            'col_1' => [
                                'justify' => 'justify-start',
                            ],
                            'col_2' => [
                                'justify' => 'justify-start',
                            ],
                            'col_3' => [
                                'justify' => 'justify-start',
                            ],
                        ]
                    ],
                ],
                'row_2' => [
                    'desktop' => [
                        'fluid' => false,
                        'max_width' => null,
                        'columns' => [
                            'count' => 3,
                            'col_1' => [
                                'justify' => 'justify-start',
                            ],
                            'col_2' => [
                                'justify' => 'justify-start',
                            ],
                            'col_3' => [
                                'justify' => 'justify-start',
                            ],
                        ]
                    ],
                    'tablet' => [
                        'fluid' => false,
                        'max_width' => null,
                        'columns' => [
                            'count' => 2,
                            'col_1' => [
                                'justify' => 'justify-start',
                            ],
                            'col_2' => [
                                'justify' => 'justify-start',
                            ],
                            'col_3' => [
                                'justify' => 'justify-start',
                            ],
                        ]
                    ],
                    'handy' => [
                        'fluid' => false,
                        'max_width' => null,
                        'columns' => [
                            'count' => 1,
                            'col_1' => [
                                'justify' => 'justify-start',
                            ],
                            'col_2' => [
                                'justify' => 'justify-start',
                            ],
                            'col_3' => [
                                'justify' => 'justify-start',
                            ],
                        ]
                    ],
                ],
            ]

        ],
        'footer' => [
            'props' => ['border' => false, 'color' => 'first', 'elevation' => 0, 'flat' => true, 'height' => 0],
        ],
    ],
];
