<?php

return [
    'download' => [
        'storage' => [
            'local'      => 'file/',
            'testing'    => 'file/',
            'staging'    => 'file/',
            'production' => 's3',
        ],
    ],
    'upload' => [
        'storage' => [
            'local'      => [
                'images'     => [
                    'banner' => '/images/banner/',
                    'debug' => '/images/debug/',
                ],
                'teting'     => [
                    'session' => '/testing/session/',
                ],
            ],
            'testing'    => 'file/',
            'staging'    => 'file/',
            'production' => 's3',
        ],
    ],
    'service' => [
        'admins' => [
            'banners' => [
                'template' => [
                    (object)[
                        'name'      => 'test name',
                        'detail'    => 'test detail',
                        'location'  => 1,
                        'pc_height' => 100,
                        'pc_width'  => 100,
                        'sp_height' => 100,
                        'sp_width'  => 100,
                        'start_at'  => '2022/05/10 00:00:00',
                        'end_at'    => '2030/12/31 23:59:59',
                        'url'       => '',
                    ]
                ],
            ],
            'bannerBlockContents' => [
                'template' => [
                    (object)[
                        'banner_block_id' => 1,
                        'banner_id'       => 1,
                        'type'            => 1,
                        'order'           => 10,
                        'start_at'        => '2022/05/10 00:00:00',
                        'end_at'          => '2030/12/31 23:59:59',
                    ]
                ]
            ],
            'bannerBlocks' => [
                'template' => [
                    (object)[
                        'name'     => 'test name',
                        'order'    => 10,
                        'start_at' => '2022/05/10 00:00:00',
                        'end_at'   => '2030/12/31 23:59:59',
                    ]
                ]
            ],
            'coins' => [
                'template' => [
                    (object)[
                        'name'   => 'test name',
                        'detail' => 'test detail',
                        'price'  => 100,
                        'cost'   => 100,
                        'start_at'  => '2022/05/10 00:00:00',
                        'end_at'    => '2030/12/31 23:59:59',
                        'image'     => null,
                    ]
                ],
            ],
            'informations' => [
                'template' => [
                    (object)[
                        'name'     => 'test name',
                        'type'     => 1,
                        'detail'   => 'test detail',
                        'start_at' => '2022/05/10 00:00:00',
                        'end_at'   => '2030/12/31 23:59:59',
                    ]
                ]
            ],
            'events' => [
                'template' => [
                    (object)[
                        'name'     => 'test name',
                        'type'     => 1,
                        'detail'   => 'test detail',
                        'start_at' => '2022/05/10 00:00:00',
                        'end_at'   => '2030/12/31 23:59:59',
                    ]
                ]
            ],
            'homeContents' => [
                'template' => [
                    (object)[
                        'type'        => 1,
                        'group_id'    => 1,
                        'contents_id' => 1,
                        'start_at'    => '2022/05/10 00:00:00',
                        'end_at'      => '2030/12/31 23:59:59',
                    ]
                ]
            ],
            'homeContentsGroups' => [
                'template' => [
                    (object)[
                        'name'     => 'test name',
                        'order'    => 10,
                        'start_at' => '2022/05/10 00:00:00',
                        'end_at'   => '2030/12/31 23:59:59',
                    ]
                ]
            ],
            'questionnaires' => [
                'template' => [
                    (object)[
                        'name'      => 'test name',
                        'detail'    => 'test detail',
                        'questions' => json_encode(
                            ['key' => 1, 'text' => 'question text', 'type' => 1, 'defaultText' => '']
                        ),
                        'start_at'   => '2022/05/10 00:00:00',
                        'end_at'     => '2030/12/31 23:59:59',
                        'expired_at' => '2030/12/31 23:59:59',
                    ]
                ]
            ],
            'serviceTerms' => [
                'template' => [
                    (object)[
                        'version'        => 1,
                        'terms'          => '利用規約',
                        'privacy_policy' => 'プライバシーポリシー',
                        'memo'           => 'メモ',
                        'start_at'       => '2022/05/10 00:00:00',
                        'end_at'         => '2030/12/31 23:59:59',
                    ]
                ]
            ],
        ]
    ]
];
