<?php

class WishlistController extends Controller
{
    /**
     * Afficher la page wishlist
     */
    public function index()
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login', 'Veuillez vous connecter pour accéder à votre liste de souhaits', 'warning');
            return;
        }

        $wishlistModel = new Wishlist();
        $products = $wishlistModel->getByUser($_SESSION['user_id']);

        $this->render('wishlist.index', [
            'title' => 'Ma Liste de Souhaits - VinShop',
            'products' => $products,
            'count' => count($products)
        ]);
    }

    /**
     * Ajouter un produit à la wishlist
     */
    public function add()
    {
        header('Content-Type: application/json');

        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Vous devez être connecté'
            ]);
            return;
        }

        $product_id = $_POST['product_id'] ?? null;

        if (!$product_id) {
            echo json_encode([
                'success' => false,
                'message' => 'Produit invalide'
            ]);
            return;
        }

        // Vérifier que le produit existe
        $productModel = new Product();
        $product = $productModel->getById($product_id);

        if (!$product) {
            echo json_encode([
                'success' => false,
                'message' => 'Produit introuvable'
            ]);
            return;
        }

        $wishlistModel = new Wishlist();

        // Vérifier si déjà dans la wishlist
        if ($wishlistModel->isInWishlist($_SESSION['user_id'], $product_id)) {
            echo json_encode([
                'success' => false,
                'message' => 'Ce produit est déjà dans vos favoris',
                'inWishlist' => true
            ]);
            return;
        }

        // Ajouter à la wishlist
        if ($wishlistModel->add($_SESSION['user_id'], $product_id)) {
            $count = $wishlistModel->count($_SESSION['user_id']);
            echo json_encode([
                'success' => true,
                'message' => 'Produit ajouté à vos favoris',
                'count' => $count,
                'inWishlist' => true
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout aux favoris'
            ]);
        }
    }

    /**
     * Retirer un produit de la wishlist
     */
    public function remove()
    {
        header('Content-Type: application/json');

        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Vous devez être connecté'
            ]);
            return;
        }

        $product_id = $_POST['product_id'] ?? null;

        if (!$product_id) {
            echo json_encode([
                'success' => false,
                'message' => 'Produit invalide'
            ]);
            return;
        }

        $wishlistModel = new Wishlist();

        if ($wishlistModel->remove($_SESSION['user_id'], $product_id)) {
            $count = $wishlistModel->count($_SESSION['user_id']);
            echo json_encode([
                'success' => true,
                'message' => 'Produit retiré de vos favoris',
                'count' => $count,
                'inWishlist' => false
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors du retrait des favoris'
            ]);
        }
    }

    /**
     * Récupérer les IDs de la wishlist (pour marquer les produits favoris)
     */
    public function getIds()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['ids' => []]);
            return;
        }

        $wishlistModel = new Wishlist();
        $ids = $wishlistModel->getUserWishlistIds($_SESSION['user_id']);

        echo json_encode(['ids' => $ids]);
    }
}
