<?php

class OrderController extends Controller
{

    // Page de checkout
    public function checkout()
    {
        $cart = new Cart();
        $items = $cart->getItems();

        if (empty($items)) {
            $this->redirect('/', 'Votre panier est vide', 'error');
        }

        $total = $cart->getTotal();

        $userModel = new User();
        $user = $userModel->getById($_SESSION['user_id']);

        $this->render('orders.checkout', [
            'title' => 'Finaliser ma commande - VinShop',
            'items' => $items,
            'total' => $total,
            'user' => $user
        ]);
    }

    // Valider la commande
    public function process()
    {
        try {
            $cart = new Cart();
            $items = $cart->getItems();

            if (empty($items)) {
                $this->json(['success' => false, 'message' => 'Panier vide'], 400);
                return;
            }

            $orderModel = new Order();
            $orderModel->user_id = $_SESSION['user_id'];
            $orderModel->total = $cart->getTotal();
            $orderModel->status = 'pending';
            $orderModel->payment_method = $_POST['payment_method'] ?? 'card';
            $orderModel->shipping_address = $_POST['shipping_address'] ?? '';
            $orderModel->notes = $_POST['notes'] ?? '';

            $result = $orderModel->create($items);

            // Vérifier si c'est une erreur (array avec 'error') ou un succès (ID numérique)
            if (is_array($result) && isset($result['error'])) {
                $this->json(['success' => false, 'message' => $result['error']], 200);
            } elseif ($result) {
                $cart->clear();
                $this->json([
                    'success' => true,
                    'message' => 'Commande validée avec succès !',
                    'order_id' => $result
                ]);
            } else {
                $this->json(['success' => false, 'message' => 'Erreur lors de la commande'], 200);
            }
        } catch (Exception $e) {
            // Log l'erreur et retourner un JSON
            error_log('Erreur process order: ' . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()], 200);
        }
    }

    // Historique des commandes
    public function history()
    {
        $orderModel = new Order();

        // Pagination
        $perPage = 20;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $perPage;

        $orders = $orderModel->getByUserIdPaginated($_SESSION['user_id'], $perPage, $offset);
        $totalOrders = $orderModel->countByUserId($_SESSION['user_id']);
        $totalPages = (int)ceil($totalOrders / $perPage);

        $pagination = [
            'current' => $page,
            'per_page' => $perPage,
            'total' => $totalOrders,
            'last_page' => $totalPages
        ];

        $this->render('orders.history', [
            'title' => 'Mes Commandes - VinShop',
            'orders' => $orders,
            'pagination' => $pagination
        ]);
    }

    // Détails d'une commande
    public function show($id)
    {
        $orderModel = new Order();
        $order = $orderModel->getById($id);

        // Vérifier que la commande appartient à l'utilisateur ou que c'est un admin
        if (!$order || ($order['user_id'] != $_SESSION['user_id'] && $_SESSION['role'] !== 'admin')) {
            $this->redirect('/orders/history', 'Commande introuvable', 'error');
        }

        $items = $orderModel->getItems($id);

        $this->render('orders.show', [
            'title' => 'Commande #' . $id . ' - VinShop',
            'order' => $order,
            'items' => $items
        ]);
    }
}
