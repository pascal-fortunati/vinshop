<?php

/**
 * Helper - Fonctions utilitaires globales
 */

/**
 * Générer une URL
 */
function url($path = '')
{
    // Utiliser l'URL de base depuis la configuration
    $config = require __DIR__ . '/../config/app.php';
    $baseUrl = rtrim($config['url'], '/');
    return $baseUrl . '/' . ltrim($path, '/');
}

/**
 * Générer une URL de ressource (assets)
 */
function asset($path)
{
    $config = require __DIR__ . '/../config/app.php';
    $baseUrl = rtrim($config['url'], '/');
    return $baseUrl . '/public/' . ltrim($path, '/');
}

/**
 * Échapper les données HTML
 */
function e($string)
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Afficher une valeur et arrêter l'exécution
 */
function dd(...$vars)
{
    echo '<pre style="background: #1e1e1e; color: #dcdcdc; padding: 20px; border-radius: 5px; margin: 20px;">';
    foreach ($vars as $var) {
        var_dump($var);
    }
    echo '</pre>';
    die();
}

/**
 * Afficher une valeur sans arrêter
 */
function dump(...$vars)
{
    echo '<pre style="background: #1e1e1e; color: #dcdcdc; padding: 20px; border-radius: 5px; margin: 20px;">';
    foreach ($vars as $var) {
        var_dump($var);
    }
    echo '</pre>';
}

/**
 * Obtenir une valeur de session avec message flash
 */
function flash($key, $default = null)
{
    if (isset($_SESSION[$key])) {
        $value = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $value;
    }
    return $default;
}

/**
 * Définir une valeur de session flash
 */
function setFlash($key, $value)
{
    $_SESSION[$key] = $value;
}

/**
 * Vérifier si une session flash existe
 */
function hasFlash($key)
{
    return isset($_SESSION[$key]);
}

/**
 * Obtenir l'ancienne valeur d'un champ (après validation)
 */
function old($key, $default = '')
{
    if (isset($_SESSION['old'][$key])) {
        $value = $_SESSION['old'][$key];
        return $value;
    }
    return $default;
}

/**
 * Nettoyer les anciennes valeurs
 */
function clearOld()
{
    unset($_SESSION['old']);
}

/**
 * Obtenir les erreurs de validation
 */
function errors($key = null)
{
    if ($key) {
        return $_SESSION['errors'][$key] ?? [];
    }
    return $_SESSION['errors'] ?? [];
}

/**
 * Vérifier si un champ a des erreurs
 */
function hasError($key)
{
    return isset($_SESSION['errors'][$key]);
}

/**
 * Nettoyer les erreurs
 */
function clearErrors()
{
    unset($_SESSION['errors']);
}

/**
 * Formater un prix
 */
function formatPrice($price)
{
    return number_format($price, 2, ',', ' ') . ' €';
}

/**
 * Formater une date
 */
function formatDate($date, $format = 'd/m/Y H:i')
{
    if (is_string($date)) {
        $date = new DateTime($date);
    }
    return $date->format($format);
}

/**
 * Tronquer un texte
 */
function truncate($text, $length = 100, $suffix = '...')
{
    if (mb_strlen($text, 'UTF-8') <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length, 'UTF-8') . $suffix;
}

/**
 * Vérifier si l'utilisateur est connecté
 */
function isAuth()
{
    return Auth::check();
}

/**
 * Vérifier si l'utilisateur est admin
 */
function isAdmin()
{
    return Auth::isAdmin();
}

/**
 * Obtenir l'utilisateur connecté
 */
function currentUser()
{
    return Auth::user();
}

/**
 * Obtenir l'ID de l'utilisateur connecté
 */
function userId()
{
    return Auth::id();
}

/**
 * Générer un token CSRF
 */
function csrfToken()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Vérifier un token CSRF
 */
