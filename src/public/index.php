<?php

require_once __DIR__ . '/../Router.php';

$router = new Router();

$router->get('',                ['PostController', 'index']);
$router->get('post/{id}',       ['PostController', 'show']);
$router->get('post/create',     ['PostController', 'create']);
$router->post('post/create',    ['PostController', 'store']);

$router->get('register',        ['AuthController', 'register']);
$router->post('register',       ['AuthController', 'registerStore']);
$router->get('login',           ['AuthController', 'login']);
$router->post('login',          ['AuthController', 'loginStore']);
$router->get('logout',          ['AuthController', 'logout']);

$router->dispatch();