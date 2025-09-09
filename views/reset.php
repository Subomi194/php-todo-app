<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once __DIR__ . "/../include/config/session_config.php";

    $error = "";
    $alert = "";

    if (isset($_POST["change-request"])){

        $new_password = $_POST["new_password"];
        $conf_new_password = $_POST["conf_new_password"];

        if (empty($new_password) || empty($conf_new_password)){
            $error= "Please fill in all fields!";

        }elseif ($new_password != $conf_new_password){
            $error= "Passwords do not match. Please try again!";
            
        }else{

            $token = $_POST["token"];
            $token_hash = hash("sha256", $token);

            try{
                require __DIR__ . '/../include/config/database.php';

                //check if token exists and is still valid
                $stmt = $pdo->prepare ("SELECT email FROM pwdReset WHERE token = :token AND expires_at > NOW()");
                            
                $stmt->bindParam(':token', $token_hash);
                $stmt->execute();

                $results = $stmt->fetch(PDO::FETCH_ASSOC);


                if ($results) {
                    // Hash new password
                    $passwordHash = password_hash($new_password, PASSWORD_DEFAULT);

                    // Update user password
                    $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE email = :email");

                    $stmt->bindParam(":password", $passwordHash);
                    $stmt->bindParam(":email", $results["email"]);

                    $stmt->execute();

                    // Delete reset token from database
                    $stmt = $pdo->prepare("DELETE FROM pwdReset WHERE email = :email");
                    $stmt->bindParam(":email", $results["email"]);
                    $stmt->execute();


                    header("Location: /PHP101/login?reset=success");
                    
                } else {
                    $error ="Invalid or expired token";
                }

            }catch (Exception $e){
                $error = "Error: " . $e->getMessage();     
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
    <title>Reset Password</title>
</head>
<body>

    <div class="reg-container">

        <h1 id="login-title">Reset Password</h1>

        <?php if (!empty($error)): ?>
            <div class="reset-fail"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        
        <form action="" method="POST">

            <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">

            <label>New password </label><br>
            <input type="password" name="new_password" required/><br>

            <label>Confirm password </label><br>
            <input type="password" name="conf_new_password" required/><br>

            <button type="submit" name="change-request">Reset password</button>

        </form>
        
    </div>

</body>
</html>