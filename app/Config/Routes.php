<?php

use App\Controllers\AuthController;
use App\Controllers\Home;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', [Home::class, 'index']);
$routes->get('403', [Home::class, 'http403']);

$routes->post('login', [AuthController::class, 'login']);

$routes->group('', ['filter' => 'tokens'], function (RouteCollection $routes) {
    $routes->get('me', [AuthController::class, 'me']);
    $routes->post('logout', [AuthController::class, 'logout']);
    
    $routes->get('superadmin-only', fn () => response()->setJSON(['message' => 'OK GRANTED!']), ['filter' => 'group:superadmin']);
    $routes->get('user-only', fn () => response()->setJSON(['message' => 'OK GRANTED!']), ['filter' => 'group:user']);
    $routes->get('permitted-permissions', fn () => response()->setJSON(['message' => 'OK GRANTED!']), ['filter' => 'permission:module.admin-feature']);
});
