<?php

/**
 * Fichier de routes de l'application
 * 
 * Utilisez le Router pour définir vos routes de manière propre et organisée
 */

// Route de la page d'accueil
$router->get('/', 'HomeController@index', 'home');

// Routes des produits
$router->get('/products', 'ProductController@index', 'products.index');
$router->get('/products/search', 'ProductController@searchResults', 'products.search');
$router->get('/api/products/search', 'ProductController@searchApi', 'api.products.search');
$router->get('/products/{id}', 'ProductController@show', 'products.show');

// Routes d'authentification (guest only)
$router->group(['middleware' => 'guest'], function ($router) {
    $router->get('/login', 'AuthController@login', 'login');
    $router->post('/login', 'AuthController@loginPost', 'login.post');
    $router->get('/register', 'AuthController@register', 'register');
    $router->post('/register', 'AuthController@registerPost', 'register.post');
});

// Routes d'authentification (auth required)
$router->group(['middleware' => 'auth'], function ($router) {
    $router->get('/logout', 'AuthController@logout', 'logout');
    $router->get('/profile', 'AuthController@profile', 'profile');
    $router->post('/profile/update', 'AuthController@updateProfile', 'profile.update');
});

// Routes du panier
$router->get('/cart', 'CartController@index', 'cart.index');
$router->post('/cart/add', 'CartController@add', 'cart.add');
$router->post('/cart/remove', 'CartController@remove', 'cart.remove');
$router->post('/cart/update', 'CartController@update', 'cart.update');
$router->get('/cart/get', 'CartController@get', 'cart.get');

// Routes de la wishlist
$router->get('/wishlist', 'WishlistController@index', 'wishlist.index');
$router->post('/wishlist/add', 'WishlistController@add', 'wishlist.add');
$router->post('/wishlist/remove', 'WishlistController@remove', 'wishlist.remove');
$router->get('/wishlist/ids', 'WishlistController@getIds', 'wishlist.ids');

// Routes des avis
$router->post('/reviews/store', 'ReviewController@store', 'reviews.store');

// Routes des coupons (API)
$router->post('/coupons/validate', 'CouponController@validateCoupon', 'coupons.validate');

// Routes des commandes (auth required)
$router->group(['prefix' => '/orders', 'middleware' => 'auth'], function ($router) {
    $router->get('/checkout', 'OrderController@checkout', 'orders.checkout');
    $router->post('/process', 'OrderController@process', 'orders.process');
    $router->get('/history', 'OrderController@history', 'orders.history');
    $router->get('/{id}', 'OrderController@show', 'orders.show');
});

// Routes d'administration (admin only)
$router->group(['prefix' => '/admin', 'middleware' => 'admin'], function ($router) {
    // Dashboard (route par défaut /admin)
    $router->get('', 'AdminController@dashboard', 'admin.index');
    $router->get('/dashboard', 'AdminController@dashboard', 'admin.dashboard');

    // Gestion des produits
    $router->get('/products', 'AdminController@products', 'admin.products');
    $router->get('/products/create', 'AdminController@create', 'admin.products.create');
    $router->post('/products/store', 'AdminController@store', 'admin.products.store');
    $router->get('/products/edit/{id}', 'AdminController@edit', 'admin.products.edit');
    $router->post('/products/update', 'AdminController@update', 'admin.products.update');
    $router->post('/products/delete', 'AdminController@delete', 'admin.products.delete');

    // Gestion des commandes
    $router->get('/orders', 'AdminController@orders', 'admin.orders');
    $router->get('/orders/{id}', 'AdminController@orderDetails', 'admin.orders.details');
    $router->post('/orders/update-status', 'AdminController@updateOrderStatus', 'admin.orders.update-status');

    // Gestion des utilisateurs
    $router->get('/users', 'AdminController@users', 'admin.users');
    $router->get('/users/details/{id}', 'AdminController@userDetails', 'admin.users.details');

    // Gestion des avis
    $router->get('/reviews', 'ReviewController@index', 'admin.reviews');
    $router->post('/reviews/moderate', 'ReviewController@moderate', 'admin.reviews.moderate');

    // Gestion des coupons
    $router->get('/coupons', 'CouponController@index', 'admin.coupons');
    $router->get('/coupons/create', 'CouponController@create', 'admin.coupons.create');
    $router->post('/coupons/store', 'CouponController@store', 'admin.coupons.store');
    $router->get('/coupons/edit/{id}', 'CouponController@edit', 'admin.coupons.edit');
    $router->post('/coupons/update/{id}', 'CouponController@update', 'admin.coupons.update');
    $router->post('/coupons/delete/{id}', 'CouponController@delete', 'admin.coupons.delete');
    $router->post('/coupons/toggle/{id}', 'CouponController@toggle', 'admin.coupons.toggle');
});

// Routes légales (RGPD / RGAA)
$router->get('/legal/privacy', 'LegalController@privacy', 'legal.privacy');
$router->get('/legal/terms', 'LegalController@terms', 'legal.terms');
$router->get('/legal/cookies', 'LegalController@cookies', 'legal.cookies');
$router->get('/legal/accessibility', 'LegalController@accessibility', 'legal.accessibility');
