<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


$routes->group('api', function ($routes) {
    $routes->get('contacts', 'ContactsController::index');
    $routes->post('contacts', 'ContactsController::create');
    $routes->get('contacts/(:num)', 'ContactsController::show/$1');
    $routes->put('contacts/(:num)', 'ContactsController::update/$1');
    $routes->delete('contacts/(:num)', 'ContactsController::delete/$1');
});
