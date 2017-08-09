<?php

return [

    'driver' => 'smtp',

    'host' => 'smtp.gmail.com',

    'port' => 587,

    'from' => [
        'address' => 'administrator@amadeo.id',
        'name' => 'Administrator',
    ],

    'encryption' => 'tls',

    'username' => 'fourline66@gmail.com',

    'password' => 'yejluynhqogvmfrc',

    'sendmail' => '/usr/sbin/sendmail -bs',

    'markdown' => [
        'theme' => 'default',

        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

];
