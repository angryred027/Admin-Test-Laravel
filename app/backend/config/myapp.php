<?php

return [
    'headers' => [
        'id'            => 'X-Auth-ID',
        'authority'     => 'X-Auth-Authority',
        'authorization' => 'Authorization',
        'passwordReset' => 'X-Auth-Reset-Session-ID',
        'fakerTime'     => 'X-Faker-Time',
    ],
    'executionRole' => [
        'services' => [
            'admins'         => ['master', 'administrator', 'develop'],
            'permissions'    => ['master', 'administrator', 'develop'],
            'roles'          => ['master', 'administrator', 'develop'],
            'banners'        => ['master', 'administrator', 'develop'],
            'coins'          => ['master', 'administrator', 'develop'],
            'events'         => ['master', 'administrator', 'develop'],
            'home'           => ['master', 'administrator', 'develop'],
            'informations'   => ['master', 'administrator', 'develop'],
            'questionnaires' => ['master', 'administrator', 'develop'],
            'serviceTerms'   => ['master', 'administrator', 'develop'],
            'debug'          => ['master', 'administrator', 'develop'],
        ]
    ],
    'file' => [
        'download' => [
            'storage' => [
                'local'      => 'file/',
                'testing'    => 'file/',
                'staging'    => 'file/',
                'production' => 's3',
            ],
        ],
    ],
    'slack' => [
        'channel' => env('APP_SLACK_CHANNEL', 'channel_title'),
        'name'    => env('APP_SLACK_NAME', 'bot-name'),
        'icon'    => env('APP_SLACK_ICON', ':ghost:'),
        'url'     => env('APP_SLACK_WEBHOOK_URL', 'https://hooks.slack.com/services/test'),
    ],
    'service' => [],
    'hash' => [
        'pepper' => env('PEPPER'),
    ],
    'database' => [
        'logs' => [
            'baseConnectionName' => env('DB_LOGS_BASE_CONNECTION'),
        ],
        'users' => [
            'baseConnectionName' => env('DB_USER_BASE_CONNECTION'),
            'shardCount'         => 12,
            'modBaseNumber'      => 3,
            'nodeNumber1'        => 1,
            'nodeNumber2'        => 2,
            'nodeNumber3'        => 3,
            'node1ShardIds'      => [1, 4, 7, 10],
            'node2ShardIds'      => [2, 5, 8, 11],
            'node3ShardIds'      => [3, 6, 9, 12],
        ]
    ],
    // unitTestで使う場合のコネクション名。単一のコネクションに接続させる。
    'unitTest' => [
        'database' => [
            'baseConnectionName' => 'mysql_testing',
        ],
    ],
    // CIなどで使う場合のコネクション名。単一のコネクションに接続させる。
    'ci' => [
        'database' => [
            'baseConnectionName' => 'sqlite',
        ],
    ],
    'mainColor' => env('MAIN_COLOR', '#343a40'),
];
