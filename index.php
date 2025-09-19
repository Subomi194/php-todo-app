<?php
declare(strict_types=1);

require_once __DIR__ . '/Router.php';

$baseDir = '/PHP101';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($baseDir && strpos($uri, $baseDir) === 0) {
    $uri = substr($uri, strlen($baseDir));
}

if ($uri === '' || $uri === false) {
    $uri = '/';
}

routeToController($uri, $routes);
