<?php

return [
    'pools' => [
        'users' => [
            'model' => 'App\User',
            'column' => 'name',
            'notification' => 'App\Notifications\UserMentioned',
            'auto_notify' => true
        ]
    ]
];
