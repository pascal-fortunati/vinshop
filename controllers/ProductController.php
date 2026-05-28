<?php

class ProductController extends Controller
{

    public function index()
    {
        $productModel = new Product();

        // Récupérer la plage de prix pour le slider (avant les filtres)
        $priceRange = $productModel->getPriceRange();

        // Récupérer les filtres depuis l'URL
        $filters = [
            'category' => $_GET['category'] ?? null,
            'min_price' => isset($_GET['min_price']) && $_GET['min_price'] !== '' ? $_GET['min_price'] : null,
            'max_price' => isset($_GET['max_price']) && $_GET['max_price'] !== '' ? $_GET['max_price'] : null,
            'stock_status' => $_GET['stock_status'] ?? null,
            'sort' => $_GET['sort'] ?? 'date_desc'
        ];

        // Créer un tableau de filtres pour la requête (sans les valeurs null sauf sort)
        $queryFilters = [];
        foreach ($filters as $key => $value) {
            if ($value !== null && $value !== '') {
                $queryFilters[$key] = $value;
            }
        }

        // Pagination
        $perPage = 8;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $perPage;

        // Récupérer les produits filtrés (paginated)
        $products = $productModel->filterPaginated($queryFilters, $perPage, $offset);
        $totalProducts = $productModel->countFiltered($queryFilters);
        $totalPages = (int)ceil($totalProducts / $perPage);

        $pagination = [
            'current' => $page,
            'per_page' => $perPage,
            'total' => $totalProducts,
            'last_page' => $totalPages
        ];

        // Titre dynamique
        $title = 'Produits';
        if (!empty($filters['category'])) {
            $title .= ' - ' . e($filters['category']);
        }
        $title .= ' - VinShop';

        $this->render('products.index', [
            'title' => $title,
            'products' => $products,
            'currentCategory' => $filters['category'] ?? null,
            'filters' => $filters,
            'priceRange' => $priceRange,
            'pagination' => $pagination
        ]);
    }

    public function show($slug)
    {
        if (!$slug) {
            $this->redirect('/products', 'Produit introuvable', 'error');
        }

        $productModel = new Product();

        // Essayer d'abord avec le slug
        $product = $productModel->getBySlug($slug);

        // Si pas trouvé et que c'est un nombre, essayer avec l'ID (rétrocompatibilité)
        if (!$product && is_numeric($slug)) {
            $product = $productModel->getById($slug);
            // Rediriger vers l'URL avec slug
            if ($product && !empty($product['slug'])) {
                $this->redirect('/products/' . $product['slug']);
            }
        }

        if (!$product) {
            $this->redirect('/products', 'Produit introuvable', 'error');
        }

        // Récupérer les avis du produit
        $reviewModel = new Review();
        $reviews = $reviewModel->getByProduct($product['id'], 'approved');
        $ratingData = $reviewModel->getAverageRating($product['id']);

        // Vérifier si l'utilisateur a déjà laissé un avis
        $hasReviewed = false;
        if (isset($_SESSION['user_id'])) {
            $hasReviewed = $reviewModel->hasUserReviewed($product['id'], $_SESSION['user_id']);
        }

        $this->render('products.show', [
            'title' => e($product['name']) . ' - VinShop',
            'product' => $product,
            'reviews' => $reviews,
            'averageRating' => $ratingData['average'] ?? 0,
            'reviewCount' => $ratingData['count'] ?? 0,
            'hasReviewed' => $hasReviewed
        ]);
    }

    public function searchApi()
    {
        header('Content-Type: application/json');

        $query = $_GET['q'] ?? '';

        if (strlen($query) < 2) {
            echo json_encode(['products' => []]);
            return;
        }

        $productModel = new Product();
        $products = $productModel->search($query, 5);

        // Formater les résultats pour l'autocomplete
        $results = array_map(function ($product) {
            return [
                'id' => $product['id'],
                'slug' => $product['slug'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'category' => $product['category'],
                'url' => url('/products/' . $product['slug'])
            ];
        }, $products);

        echo json_encode(['products' => $results]);
    }

    public function searchResults()
    {
        $query = $_GET['q'] ?? '';

        if (empty($query)) {
            $this->redirect('/products', 'Veuillez entrer un terme de recherche', 'warning');
        }

        $productModel = new Product();
        $products = $productModel->search($query, 100); // Limite plus élevée pour la page de résultats

        $this->render('products.search', [
            'title' => 'Recherche: ' . e($query) . ' - VinShop',
            'products' => $products,
            'query' => $query,
            'count' => count($products)
        ]);
    }
}
