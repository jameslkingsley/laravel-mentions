<?php

return [
    // Pools are what you reference on the front-end
    // They contain the model that will be mentioned
    'pools' => [
        'users' => [
            // Model that will be mentioned
            'model' => 'App\User',

            // The column that will be used to search the model
            'column' => 'name',

            // Notification class to use when this model is mentioned
            'notification' => 'App\Notifications\UserMentioned',

            // Automatically notify upon mentions
            'auto_notify' => true
        ]
    ]
];
