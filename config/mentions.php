<?php

return [
    'pools' => [
        'users' => [
            'model' => 'App\User',
            'columns' => ['name', 'email'],
            'notification' => 'App\Notifications\UserMentioned',
            'auto_notify' => true
        ]
    ]
];
