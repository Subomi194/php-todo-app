<?php

    require __DIR__ . '/../../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../..");
    $dotenv->load();

    try{
        $pdo = new PDO(
            "mysql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']}",
            $_ENV['DB_USER'],
            $_ENV['DB_PASS']
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    }catch (Exception $e){
        die('Connection failed: ' . $e->getMessage());
    }

