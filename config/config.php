<?php
return [
    'environment' => 'dev', // accepted values: dev, prod, test
    'basePath' => '/',
    'theme' => '',
    'sessionName' => 'Rakitan\Auth',
    'db' => [
        'default' => [
            'dsn' => 'mysql:host=localhost;dbname=rakitan',
            'username' => 'root',
            'password' => 'password',
        ]
    ]
];
