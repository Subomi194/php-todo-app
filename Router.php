<?php
   declare(strict_types=1);

   class Router
   {
      private array $routes = [];
      private string $basedir;
      
      public function __construct(string $baseDir = '')
      {
         $this->baseDir = rtrim($baseDir, '/');
      }

      public function add(string $path, Closure $handler): void
      {
         $this->routes[$path] = $handler; 
      }

      public function dispatch(string $path): void 
      {
         
         if ($this->baseDir && strpos($path, $this->baseDir) === 0) {
         $path = substr($path, strlen($this->baseDir));
         }

         
         if ($path === '' || $path === false) {
               $path = '/';
         }

         if (array_key_exists($path, $this->routes)) { //use function to see if path applied match any in the routes property

            call_user_func($this->routes[$path]);
         
         }else {

            http_response_code(404);
            echo "404 - Page not found";
         }
      }
   }
