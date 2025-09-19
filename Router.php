<?php

declare(strict_types=1);

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

$routes = [
   '/' => 'views/register.php',
   '/login' => 'views/login.php',
   '/success' => 'views/success.php',
   '/forgot' => 'views/forgot.php',
   '/reset' => 'views/reset.php',
   '/todo' => 'views/todo.php',
   '/todo-action' => 'include/todo_actions/todo_controller.php',   
];

function routeToController($uri, $routes) {
   
   if (array_key_exists($uri, $routes)) {

      require $routes[$uri];

   } else {
      
      abort();
   }   
}

function abort($code = 404){

   http_response_code($code);
   
   require "views/{$code}.php";

   die();
   
}   