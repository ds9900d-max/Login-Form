<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'db.php';

$errorMessage = '';

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $login_quary = $conn->prepare("SELECT username, password FROM users WHERE email = ?");
    $login_quary->bind_param("s", $email);
    $login_quary->execute();
    $result = $login_quary->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = $row['username'];
        $hashedPassword = $row['password'];

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['username'] = $username;
            header("Location: welcome.php");
            exit();
        } else {
            $errorMessage = "Invalid password.";
        }
    } else {
        $errorMessage = "No user found with that email.";
    }
    $login_quary->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Make sure this filename matches your actual CSS file name -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <h2>Welcome back</h2>
            <p>Please enter your details to sign in</p>
        </div>

        <?php if ($errorMessage): ?>
            <div class="error-alert">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form class="form" id="loginForm" action="" method="POST">
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="you@example.com" required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
            </div>

            <button type="submit" name="login" class="btn-submit">Sign in</button>
        </form>

        <p class="register-link">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
    </div>

</body>
</html>