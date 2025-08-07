<?php

use App\Controllers\AuthController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->post('login', [AuthController::class, 'login']);

$routes->group('', ['filter' => 'tokens'], function ($routes) {
    $routes->get('me', [AuthController::class, 'me']);
    $routes->post('logout', [AuthController::class, 'logout']);
});
