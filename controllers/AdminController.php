<?php

class AdminController extends Controller
{

    // Dashboard admin
    public function dashboard()
    {
        $orderModel = new Order();
        $productModel = new Product();
        $userModel = new User();

        $stats = $orderModel->getStatistics();
        $recentOrders = array_slice($orderModel->getAll(), 0, 5);

        // Compter les produits
        $products = $productModel->getAll();
        $stats['total_products'] = count($products);

        // Compter les utilisateurs
        $users = $userModel->getAll();
        $stats['total_users'] = count($users);

        $this->render('admin.dashboard', [
            'title' => 'Dashboard Admin - VinShop',
            'stats' => $stats,
            'recentOrders' => $recentOrders
        ]);
    }

    // Gestion des produits
    public function products()
    {
        $productModel = new Product();

        // Pagination
        $perPage = 20;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $perPage;

        $products = $productModel->getAllPaginated($perPage, $offset);
        $totalProducts = $productModel->countAll();
        $totalPages = (int)ceil($totalProducts / $perPage);

        $pagination = [
            'current' => $page,
            'per_page' => $perPage,
            'total' => $totalProducts,
            'last_page' => $totalPages
        ];

        $this->render('admin.products', [
            'title' => 'Gestion des produits - VinShop',
            'products' => $products,
            'pagination' => $pagination
        ]);
    }

    public function create()
    {
        $this->render('admin.form', [
            'title' => 'Ajouter un produit - VinShop',
            'product' => null
        ]);
    }

