<?php

    require_once __DIR__ . "/../include/config/session_config.php";

    if(!isset($_SESSION['user'])){ //redirects user to login if not logged in
            header('Location: /PHP101/login');
            exit;
        }

    $message = "";

    if (isset($_GET['type']) && $_GET['type'] === 'register') {
        $message = "Registration successful, welcome ";
    } else {
        $message = "Login successful. Welcome back ";

    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='/css/styles.css'>
    <title>Success</title>
</head>
<body>

    <div>
        <h1 class="success-message"><?php echo htmlspecialchars($message . $_SESSION['user']); ?></h1>
        <p><a href="/PHP101/todo">Go to your To-Do list</a></p>
    </div>
    
</body>
</html>