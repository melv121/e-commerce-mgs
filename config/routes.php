<?php
// Définition des routes de l'application

// Route par défaut (page d'accueil)
$routes = [
    '' => ['HomeController', 'index'],
    'home' => ['HomeController', 'index'],
    
    // Routes pour les produits
    'product/category' => ['ProductController', 'category'],
    'product/category/:category' => ['ProductController', 'category'],
    'product/category/:category/:subcategory' => ['ProductController', 'category'],
    'product/detail/:id' => ['ProductController', 'detail'],
    'product/nouveautes' => ['ProductController', 'nouveautes'],
    'product/promotions' => ['ProductController', 'promotions'],
    
    // Routes pour le panier
    'cart' => ['CartController', 'index'],
    'cart/add/:id' => ['CartController', 'add'],
    'cart/update/:id' => ['CartController', 'update'],
    'cart/remove/:id' => ['CartController', 'remove'],
    'cart/clear' => ['CartController', 'clear'],
    
    // Routes pour le checkout
    'checkout' => ['CheckoutController', 'index'],
    'checkout/process' => ['CheckoutController', 'process'],
    'checkout/confirmation/:orderId' => ['CheckoutController', 'confirmation'],
    
    // Routes pour l'authentification
    'auth' => ['AuthController', 'index'],
    'auth/login' => ['AuthController', 'login'],
    'auth/register' => ['AuthController', 'register'],
    'auth/processLogin' => ['AuthController', 'processLogin'],
    'auth/processRegister' => ['AuthController', 'processRegister'],
    'auth/logout' => ['AuthController', 'logout'],
    'auth/profile' => ['AuthController', 'profile'],
    'auth/updateProfile' => ['AuthController', 'updateProfile'],
    
    // Routes pour les commandes
    'order/history' => ['OrderController', 'history'],
    'order/detail/:id' => ['OrderController', 'detail'],
    'order/cancel/:id' => ['OrderController', 'cancel'],
    
    // Routes pour la gestion des factures
    'invoice' => ['InvoiceController', 'index'],
    'invoice/view/:id' => ['InvoiceController', 'view'],
    'invoice/download/:id' => ['InvoiceController', 'download'],
    'invoice/generate/:orderId' => ['InvoiceController', 'generate'],
    
    // Routes pour l'administration
    'admin' => ['AdminController', 'index'],
    'admin/products' => ['AdminController', 'products'],
    'admin/addProduct' => ['AdminController', 'addProduct'],
    'admin/processAddProduct' => ['AdminController', 'processAddProduct'],
    'admin/editProduct/:id' => ['AdminController', 'editProduct'],
    'admin/processEditProduct/:id' => ['AdminController', 'processEditProduct'],
    'admin/deleteProduct/:id' => ['AdminController', 'deleteProduct'],
    'admin/orders' => ['AdminController', 'orders'],
    'admin/orderDetail/:id' => ['AdminController', 'orderDetail'],
    'admin/updateOrderStatus/:id' => ['AdminController', 'updateOrderStatus'],
    'admin/users' => ['AdminController', 'users'],
    'admin/userDetail/:id' => ['AdminController', 'userDetail'],
    'admin/updateUserRole/:id' => ['AdminController', 'updateUserRole'],
    
    // Routes pour les clubs partenaires
    'partner' => ['PartnerController', 'index'],
    'partner/view/:id' => ['PartnerController', 'view'],
    'partner/dashboard' => ['PartnerController', 'dashboard'],
    
    // Route pour la page 404
    '404' => ['ErrorController', 'notFound']
];

return $routes;
