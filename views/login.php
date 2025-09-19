
<?php

    require_once __DIR__ . "/../include/config/session_config.php";

    $error ="";

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
    
        if (empty($username) || empty($password)){
            $error= "Please fill in all fields!";

        } else {
            try{
                require __DIR__ . '/../include/config/database.php';
    
                $stmt = $pdo->prepare ("SELECT * FROM users WHERE username = :username ");
                
                $stmt->bindParam(':username', $username);
    
                $stmt->execute();

                $results = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($results && password_verify($password, $results['password'])){

                    $_SESSION['user'] = $results['username']; //user stored in session
                    $_SESSION['user_id'] = $results['id']; //user id stored in session

                    regenerate_session_id(); //after login

                    header("Location: /PHP101/success");
                    exit();

                }else {
                    $error = "Invalid username or password";
                }
                $pdo = null;
                $stmt = null;

            }catch (Exception $e){
                die("Error: " . $e->getMessage());
            }
        }
    }    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='/css/styles.css'>
    <title>Login</title>
</head>
<body>

    <div class="reg-container">

        <?php if (!empty($error)): ?>
            <div class="reset-fail"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['reset']) && $_GET['reset'] === 'success'): ?>
            <div class="request-success"> 
                Password reset successful. Please log in with your new password.
            </div>
        <?php endif; ?>

        <h1 id="login-title">Login</h1>

        <form action="" method="POST">

            <label>Username </label><br>
            <input type="text" name="username" required/><br>

            <label>Password </label><br>
            <input type="password" name="password" required/><br>
            
            <a href="/PHP101/forgot" id = "forgot-btn">Forget Password?</a><br>
            

            <button type="submit">Login</button>

            <div class="login-link">

                <p>Don't have an account?<a href="/PHP101/">Sign up</a></p>

            </div>

        </form>
    </div>

</body>
</html>