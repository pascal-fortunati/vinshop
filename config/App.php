<?php

return [
    // Application
    'name' => 'VinShop',
    'url' => getenv('APP_URL') ?: 'http://localhost:8080',
    'timezone' => 'Europe/Paris',
    'debug' => true,
    'locale' => 'fr',

    // Base de données
    'database' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'name' => getenv('DB_NAME') ?: 'vinshop',
        'username' => getenv('DB_USER') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: '',
        'charset' => getenv('DB_CHARSET') ?: 'utf8mb4',
    ],

    // Session
    'session' => [
        'lifetime' => 120, // minutes
        'name' => 'VINSHOP_SESSION',
    ],

    // Upload de fichiers
    'upload' => [
        'path' => 'public/uploads/',
        'max_size' => 5 * 1024 * 1024, // 5MB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
    ],

    // Pagination
    'pagination' => [
        'per_page' => 12,
    ],
];
