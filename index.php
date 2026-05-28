<?php

/**
 * VinShop - Application de Marketplace E-commerce
 * Point d'entrée de l'application
 */

// Démarrer la session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Charger l'autoloader
require_once __DIR__ . '/core/Autoloader.php';
Autoloader::register();

// Charger les helpers
require_once __DIR__ . '/helpers/Helper.php';
require_once __DIR__ . '/helpers/Auth.php';
require_once __DIR__ . '/helpers/ReviewHelper.php';

// Initialiser le panier dans la session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Activer le mode debug si configuré
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Initialiser le routeur
$router = new Router();

// Charger les routes
require_once __DIR__ . '/routes/web.php';

// Résoudre et exécuter la route
try {
    $router->resolve();
} catch (Exception $e) {
    // Gérer les erreurs de manière élégante
    http_response_code(500);

    if (config('app.debug', false)) {
        echo '<h1>Erreur</h1>';
        echo '<p>' . e($e->getMessage()) . '</p>';
        echo '<pre>' . e($e->getTraceAsString()) . '</pre>';
    } else {
        echo '<h1>Une erreur est survenue</h1>';
        logger($e->getMessage(), 'error');
    }
}
