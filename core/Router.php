<?php

class Router
{
    private $routes = [];
    private $namedRoutes = [];
    private $currentRoute = null;
    private $groupPrefix = '';
    private $groupMiddleware = null;

    // Ajouter une route GET
    public function get($path, $callback, $name = null)
    {
        return $this->addRoute('GET', $path, $callback, $name);
    }

    // Ajouter une route POST
    public function post($path, $callback, $name = null)
    {
        return $this->addRoute('POST', $path, $callback, $name);
    }

    // Ajouter une route PUT
    public function put($path, $callback, $name = null)
    {
        return $this->addRoute('PUT', $path, $callback, $name);
    }

    // Ajouter une route DELETE
    public function delete($path, $callback, $name = null)
    {
        return $this->addRoute('DELETE', $path, $callback, $name);
    }

    // Ajouter une route pour toutes les méthodes
    public function any($path, $callback, $name = null)
    {
        return $this->addRoute(['GET', 'POST', 'PUT', 'DELETE'], $path, $callback, $name);
    }

    // Ajouter une route
    private function addRoute($method, $path, $callback, $name = null)
    {
        // Appliquer le préfixe du groupe si défini
        if ($this->groupPrefix) {
            $path = rtrim($this->groupPrefix, '/') . '/' . ltrim($path, '/');
        }

        $methods = is_array($method) ? $method : [$method];

        foreach ($methods as $m) {
            $this->routes[$m][$path] = [
                'callback' => $callback,
                'name' => $name,
                'middleware' => $this->groupMiddleware
            ];
        }

        if ($name) {
            $this->namedRoutes[$name] = $path;
        }

        return $this;
    }

    // Définir un groupe de routes avec des options
    public function group($options, $callback)
    {
        $prefix = $options['prefix'] ?? '';
        $middleware = $options['middleware'] ?? null;

        // Sauvegarder l'état précédent
        $previousPrefix = $this->groupPrefix;
        $previousMiddleware = $this->groupMiddleware;

        // Appliquer les nouvelles options
        $this->groupPrefix = $previousPrefix . $prefix;
        $this->groupMiddleware = $middleware ?? $previousMiddleware;

        // Exécuter le callback pour définir les routes du groupe
        $callback($this);

        // Restaurer l'état précédent
        $this->groupPrefix = $previousPrefix;
        $this->groupMiddleware = $previousMiddleware;
    }

    // Résoudre la route courante
    public function resolve()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        // Extraire le chemin sans les paramètres de requête
        $uri = parse_url($uri, PHP_URL_PATH);

        // Enlever le nom du script si présent
        $uri = str_replace('/index.php', '', $uri);

        // Normaliser l'URI
        $uri = '/' . trim($uri, '/');
        if ($uri !== '/') {
            $uri = rtrim($uri, '/');
        }

        // Chercher une correspondance exacte
        if (isset($this->routes[$method][$uri])) {
            return $this->executeRoute($this->routes[$method][$uri], []);
        }

        // Chercher une correspondance avec des paramètres
        foreach ($this->routes[$method] ?? [] as $path => $route) {
            $pattern = $this->convertPathToRegex($path);
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Retirer la correspondance complète
                $this->currentRoute = $route;
                return $this->executeRoute($route, $matches);
            }
        }

        // Aucune route trouvée - afficher la page 404
        http_response_code(404);
        $this->notFound();
    }

    // Convertir un chemin avec paramètres en expression régulière
    private function convertPathToRegex($path)
    {
        // Remplacer {param} par un groupe de capture
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $path);
        // Échapper les slashes
        $pattern = str_replace('/', '\/', $pattern);
        return '/^' . $pattern . '$/';
    }

    // Exécuter la route avec les paramètres extraits
    private function executeRoute($route, $params)
    {
        // Vérifier le middleware
        if (isset($route['middleware'])) {
            $middleware = $route['middleware'];
            if (is_callable($middleware)) {
                $result = call_user_func($middleware);
                if ($result === false) {
                    return;
                }
            } elseif (is_string($middleware)) {
                // Exécuter le middleware par nom (ex: 'auth', 'admin')
                $this->executeMiddleware($middleware);
            }
        }

        $callback = $route['callback'];

        if (is_callable($callback)) {
            return call_user_func_array($callback, $params);
        }

        if (is_string($callback)) {
            // Attendre un format "Controller@method"
            if (strpos($callback, '@') === false) {
                throw new Exception("Invalid callback format. Expected 'Controller@method', got '{$callback}'");
            }

            [$controller, $method] = explode('@', $callback, 2);

            $controllerClass = $controller;
            if (!class_exists($controllerClass)) {
                throw new Exception("Controller {$controllerClass} not found");
            }

            $controllerInstance = new $controllerClass();

            if (!method_exists($controllerInstance, $method)) {
                throw new Exception("Method {$method} not found in {$controllerClass}");
            }

            return call_user_func_array([$controllerInstance, $method], $params);
        }
    }

    // Exécuter un middleware par nom
    private function executeMiddleware($name)
    {
        switch ($name) {
            case 'auth':
                Auth::requireLogin();
                break;
            case 'admin':
                Auth::requireAdmin();
                break;
            case 'guest':
                if (Auth::check()) {
                    header('Location: /');
                    exit;
                }
                break;
        }
    }

    // Générer une URL à partir d'un nom de route
    public function route($name, $params = [])
    {
        if (!isset($this->namedRoutes[$name])) {
            throw new Exception("Route {$name} not found");
        }

        $path = $this->namedRoutes[$name];

        // Remplacer les paramètres dans le chemin
        foreach ($params as $key => $value) {
            $path = str_replace('{' . $key . '}', $value, $path);
        }

        return $path;
    }

    // Page 404
    private function notFound()
    {
        echo '<!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>404 - Page non trouvée</title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/5.3.2/lux/bootstrap.min.css">
        </head>
        <body>
            <div class="container mt-5 text-center">
                <h1 class="display-1">404</h1>
                <p class="lead">Page non trouvée</p>
                <a href="/" class="btn btn-primary">Retour à l\'accueil</a>
            </div>
        </body>
        </html>';
    }
}
