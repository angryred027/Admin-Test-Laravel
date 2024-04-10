<?php

return [
    'isMaintenance' =>  env('IS_MAINTENANCE_MODE', false),
    'startTime' =>  env('MAINTENANCE_START_TIME', '2022-01-01 00:00:00'),
    'endTime' =>  env('MAINTENANCE_END_TIME', '2022-01-01 23:59:59'),
    'isEnabplePass' =>  env('IS_ENABLE_PASS_MAINTENANCE', false),
    'exceptRoutes' => [env('MAINTENANCE_EXCEPT_ROUTE', null)],
    'exceptIps' => [env('MAINTENANCE_EXCEPT_IP', null)],
];
