<?php

class HomeController extends Controller
{

    public function index()
    {
        $productModel = new Product();
        $products = $productModel->getAll();

        $this->render('home.index', [
            'title' => 'Accueil - VinShop',
            'products' => $products
        ]);
    }
}
