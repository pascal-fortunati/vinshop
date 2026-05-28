<?php
require_once 'models/Product.php';

class Cart {
    public function add($productId, $quantity = 1) {
        if (!isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] = 0;
        }
        $_SESSION['cart'][$productId] += $quantity;
    }

    public function remove($productId) {
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
    }

    public function update($productId, $quantity) {
        if ($quantity <= 0) {
            $this->remove($productId);
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
    }

    public function getItems() {
        $items = [];
        $productModel = new Product();
        
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $product = $productModel->getById($productId);
            if ($product) {
                $product['quantity'] = $quantity;
                $product['subtotal'] = $product['price'] * $quantity;
                $items[] = $product;
            }
        }
        
        return $items;
    }

    public function getTotal() {
        $total = 0;
        $items = $this->getItems();
        foreach ($items as $item) {
            $total += $item['subtotal'];
        }
        return $total;
    }

    public function getCount() {
        return array_sum($_SESSION['cart']);
    }

    public function clear() {
        $_SESSION['cart'] = [];
    }
}