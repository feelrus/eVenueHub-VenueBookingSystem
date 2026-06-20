<?php
session_start();
?>

<?php

require 'src/database.php';
$database = new Database();
$conn = $database->getConnection();

$email_valid = true;
$username_exists = false;
$password_too_short = false;
$passwords_match = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_valid = true;
    $username_exists = false;
    $password_too_short = false;
    $passwords_match = true;

    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_valid = false;
    }

    if (strlen($password) < 8) {
        $password_too_short = true;
    }

    if ($password !== $confirmpassword) {
        $passwords_match = false;
    }

    if ($email_valid && $passwords_match && !$password_too_short) {
        $checkQuery = "SELECT * FROM users WHERE username = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("s", $username);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $username_exists = true;
        } else {
            // set role to 1 for new users and 0 for superadmin
            $role = 2;

            $insertQuery = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt->bind_param("sssi", $username, $email, $hashed_password, $role);

            if ($stmt->execute()) {
                header('Location: login.php');
                exit;
            } else {
                $email_valid = false;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Registration</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div style="background-image: url('images/front.jpg'); background-size:cover; background-repeat:no-repeat;">
        <?php include 'src/header.php'; ?>
    </div>

    <div class="login-container"
        style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7)), url('images/front.jpg'); background-size:cover; background-repeat:no-repeat;">
        <!-- main container -->

        <div class="login-form">
            <h2>Registration</h2>
            <div>

                <form id="registerForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="text" id="email" name="email" placeholder="Email" required>
                    <?php if (!$email_valid): ?>
                        <span style="color: red;">Invalid email format or already existed</span>
                    <?php endif; ?>

                    <input type="text" id="username" name="username" placeholder="Username" required>
                    <?php if ($username_exists): ?>
                        <span style="color: red;">Username already exists</span>
                    <?php endif; ?>

                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <?php if ($password_too_short): ?>
                        <span style="color: red;">Password should be at least 8 characters long</span>
                    <?php endif; ?>

                    <input type="password" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password"
                        required>

                    <?php if (!$passwords_match): ?>
                        <span style="color: red;">Passwords do not match</span>
                    <?php endif; ?>

                    <input type="submit" name="register" value="Register">

                    <div class="form-footer">
                        <p><a href="login.php">Already registered?</a></p>
                    </div>

                </form>
            </div>

        </div>
    </div>

    <footer class="footer">
        <?php include 'src/footer.php'; ?>
    </footer>
</body>

</html>