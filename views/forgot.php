<?php
    require_once __DIR__ . "/../include/config/session_config.php";

    $alert = "";
    $error = "";


    // Check if the form has been submitted
    if (isset($_POST["reset-request"])){
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid email address.";
        } else {

            $token = bin2hex(random_bytes(16));
            $token_hash = hash("sha256", $token);
            $expire = date("Y-m-d H:i:s", time() + 60 * 30);

            try{
                require __DIR__ . '/../include/config/database.php';

                //Delete previous tokens//
                $stmt = $pdo->prepare ("DELETE FROM pwdReset WHERE email = :email");

                $stmt->bindParam(':email', $email);

                $stmt->execute();
            

                //insert new token
                $stmt = $pdo->prepare ("INSERT INTO pwdReset (email, token, expires_at) 
                        SELECT :email, :token, :expires_at
                        WHERE EXISTS (SELECT 1 FROM users WHERE email = :email)");

                
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':token', $token_hash);
                $stmt->bindParam(':expires_at', $expire);

                $stmt->execute();

                // if > 0, means INSERT worked 
                if ($stmt->rowCount() > 0) {

                    require_once __DIR__ . "/../include/config/mail.php";

                    
                    $phpmail->addAddress($email); 
                    
                    $phpmail->Subject = 'Password Reset';
                    $phpmail->Body    = "<h2>Password Reset Request</h2>
                        <p>Click the link below to reset your password:</p>
                        <a href='http://localhost/PHP101/reset?token=$token'>here</a>";

                    try{
                        $phpmail->send();
        
                        $alert = "Check your email for reset instructions.";
                    }catch (Exception $e) {
                    $error = "Message could not be sent. Mailer Error: {$phpmail->ErrorInfo}";

                    }
                }
                
                $alert = "If an account with that email exists, you will receive a password reset link shortly.";

                $pdo = null;
                $stmt = null;     
       
            }catch (Exception $e) {
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
    <link rel='stylesheet' href='/css/styles.css'>
    <title>Forgot Password</title>
</head>
<body>
   
    <div class="reg-container">

        <h1 id="login-title">Forgot Password?</h1>

        <p>An e-mail will be sent to you with instructions how to reset your password.</p>

        <form action="" method="POST">

            <label>Email </label><br>
            <input type="email" name="email" placeholder="Enter your email address" required/><br>

            <button type="submit" name="reset-request">Send</button>

        </form>

        <?php if (!empty($alert)): ?>
            <div class="request-success"><?php echo htmlspecialchars($alert); ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="reset-fail"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
    </div>

</body>
</html> 