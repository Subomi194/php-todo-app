<?php

    use Doctrine\Migrations\DependencyFactory;
    use Doctrine\Migrations\Configuration\Migration\PhpFile;
    use Doctrine\DBAL\DriverManager;

    require __DIR__ . '/vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    
    $connection = DriverManager::getConnection([
        'dbname'   => $_ENV['DB_NAME'],
        'user'     => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASS'],
        'host'     => $_ENV['DB_HOST'],
        'port'     => $_ENV['DB_PORT'],
        'driver'   => 'pdo_mysql',
    ]);

    $config = new PhpFile(__DIR__ . '/migrations.php'); // Or use one of the Doctrine\Migrations\Configuration\Configuration\* loaders
    
    return DependencyFactory::fromConnection($config, new \Doctrine\Migrations\Configuration\Connection\ExistingConnection($connection));
