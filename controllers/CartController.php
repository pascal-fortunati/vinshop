<?php

class CartController extends Controller
{
    private $cart;

    public function __construct()
    {
        $this->cart = new Cart();
    }

    public function index()
    {
        $items = $this->cart->getItems();
        $total = $this->cart->getTotal();

        $this->render('cart.index', [
            'title' => 'Mon Panier - VinShop',
            'items' => $items,
            'total' => $total
        ]);
    }

    public function add()
    {
        $productId = $_POST['product_id'] ?? null;
        $quantity = $_POST['quantity'] ?? 1;

        if (!$productId) {
            $this->json(['success' => false, 'message' => 'Produit invalide'], 400);
        }

        $this->cart->add($productId, $quantity);

        $this->json([
            'success' => true,
            'message' => 'Produit ajouté au panier',
            'cart_count' => $this->cart->getCount()
        ]);
    }

    public function remove()
    {
        $productId = $_POST['product_id'] ?? null;

        if (!$productId) {
            $this->json(['success' => false, 'message' => 'Produit invalide'], 400);
        }

        $this->cart->remove($productId);

        $this->json([
            'success' => true,
            'message' => 'Produit retiré du panier',
            'cart_count' => $this->cart->getCount()
        ]);
    }

    public function update()
    {
        $productId = $_POST['product_id'] ?? null;
        $quantity = $_POST['quantity'] ?? 0;

        if (!$productId) {
            $this->json(['success' => false, 'message' => 'Produit invalide'], 400);
        }

        $this->cart->update($productId, $quantity);

        $this->json([
            'success' => true,
            'message' => 'Panier mis à jour',
            'cart_count' => $this->cart->getCount()
        ]);
    }

    public function get()
    {
        $items = $this->cart->getItems();
        $total = $this->cart->getTotal();

        $this->json([
            'success' => true,
            'items' => $items,
            'total' => $total,
            'count' => $this->cart->getCount()
        ]);
    }
}
