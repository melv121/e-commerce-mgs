<?php
// Configuration des routes de l'application

$routes = [
    // Page d'accueil
    '' => ['HomeController', 'index'],
    'home' => ['HomeController', 'index'],
    
    // Routes pour les produits
    'product/category' => ['ProductController', 'category'],
    'product/category/:slug' => ['ProductController', 'category'],
    'product/category/:category/:subcategory' => ['ProductController', 'category'],
    'product/detail/:id' => ['ProductController', 'detail'],
    'product/nouveautes' => ['ProductController', 'nouveautes'],
    'product/promotions' => ['ProductController', 'promotions'],
    
    // Routes pour le panier
    'cart' => ['CartController', 'index'],
    'cart/add/:id' => ['CartController', 'add'],
    'cart/update' => ['CartController', 'update'],
    'cart/remove/:id' => ['CartController', 'remove'],
    'cart/clear' => ['CartController', 'clear'],
    
    // Routes pour le checkout
    'checkout' => ['CheckoutController', 'index'],
    'checkout/process' => ['CheckoutController', 'process'],
    'checkout/confirmation/:id' => ['CheckoutController', 'confirmation'],
    
    // Routes pour l'authentification
    'auth/login' => ['AuthController', 'login'],
    'auth/register' => ['AuthController', 'register'],
    'auth/processLogin' => ['AuthController', 'processLogin'],
    'auth/processRegister' => ['AuthController', 'processRegister'],
    'auth/logout' => ['AuthController', 'logout'],
    'auth/profile' => ['AuthController', 'profile'],
    'auth/updateProfile' => ['AuthController', 'updateProfile'],
    
    // Routes pour la gestion des commandes
    'order/history' => ['OrderController', 'history'],
    'order/detail/:id' => ['OrderController', 'detail'],
    'order/cancel/:id' => ['OrderController', 'cancel'],
    
    // Routes pour les factures
    'invoice' => ['InvoiceController', 'index'],
    'invoice/view/:id' => ['InvoiceController', 'view'],
    'invoice/download/:id' => ['InvoiceController', 'download'],
    
    // Route pour l'administration
    'admin' => ['AdminController', 'index'],
    'admin/orders' => ['AdminController', 'orders'],
    'admin/order/:id' => ['AdminController', 'orderDetail'],
    'admin/products' => ['AdminController', 'products'],
    'admin/product/add' => ['AdminController', 'productAdd'],
    'admin/product/edit/:id' => ['AdminController', 'productEdit'],
    'admin/products/delete/:id' => ['AdminController', 'deleteProduct'],
    'admin/orders/detail/:id' => ['AdminController', 'orderDetail'],
    'admin/orders/status/:id/:status' => ['AdminController', 'updateOrderStatus'],
    
    // Route pour la page 404
    '404' => ['ErrorController', 'notFound']
];

return $routes;