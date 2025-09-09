<?php
   declare(strict_types=1);

   class Router
   {
      private array $routes = []; 

      public function add(string $path, Closure $handler): void
      {
         $this->routes[$path] = $handler; 
      }

      public function dispatch(string $path): void 
      {
         $baseDir = '/PHP101'; 
         if (strpos($path, $baseDir) === 0) {

            $path = substr($path, strlen($baseDir));
         }
         
         if ($path === '' || $path === false) {
            $path = '/';
         }

         if (array_key_exists($path, $this->routes)) { //use function to see if path applied match any in the routes property

            $handler = $this->routes[$path];//get handler from array using path as index

            call_user_func($handler);
         
         }else {

            http_response_code(404);
            echo "404 - Page not found";
         }
      }
   }
