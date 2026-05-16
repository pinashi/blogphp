<?php

declare(strict_types=1);

require_once __DIR__ . '/../Router.php';

/**
 * Application entry point.
 * Initializes router and registers all routes.
 */

session_start();
$router = new Router();

$router->get('',                    ['PostController', 'index']);
$router->get('post/{id}',           ['PostController', 'show']);
$router->get('post/create',         ['PostController', 'create']);
$router->post('post/create',        ['PostController', 'store']);
$router->get('post/{id}/edit',      ['PostController', 'edit']);
$router->post('post/{id}/edit',     ['PostController', 'update']);
$router->post('post/{id}/delete',   ['PostController', 'destroy']);

$router->get('register',            ['AuthController', 'register']);
$router->post('register',           ['AuthController', 'registerStore']);
$router->get('login',               ['AuthController', 'login']);
$router->post('login',              ['AuthController', 'loginStore']);
$router->get('logout',              ['AuthController', 'logout']);

$router->post('post/{id}/comment',  ['CommentController', 'store']);

$router->dispatch();