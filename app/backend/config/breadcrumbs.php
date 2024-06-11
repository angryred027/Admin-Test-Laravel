<?php

return [
    'name' => 'admin.home',
    'title' => 'Home',
    'hasParam' => false,
    'list' => [
        [
            'name' => 'admin.test',
            'hasParam' => false,
            'title' => 'Test',
        ],
        [
            'name' => 'admin.sampleImageUploader1',
            'hasParam' => false,
            'title' => 'sampleImageUploader1 Top',
            'list' => [
                [
                    'name' => 'admin.sampleImageUploader1.create',
                    'hasParam' => false,
                    'title' => 'sampleImageUploader1 Create',
                ],
                [
                    'name' => 'admin.sampleImageUploader1.edit',
                    'hasParam' => false,
                    'title' => 'sampleImageUploader1 Edit',
                ],
            ],
        ],
    ],
];
