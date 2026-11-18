<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'db.php';  

$errorMessage = '';

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $checkUsername = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $checkUsername->bind_param("s", $username);
    $checkUsername->execute();
    $checkUsername->store_result();
    
    if ($checkUsername->num_rows > 0) {
        $errorMessage = "Username already exists.";
    }
    $checkUsername->close();

    if (empty($errorMessage)) {
        $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $checkEmail->bind_param("s", $email);
        $checkEmail->execute();
        $checkEmail->store_result();
        
        if ($checkEmail->num_rows > 0) {
            $errorMessage = "Email already exists.";
        }
        $checkEmail->close();
    }

    if (empty($errorMessage)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insertStat = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $insertStat->bind_param("sss", $username, $email, $hashedPassword);
        
        if ($insertStat->execute()) {
            echo "Registration successful. <a href='login.php'>Login here</a>";
            exit();
        } else {
            $errorMessage = "Error: " . $conn->error;
        }
        $insertStat->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <h2>Create an account</h2>
            <p>Please enter your details to sign up</p>
        </div>

        <?php if (!empty($errorMessage)): ?>
            <div class="error-alert">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form class="form" id="registerForm" action="" method="POST">
            <div class="form-group">
                <label class="form-label" for="username">Username</label>
                <input type="text" id="username" class="form-input" name="username" placeholder="johndoe" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" class="form-input" name="email" placeholder="you@example.com" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" class="form-input" name="password" placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="confirmPassword">Confirm Password</label>
                <input type="password" id="confirmPassword" class="form-input" name="confirmPassword" placeholder="••••••••" required>
            </div>

            <!-- JavaScript validation error message container styled to match error-alert -->
            <div id="js-error-message" class="error-alert" style="display: none;"></div>

            <button type="submit" class="btn-submit" name="register">Create account</button>
        </form>

        <p class="register-link">
            Already have an account? <a href="login.php">Sign in</a>
        </p>
    </div>

    <script>
        document.getElementById("registerForm").addEventListener("submit", function(event) {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirmPassword").value;
            const jsErrorMessage = document.getElementById("js-error-message");
            const strongPasswordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

            jsErrorMessage.innerHTML = "";
            jsErrorMessage.style.display = "none";

            if (!strongPasswordRegex.test(password)) {
                event.preventDefault();
                jsErrorMessage.innerHTML = "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.";
                jsErrorMessage.style.display = "block";
                return;
            }
            if (password !== confirmPassword) {
                event.preventDefault();
                jsErrorMessage.innerHTML = "Passwords do not match.";
                jsErrorMessage.style.display = "block";
            }
        });
    </script>
</body>
</html>