<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 * 
 * Kataraksa - Sistem Perpustakaan Digital
 * Route Configuration
 */

// ============================================
// PUBLIC ROUTES (No Auth Required)
// ============================================

// Landing Page & Public Catalog
$routes->get('/', 'Home::index');
$routes->get('/catalog', 'Home::catalog');
$routes->get('/catalog/search', 'Home::catalog');
$routes->get('/book/(:segment)/(:segment)', 'Home::book/$1/$2');

// Authentication Routes
$routes->get('/login', 'Auth::index');
$routes->get('/auth', 'Auth::index');
$routes->post('/login', 'Auth::attemptLogin');
$routes->post('/register', 'Auth::register');
$routes->get('/logout', 'Auth::logout');

// ============================================
// ADMIN ROUTES (Auth Required)
// ============================================

$routes->group('admin', ['filter' => 'auth'], function ($routes) {
    
    // Dashboard
    $routes->get('/', 'Admin\Dashboard::index');
    $routes->get('dashboard', 'Admin\Dashboard::index');

    // Categories Management
    $routes->get('categories', 'Admin\CategoryController::index');
    $routes->get('categories/create', 'Admin\CategoryController::create');
    $routes->post('categories/store', 'Admin\CategoryController::store');
    $routes->get('categories/edit/(:num)', 'Admin\CategoryController::edit/$1');
    $routes->post('categories/update/(:num)', 'Admin\CategoryController::update/$1');
    $routes->post('categories/delete/(:num)', 'Admin\CategoryController::delete/$1');

    // Books Management
    $routes->get('books', 'Admin\BookController::index');
    $routes->get('books/create', 'Admin\BookController::create');
    $routes->post('books/store', 'Admin\BookController::store');
    $routes->get('books/edit/(:num)', 'Admin\BookController::edit/$1');
    $routes->post('books/update/(:num)', 'Admin\BookController::update/$1');
    $routes->post('books/delete/(:num)', 'Admin\BookController::delete/$1');
    $routes->get('books/show/(:num)', 'Admin\BookController::show/$1');

    // Members Management
    $routes->get('members', 'Admin\MemberController::index');
    $routes->get('members/create', 'Admin\MemberController::create');
    $routes->post('members/store', 'Admin\MemberController::store');
    $routes->get('members/edit/(:num)', 'Admin\MemberController::edit/$1');
    $routes->post('members/update/(:num)', 'Admin\MemberController::update/$1');
    $routes->post('members/delete/(:num)', 'Admin\MemberController::delete/$1');
    $routes->get('members/show/(:num)', 'Admin\MemberController::show/$1');

    // Borrowings Management
    $routes->get('borrowings', 'Admin\BorrowingController::index');
    $routes->get('borrowings/create', 'Admin\BorrowingController::create');
    $routes->post('borrowings/store', 'Admin\BorrowingController::store');
    $routes->get('borrowings/show/(:num)', 'Admin\BorrowingController::show/$1');
    $routes->post('borrowings/return/(:num)', 'Admin\BorrowingController::returnBook/$1');
    $routes->post('borrowings/extend/(:num)', 'Admin\BorrowingController::extend/$1');
    $routes->post('borrowings/delete/(:num)', 'Admin\BorrowingController::delete/$1');
    $routes->get('borrowings/history', 'Admin\BorrowingController::history');

    // Users Management (Admin Only)
    $routes->group('users', ['filter' => 'role:admin'], function ($routes) {
        $routes->get('/', 'Admin\UserController::index');
        $routes->get('create', 'Admin\UserController::create');
        $routes->post('store', 'Admin\UserController::store');
        $routes->get('edit/(:num)', 'Admin\UserController::edit/$1');
        $routes->post('update/(:num)', 'Admin\UserController::update/$1');
        $routes->post('delete/(:num)', 'Admin\UserController::delete/$1');
    });
});

// ============================================
// MEMBER ROUTES (Member Auth Required)
// ============================================

$routes->group('member', ['filter' => 'member'], function($routes) {
    $routes->get('dashboard', 'Member\Dashboard::index');
    $routes->get('borrowings', 'Member\Dashboard::borrowings');
    $routes->get('catalog', 'Member\Dashboard::catalog');
    $routes->post('borrow/(:num)', 'Member\Dashboard::borrow/$1');
});
