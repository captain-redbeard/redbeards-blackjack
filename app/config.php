<?php
/**
 * @author captain-redbeard
 * @since 05/02/17
 */

return [
    'app' => [
        'base_dir' =>           __DIR__,
        'timezone' =>           'UTC',
        'user_session' =>       'redbeard_user-BEjKKpZ7wAn3V6SmBKIwfRJY4FTm4cyG',
        'user_role' =>          10,
        'password_cost' =>      12,
        'max_login_attempts' => 5,
        'secure_cookies' =>     true,
        'token_expire_time' =>  900,
        'path' =>               '\\Redbeard\\',
        'default_controller' => 'Home',
        'default_method' =>     'index'
    ],
    
    'database' => [
        'rdbms' =>              'mysql',
        'hostname' =>           '',
        'database' =>           '',
        'username' =>           '',
        'password' =>           '',
        'charset'  =>           'utf8mb4',
    ],
    
    'site' => [
        'name' =>               'Redbeards Blackjack',
        'theme_color' =>        '4aa3df'
    ]
];