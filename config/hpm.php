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
        'header' => [],
        'footer' => [],
    ],
];
