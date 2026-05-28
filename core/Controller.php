<?php

abstract class Controller
{
    protected $layout = 'layout';
    protected $viewData = [];

    /**
     * Rendre une vue avec le layout
     */
    protected function render($view, $data = [])
    {
        $this->viewData = array_merge($this->viewData, $data);

        // Extraire les données pour la vue
        extract($this->viewData);

        // Commencer la capture du contenu
        ob_start();

        // Inclure la vue
        $viewPath = __DIR__ . '/../views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewPath)) {
            throw new Exception("View {$view} not found at {$viewPath}");
        }

        require $viewPath;

        // Récupérer le contenu de la vue
        $content = ob_get_clean();

        // Inclure le layout
        if ($this->layout) {
            $layoutPath = __DIR__ . '/../views/layouts/' . $this->layout . '.php';

            if (!file_exists($layoutPath)) {
                throw new Exception("Layout {$this->layout} not found at {$layoutPath}");
            }

            require $layoutPath;
        } else {
            echo $content;
        }
    }

    /**
     * Rendre une vue partielle sans layout
     */
    protected function renderPartial($view, $data = [])
    {
        $this->viewData = array_merge($this->viewData, $data);
        extract($this->viewData);

        $viewPath = __DIR__ . '/../views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewPath)) {
            throw new Exception("View {$view} not found at {$viewPath}");
        }

        require $viewPath;
    }

    /**
     * Retourner une réponse JSON
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Rediriger vers une URL
     */
    protected function redirect($url, $message = null, $type = 'info')
    {
        if ($message) {
            $_SESSION[$type] = $message;
        }
        header('Location: ' . $url);
        exit;
    }

    /**
     * Rediriger en arrière
     */
    protected function back($message = null, $type = 'info')
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer, $message, $type);
    }

    /**
     * Valider les données
     */
    protected function validate($data, $rules)
    {
        $errors = [];

        foreach ($rules as $field => $ruleSet) {
            $ruleList = explode('|', $ruleSet);
            $value = $data[$field] ?? null;

            foreach ($ruleList as $rule) {
                // Règle required
                if ($rule === 'required' && empty($value)) {
                    $errors[$field][] = "Le champ {$field} est requis.";
                }

                // Règle email
                if ($rule === 'email' && !empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "Le champ {$field} doit être une adresse email valide.";
                }

                // Règle min:n
                if (strpos($rule, 'min:') === 0) {
                    $min = (int) substr($rule, 4);
                    if (!empty($value) && strlen($value) < $min) {
                        $errors[$field][] = "Le champ {$field} doit contenir au moins {$min} caractères.";
                    }
                }

                // Règle max:n
                if (strpos($rule, 'max:') === 0) {
                    $max = (int) substr($rule, 4);
                    if (!empty($value) && strlen($value) > $max) {
                        $errors[$field][] = "Le champ {$field} ne doit pas dépasser {$max} caractères.";
                    }
                }

                // Règle numeric
                if ($rule === 'numeric' && !empty($value) && !is_numeric($value)) {
                    $errors[$field][] = "Le champ {$field} doit être un nombre.";
                }
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            return false;
        }

        return true;
    }

    /**
     * Définir des données pour la vue
     */
    protected function set($key, $value = null)
    {
        if (is_array($key)) {
            $this->viewData = array_merge($this->viewData, $key);
        } else {
            $this->viewData[$key] = $value;
        }
    }
}