    public function store()
    {
        $productModel = new Product();

        $productModel->name = $_POST['name'];
        $productModel->description = $_POST['description'];
        $productModel->price = $_POST['price'];
        $productModel->category = $_POST['category'];
        $productModel->condition = $_POST['condition'];
        $productModel->stock = $_POST['stock'] ?? 1;
        $productModel->seller_id = $_SESSION['user_id'];

        // Upload image
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $uploadDir = 'public/uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = time() . '_' . $_FILES['image']['name'];
            $uploadFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                $productModel->image = $fileName;
            }
        } else {
            $productModel->image = 'default.jpg';
        }

        if ($productModel->create()) {
            $this->redirect('/admin/products', 'Produit créé avec succès', 'success');
        } else {
            $this->redirect('/admin/products/create', 'Erreur lors de la création du produit', 'error');
        }
    }

    public function edit($id)
    {
        if (!$id) {
            $this->redirect('/admin/products', 'ID invalide', 'error');
        }

        $productModel = new Product();
        $product = $productModel->getById($id);

        if (!$product) {
            $this->redirect('/admin/products', 'Produit introuvable', 'error');
        }

        $this->render('admin.form', [
            'title' => 'Modifier le produit - VinShop',
            'product' => $product
        ]);
    }

    public function update()
    {
        $productModel = new Product();

        $productModel->id = $_POST['id'];
        $productModel->name = $_POST['name'];
        $productModel->description = $_POST['description'];
        $productModel->price = $_POST['price'];
        $productModel->category = $_POST['category'];
        $productModel->condition = $_POST['condition'];
        $productModel->stock = $_POST['stock'] ?? 1;

        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $uploadDir = 'public/uploads/';
            $fileName = time() . '_' . $_FILES['image']['name'];
            $uploadFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                $productModel->image = $fileName;
            }
        } else {
            $productModel->image = $_POST['current_image'];
        }

        if ($productModel->update()) {
            $this->redirect('/admin/products', 'Produit modifié avec succès', 'success');
        } else {
            $this->redirect('/admin/products/' . $_POST['id'] . '/edit', 'Erreur lors de la modification', 'error');
        }
    }

    public function delete()
    {
        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->json(['success' => false, 'message' => 'ID invalide'], 400);
        }

        $productModel = new Product();

        if ($productModel->delete($id)) {
            $this->json(['success' => true, 'message' => 'Produit supprimé avec succès']);
        } else {
            $this->json(['success' => false, 'message' => 'Erreur lors de la suppression'], 500);
        }
    }

    // Gestion des commandes
    public function orders()
    {
        $orderModel = new Order();

        // Pagination
        $perPage = 20;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $perPage;

        $orders = $orderModel->getAllPaginated($perPage, $offset);
        $totalOrders = $orderModel->countAll();
        $totalPages = (int)ceil($totalOrders / $perPage);

        $pagination = [
            'current' => $page,
            'per_page' => $perPage,
            'total' => $totalOrders,
            'last_page' => $totalPages
        ];

        $this->render('admin.orders', [
            'title' => 'Gestion des commandes - VinShop',
            'orders' => $orders,
            'pagination' => $pagination
        ]);
    }

    public function orderDetails($id)
    {
        if (!$id) {
            $this->redirect('/admin/orders', 'ID invalide', 'error');
        }

        $orderModel = new Order();
        $order = $orderModel->getById($id);

        if (!$order) {
            $this->redirect('/admin/orders', 'Commande introuvable', 'error');
        }

        $items = $orderModel->getItems($id);

        $this->render('admin.order_details', [
            'title' => 'Commande #' . $id . ' - VinShop',
            'order' => $order,
            'items' => $items
        ]);
    }

    public function updateOrderStatus()
    {
        $orderId = $_POST['order_id'] ?? null;
        $status = $_POST['status'] ?? null;

        if (!$orderId || !$status) {
            $this->json(['success' => false, 'message' => 'Données invalides'], 400);
        }

        $orderModel = new Order();

        if ($orderModel->updateStatus($orderId, $status)) {
            $this->json(['success' => true, 'message' => 'Statut mis à jour']);
        } else {
            $this->json(['success' => false, 'message' => 'Erreur'], 500);
        }
    }

    // Gestion des utilisateurs
    public function users()
    {
        $userModel = new User();

        // Pagination
        $perPage = 20;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $perPage;

        $users = $userModel->getAllPaginated($perPage, $offset);
        $totalUsers = $userModel->countAll();
        $totalPages = (int)ceil($totalUsers / $perPage);

        $pagination = [
            'current' => $page,
            'per_page' => $perPage,
            'total' => $totalUsers,
            'last_page' => $totalPages
        ];

        $this->render('admin.users', [
            'title' => 'Gestion des utilisateurs - VinShop',
            'users' => $users,
            'pagination' => $pagination
        ]);
    }

    public function userDetails($id)
    {
        header('Content-Type: application/json');

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID invalide']);
            exit;
        }

        $userModel = new User();
        $user = $userModel->getById($id);

        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Utilisateur introuvable']);
            exit;
        }

        // Récupérer les statistiques de l'utilisateur
        $orderModel = new Order();
        $db = new Database();
        $conn = $db->getConnection();

        // Statistiques des commandes
        $stmt = $conn->prepare("SELECT COUNT(*) as total_orders, COALESCE(SUM(total), 0) as total_spent FROM orders WHERE user_id = ?");
        $stmt->execute([$id]);
        $orderStats = $stmt->fetch(PDO::FETCH_ASSOC);

        // Favoris
        $stmt = $conn->prepare("SELECT COUNT(*) as wishlist_count FROM wishlist WHERE user_id = ?");
        $stmt->execute([$id]);
        $wishlistStats = $stmt->fetch(PDO::FETCH_ASSOC);

        // Avis
        $stmt = $conn->prepare("SELECT COUNT(*) as reviews_count FROM reviews WHERE user_id = ?");
        $stmt->execute([$id]);
        $reviewStats = $stmt->fetch(PDO::FETCH_ASSOC);

        // Dernières commandes (5 max)
        $stmt = $conn->prepare("SELECT id, total, status, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
        $stmt->execute([$id]);
        $recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Formater les commandes pour l'affichage
        foreach ($recentOrders as &$order) {
            $order['total_formatted'] = formatPrice($order['total']);
        }

        $stats = [
            'total_orders' => (int) $orderStats['total_orders'],
            'total_spent' => (float) $orderStats['total_spent'],
            'total_spent_formatted' => formatPrice($orderStats['total_spent']),
            'wishlist_count' => (int) $wishlistStats['wishlist_count'],
            'reviews_count' => (int) $reviewStats['reviews_count'],
            'recent_orders' => $recentOrders
        ];

        echo json_encode([
            'success' => true,
            'user' => $user,
            'stats' => $stats
        ]);
        exit;
    }
}
