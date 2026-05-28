<?php
class Auth
{

    // Vérifier si l'utilisateur est connecté
    public static function check()
    {
        return isset($_SESSION['user_id']);
    }

    // Vérifier si l'utilisateur est admin
    public static function isAdmin()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    // Rediriger si non connecté
    public static function requireLogin()
    {
        if (!self::check()) {
            $_SESSION['error'] = 'Vous devez être connecté pour accéder à cette page';
            $config = require __DIR__ . '/../config/App.php';
            $baseUrl = rtrim($config['url'], '/');
            header('Location: ' . $baseUrl . '/login');
            exit;
        }
    }

    // Rediriger si non admin
    public static function requireAdmin()
    {
        self::requireLogin();
        if (!self::isAdmin()) {
            $_SESSION['error'] = 'Accès refusé. Zone réservée aux administrateurs.';
            $config = require __DIR__ . '/../config/App.php';
            $baseUrl = rtrim($config['url'], '/');
            header('Location: ' . $baseUrl . '/');
            exit;
        }
    }

    // Récupérer l'utilisateur connecté
    public static function user()
    {
        if (!self::check()) {
            return null;
        }

        require_once 'models/User.php';
        $userModel = new User();
        return $userModel->getById($_SESSION['user_id']);
    }

    // Récupérer l'ID de l'utilisateur
    public static function id()
    {
        return $_SESSION['user_id'] ?? null;
    }
}
