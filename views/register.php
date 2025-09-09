
<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once __DIR__ . "/../include/config/session_config.php";

    $error = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $user = filter_input(INPUT_POST, "user", FILTER_SANITIZE_SPECIAL_CHARS);
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $phone = filter_input(INPUT_POST, "phone", FILTER_SANITIZE_NUMBER_INT);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
        $con_pwd = filter_input(INPUT_POST, "con_pwd", FILTER_SANITIZE_SPECIAL_CHARS);

            
        if (empty($user) || empty($username) || empty($email) || empty($phone) || empty($password) || empty($con_pwd)){
            $error= "Please fill in all fields!";
        } elseif ($password != $con_pwd){
            $error= "Passwords do not match. Please try again!";
        } else{

            try{
                require __DIR__ . '/../include/config/database.php';

                $options = ['cost' => 10];
                $passwordHash = password_hash($password, PASSWORD_BCRYPT, $options);

                //check if username or email already exists
                $stmt = $pdo->prepare ("SELECT * FROM users WHERE username = :username OR email = :email ");
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);

                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $error = "Username or email already in use.";
                } 

                //insert new user
                $stmt = $pdo->prepare ("INSERT INTO users (name, username, email, phone, password) 
                VALUES (:user, :username, :email, :phone, :password)");
                
                $stmt->bindParam(':user', $user);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':password', $passwordHash);

                $stmt->execute();

                $_SESSION['user'] = $username;
                $_SESSION['user_id'] = $pdo->lastInsertId();

                session_regenerate_id();

                $pdo = null;
                $stmt = null;

                header("Location: /PHP101/success?type=register");
                exit();


            }catch (Exception $e){
                if ($e->getCode() == 23000) { 
                    // 23000 = Integrity constraint violation (duplicate entry for UNIQUE column)
                    $error = "This email is already registered. Please use another.";
                } else {
                    $error = "Error: " . $e->getMessage();
                }    
            }   
                    
        }   

    }   

    

?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='/PHP101/css/styles.css'>
    <title>Register</title>
</head>
<body>
    <div class="reg-container">

        <?php include 'header.html'; ?>
        
        <?php if (!empty($error)): ?>
            <div class="reset-fail"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            
            <label>Name </label><br>
            <input type="text" name="user" required/><br>

            <label>Username </label><br>
            <input type="text" name="username" required/><br>

            <label>Email </label><br>
            <input type="email" name="email" required/><br>

            <label>Phone </label><br>
            <input type="text" name="phone" required/><br>

            <label>Password </label><br>
            <input type="password" name="password" required/><br>

            <label>Confirm Password </label><br>
            <input type="password" name="con_pwd" required/><br>        


            
            <button type="submit" id="reg-button">Sign up</button>

        </form>

        <?php
            include 'footer.html';
        ?>

    </div>
</body>
</html>







    
 