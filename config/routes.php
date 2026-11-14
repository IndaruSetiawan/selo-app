<?php
use App\Controllers\Admin;
use App\Controllers\Customer;

/** @var App\Core\Router $router */

// Customer
$router->get('/',   [Customer\MenuController::class, 'index']);
$router->get('/payment',   [Customer\PaymentController::class, 'index']);
$router->post('/order', [Customer\OrderController::class, 'store']);
$router->post('/order/pay', [Customer\OrderController::class, 'pay']);

// Auth
$router->get('/admin/login',   [Admin\AuthController::class, 'index']);
$router->post('/admin/login', [Admin\AuthController::class, 'login']);
$router->post('/admin/register', [Admin\AuthController::class, 'register']);
$router->get('/admin/google',    [Admin\AuthController::class, 'googleRedirect']);
$router->get('/admin/google/callback',     [Admin\AuthController::class, 'googleCallback']);
$router->post('/admin/logout', [Admin\AuthController::class, 'logout']);

// Admin
$router->get('/admin',   [Admin\DashboardController::class, 'index']);
$router->get('/admin/orders',   [Admin\OrderController::class, 'index']);
$router->get('/admin/store',   [Admin\StoreController::class, 'index']);
$router->get('/admin/analytics',   [Admin\AnalyticsController::class, 'index']);

// Order
$router->get('/admin/orders', [Admin\OrderController::class, 'index']);
$router->post('/admin/orders/update', [Admin\OrderController::class, 'update']);
$router->get('/order/status', [Customer\OrderController::class, 'status']);
$router->post('/admin/orders/pay', [Admin\OrderController::class, 'pay']);
$router->post('/admin/orders/delete', [Admin\OrderController::class, 'delete']);

// store
$router->post('/admin/store/save', [Admin\StoreController::class, 'save']);
$router->post('/admin/store/delete', [Admin\StoreController::class, 'delete']);


// Team
$router->get('/admin/team',   [Admin\TeamController::class, 'index']);
$router->post('/admin/team/approve', [Admin\TeamController::class, 'approve']);
$router->post('/admin/team/reject', [Admin\TeamController::class, 'reject']);
$router->post('/admin/team/role', [Admin\TeamController::class, 'changeRole']);
