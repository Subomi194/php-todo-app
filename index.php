<?php

    declare(strict_types=1);

    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    require_once __DIR__ . '/Router.php';

    $router = new Router();

    // Add routes
    $router->add('/', function () {
        require __DIR__ . '/views/register.php';
    });

    $router->add('/login', function () {
        require __DIR__ . '/views/login.php';
    });

    $router->add('/success', function () {
        require __DIR__ . '/views/success.php';
    });

    $router->add('/forgot', function () {
        require __DIR__ . '/views/forgot.php';
    });

    $router->add('/reset', function () {
        require __DIR__ . '/views/reset.php';
    });

    $router->add('/todo', function () {
        require __DIR__ . '/views/todo.php';
    });

    $router->add('/todo-action', function () {
        require __DIR__ . '/include/todo_actions/todo_controller.php';
    });
    
    // Dispatch
    $router->dispatch($path);
