<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require __DIR__ . '/../../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../..");
    $dotenv->load();

    //Create an instance; passing `true` enables exceptions

    $phpmail = new PHPMailer(true);

    $phpmail->isSMTP();
    $phpmail->Host = $_ENV['MAIL_HOST'];
    $phpmail->SMTPAuth = true;
    $phpmail->Port = $_ENV['MAIL_PORT'];
    $phpmail->Username = $_ENV['MAIL_USER'];
    $phpmail->Password = $_ENV['MAIL_PASS'];

    $phpmail->setFrom($_ENV['MAIL_FROM'], 'Mailer');
        
    $phpmail->isHTML(true);                                
    

?>