function verifyCsrf($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Générer un champ CSRF caché
 */
function csrfField()
{
    return '<input type="hidden" name="csrf_token" value="' . csrfToken() . '">';
}

/**
 * Vérifier si la route actuelle correspond
 */
function isRoute($route)
{
    $currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $currentUri = '/' . trim($currentUri, '/');
    $route = '/' . trim($route, '/');

    // Route exacte
    if ($currentUri === $route) {
        return true;
    }

    // Cas spécial pour la racine
    if ($route === '/' && $currentUri === '/') {
        return true;
    }

    // Vérifier si l'URI commence par la route (pour les sections)
    if ($route !== '/' && strpos($currentUri, $route) === 0) {
        return true;
    }

    return false;
}

/**
 * Vérifier si on est dans une section (pour les dropdowns)
 */
function isSection($section)
{
    $currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $currentUri = '/' . trim($currentUri, '/');
    $section = '/' . trim($section, '/');

    // Cas spécial pour la racine
    return strpos($currentUri, $section) === 0;
}

/**
 *  Ajouter la classe active si la route correspond
 */
function activeRoute($route, $class = 'active')
{
    return isRoute($route) ? $class : '';
}

/**
 * Ajouter la classe active si la section correspond
 */
function activeSection($section, $class = 'active')
{
    return isSection($section) ? $class : '';
}

/**
 * Générer un slug URL-friendly
 */
function slugify($text)
{
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}

/**
 * Générer l'URL d'un produit
 */
function productUrl($product)
{
    $slug = !empty($product['slug']) ? $product['slug'] : $product['id'];
    return url('/products/' . $slug);
}

/**
 * Obtenir une valeur dans un tableau avec clé imbriquée (dot notation)
 */
function arrayGet($array, $key, $default = null)
{
    if (isset($array[$key])) {
        return $array[$key];
    }

    foreach (explode('.', $key) as $segment) {
        if (!is_array($array) || !array_key_exists($segment, $array)) {
            return $default;
        }
        $array = $array[$segment];
    }

    return $array;
}

/**
 * Générer un UUID v4
 */
function uuid()
{
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
}

/**
 * Obtenir une valeur de configuration par clé
 */
function config($key, $default = null)
{
    static $config = [];

    if (empty($config)) {
        $configFile = __DIR__ . '/../config/app.php';
        if (file_exists($configFile)) {
            $config = require $configFile;
        }
    }

    return arrayGet($config, $key, $default);
}

/**
 * Logger simple dans un fichier log
 */
function logger($message, $level = 'info')
{
    $logFile = __DIR__ . '/../storage/logs/app.log';
    $logDir = dirname($logFile);

    if (!file_exists($logDir)) {
        mkdir($logDir, 0755, true);
    }

    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] [{$level}] {$message}" . PHP_EOL;

    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

/**
 * Helper de pagination
 * 
 * @param array $pagination Tableau contenant: current, per_page, total, last_page
 * @param string $baseUrl URL de base (ex: '/admin/products')
 * @param array $queryParams Paramètres de query string à préserver (optionnel)
 * @return void Affiche le HTML de la pagination
 */
function renderPagination($pagination, $baseUrl, $queryParams = [])
{
    if (empty($pagination) || $pagination['last_page'] <= 1) {
        return;
    }

    // Fonction pour générer l'URL d'une page
    $pageUrl = function ($page) use ($baseUrl, $queryParams) {
        $params = $queryParams;
        $params['page'] = $page;
        return url($baseUrl) . '?' . http_build_query($params);
    };

    $startItem = ($pagination['current'] - 1) * $pagination['per_page'] + 1;
    $endItem = min($pagination['current'] * $pagination['per_page'], $pagination['total']);
?>

    <div class="d-flex justify-content-between align-items-center my-4">
        <div class="text-muted">
            Affichage de <strong><?= $startItem ?></strong> à <strong><?= $endItem ?></strong> sur <strong><?= $pagination['total'] ?></strong> éléments
        </div>
        <nav aria-label="Pagination">
            <ul class="pagination mb-0">
                <!-- Bouton Précédent -->
                <li class="page-item <?= $pagination['current'] <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= $pagination['current'] <= 1 ? '#' : $pageUrl($pagination['current'] - 1) ?>" aria-label="Précédent">
                        &laquo;
                    </a>
                </li>

                <?php
                $maxLinks = 7; // nombre maximum de liens visibles
                $start = max(1, $pagination['current'] - intval($maxLinks / 2));
                $end = min($pagination['last_page'], $start + $maxLinks - 1);

                // Ajuster le début si on est proche de la fin
                if ($end - $start + 1 < $maxLinks) {
                    $start = max(1, $end - $maxLinks + 1);
                }

                // Afficher les liens de page
                for ($p = $start; $p <= $end; $p++) :
                ?>
                    <li class="page-item <?= $p == $pagination['current'] ? 'active' : '' ?>">
                        <a class="page-link" href="<?= $pageUrl($p) ?>"><?= $p ?></a>
                    </li>
                <?php endfor; ?>

                <!-- Bouton Suivant -->
                <li class="page-item <?= $pagination['current'] >= $pagination['last_page'] ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= $pagination['current'] >= $pagination['last_page'] ? '#' : $pageUrl($pagination['current'] + 1) ?>" aria-label="Suivant">
                        &raquo;
                    </a>
                </li>
            </ul>
        </nav>
    </div>
<?php
}

/**
 * Afficher les messages flash sous forme de toasts avec SweetAlert2
 */
function renderFlashMessages()
{
    $messages = [];
    $types = ['success', 'error', 'warning', 'info'];

    foreach ($types as $type) {
        if (isset($_SESSION[$type])) {
            $messages[] = [
                'type' => $type,
                'message' => $_SESSION[$type]
            ];
            unset($_SESSION[$type]);
        }
    }

    if (empty($messages)) {
        return;
    }

    // Convertir en JSON pour JavaScript
    $messagesJson = json_encode($messages, JSON_HEX_TAG | JSON_HEX_AMP);
?>
    <script>
        (function() {
            const flashMessages = <?= $messagesJson ?>;

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            flashMessages.forEach((msg) => {
                Toast.fire({
                    icon: msg.type === 'error' ? 'error' : msg.type,
                    title: msg.message
                });
            });
        })();
    </script>
<?php
}
