<?php

return [
    'test' => [
        'admin' => [
            'login' => [
                'email'    => 'test' . 'admin1' . '@example.com',
                'password' => env('TEST_ADMIN_SEEDER_PASSWORD', 'password')
            ],
            'create' => [
                'success' => [
                    'name'                  => 'test name',
                    'email'                 => 'testadmin'. '12345XXX' . '@example.com',
                    'roleId'                => 1,
                    'password'              => 'testpassword' . '12345',
                    'password_confirmation' => 'testpassword' . '12345'
                ]
            ],
        ],
        'user' => [
            'login' => [
                'email'    => 'test' . 'user1' . '@example.com',
                'password' => env('TEST_USR_SEEDER_PASSWORD', 'password')
            ],
            'create' => [
                'success' => [
                    'name'                  => 'test name',
                    'email'                 => 'testuser'. '12345XXX' . '@example.com',
                    'roleId'                => 1,
                    'password'              => 'testpassword' . '12345',
                    'password_confirmation' => 'testpassword' . '12345'
                ]
            ],
        ],
        'roles' => [
            'create' => [
                'success' => [
                    'name'        => 'test name',
                    'code'        => 'test_code',
                    'detail'      => 'role`s detail.',
                    'permissions' => [1,2,3],
                ]
            ]
        ],
        'banners' => [
            'create' => [
                'success' => [
                    'name'         => 'test name',
                    'detail'       => 'banners`s detail.',
                    'location'     => 'home',
                    'pc_height'    => 1000,
                    'pc_width'     => 1000,
                    'sp_height'    => 1000,
                    'sp_width'     => 1000,
                    'start_at'     => '2022/05/10 00:00:00',
                    'end_at'       => '2030/12/31 23:59:59',
                    'url'          => 'localhost/banner/1',
                    'banners' => [2,3],
                ]
            ],
            'import' => [
                'success' => [
                    'fileName'  => 'master_banners_template_20220404000000.xlsx',
                    'mimeType'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'size'      => 1000
                ],
                'fileData' => [
                    (object)[
                        'name'         => 'test name',
                        'detail'       => 'banners`s detail.',
                        'location'     => 'home',
                        'pc_height'    => 1000,
                        'pc_width'     => 1000,
                        'sp_height'    => 1000,
                        'sp_width'     => 1000,
                        'start_at'     => '2022/05/10 00:00:00',
                        'end_at'       => '2030/12/31 23:59:59',
                        'url'          => 'localhost/banner/1',
                        'banners' => [2,3],
                    ]
                ]
            ],
        ],
        'coins' => [
            'create' => [
                'success' => [
                    'name'      => 'test name',
                    'detail'    => 'coins`s detail.',
                    'price'     => 350,
                    'cost'      => 350,
                    'start_at'  => '2022/05/10 00:00:00',
                    'end_at'    => '2030/12/31 23:59:59',
                    'image'     => null,
                    'coins'     => [2,3],
                ]
            ],
            'import' => [
                'success' => [
                    'fileName'  => 'master_coins_template_20220404000000.xlsx',
                    'mimeType'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'size'      => 1000
                ],
                'fileData' => [
                    (object)[
                        'name'     => 'test coin1',
                        'detail'   => 'test coin1',
                        'price'    => 500,
                        'cost'     => 500,
                        'start_at' => '2022/05/10 00:00:00',
                        'end_at'   => '2030/12/31 23:59:59',
                        'image'    => null,
                    ]
                ]
            ],
        ],
        'informations' => [
            'create' => [
                'success' => [
                    'name'         => 'test name',
                    'type'         => 1,
                    'detail'       => 'informations`s detail.',
                    'start_at'     => '2022/05/10 00:00:00',
                    'end_at'       => '2030/12/31 23:59:59',
                    'informations' => [2,3],
                ]
            ],
            'import' => [
                'success' => [
                    'fileName'  => 'master_informations_template_20220404000000.xlsx',
                    'mimeType'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'size'      => 1000
                ],
                'fileData' => [
                    (object)[
                        'name'         => 'test name',
                        'type'         => 1,
                        'detail'       => 'informations`s detail.',
                        'start_at'     => '2022/05/10 00:00:00',
                        'end_at'       => '2030/12/31 23:59:59',
                        'informations' => [2,3],
                    ]
                ]
            ],
        ],
        'events' => [
            'create' => [
                'success' => [
                    'name'         => 'test name',
                    'type'         => 1,
                    'detail'       => 'events`s detail.',
                    'start_at'     => '2022/05/10 00:00:00',
                    'end_at'       => '2030/12/31 23:59:59',
                    'events' => [2,3],
                ]
            ],
            'import' => [
                'success' => [
                    'fileName'  => 'master_events_template_20220404000000.xlsx',
                    'mimeType'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'size'      => 1000
                ],
                'fileData' => [
                    (object)[
                        'name'         => 'test name',
                        'type'         => 1,
                        'detail'       => 'events`s detail.',
                        'start_at'     => '2022/05/10 00:00:00',
                        'end_at'       => '2030/12/31 23:59:59',
                        'events' => [2,3],
                    ]
                ]
            ],
        ],
        'questionnaires' => [
            'import' => [
                'success' => [
                    'fileName'  => 'master_questionnaires_template_20220404000000.csv',
                    'mimeType'  => 'text/csv; charset=UTF-8',
                    'size'      => 1000
                ],
                'fileData' => [
                    (object)[
                        'name'      => 'questionnaires.',
                        'detail'    => 'questionnaires detail.',
                        'questions' => json_encode(
                            ['key' => 1, 'text' => 'question text', 'type' => 1, 'defaultText' => '']
                        ),
                        'memo'       => 'serviceTerms`s memo.',
                        'start_at'   => '2022/05/10 00:00:00',
                        'end_at'     => '2030/12/31 23:59:59',
                        'expired_at' => '2030/12/31 23:59:59',
                    ]
                ]
            ],
        ],
        'serviceTerms' => [
            'import' => [
                'success' => [
                    'fileName'  => 'master_service_terms_template_20220404000000.csv',
                    'mimeType'  => 'text/csv; charset=UTF-8',
                    'size'      => 1000
                ],
                'fileData' => [
                    (object)[
                        'version'        => 999,
                        'terms'          => 'terms.',
                        'privacy_policy' => 'privacy policy.',
                        'memo'           => 'serviceTerms`s memo.',
                        'start_at'       => '2022/05/10 00:00:00',
                        'end_at'         => '2030/12/31 23:59:59',
                    ]
                ]
            ],
        ],
    ],
];
