<?php
return [
    'post index' => [
        'type' => 2,
    ],
    'post create' => [
        'type' => 2,
    ],
    'post view' => [
        'type' => 2,
    ],
    'post update' => [
        'type' => 2,
    ],
    'post delete' => [
        'type' => 2,
    ],
    'category index' => [
        'type' => 2,
    ],
    'category create' => [
        'type' => 2,
    ],
    'category view' => [
        'type' => 2,
    ],
    'category update' => [
        'type' => 2,
    ],
    'category delete' => [
        'type' => 2,
    ],
    'admin' => [
        'type' => 1,
        'children' => [
            'author',
            'category index',
            'category create',
            'category view',
            'category update',
            'category delete',
        ],
    ],
    'author' => [
        'type' => 1,
        'children' => [
            'updateOwnPost',
            'guest',
            'post create',
            'post update',
            'post delete',
        ],
    ],
    'guest' => [
        'type' => 1,
        'children' => [
            'post index',
            'post view',
        ],
    ],
    'updateOwnPost' => [
        'type' => 2,
        'ruleName' => 'isAuthor',
        'children' => [
            'post update',
        ],
    ],
];
