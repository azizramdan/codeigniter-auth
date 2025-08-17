<?php

use App\Controllers\AuthController;
use App\Controllers\Home;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', [Home::class, 'index']);

$routes->post('auth/login', [AuthController::class, 'login']);

$routes->group('', ['filter' => 'tokens'], function (RouteCollection $routes) {
    $routes->group('auth', function (RouteCollection $routes) {
        $routes->get('me', [AuthController::class, 'me']);
        $routes->post('change-password', [AuthController::class, 'changePassword']);
        $routes->post('logout', [AuthController::class, 'logout']);
    });
    
    $routes->get('superadmin-only', [Home::class, 'ok'], ['filter' => 'group:superadmin']);
    $routes->get('user-only', [Home::class, 'ok'], ['filter' => 'group:user']);
    $routes->get('permitted-permissions', [Home::class, 'ok'], ['filter' => 'permission:module.admin-feature']);
});
