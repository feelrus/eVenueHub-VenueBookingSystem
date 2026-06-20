<?php
session_start();

require 'src/database.php';
$database = new Database();
$conn = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $user['userid'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];

                // Redirect based on user role
                if ($user['role'] > 0) {
                    header('Location: dashboard.php');
                } else {
                    header('Location: dashboard-admin.php');
                }
                exit;
            } else {
                $login_error = "Invalid password.";
            }
        } else {
            $login_error = "No user found with that username.";
        }
    } else {
        $login_error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div style="background-image: url('images/front.jpg'); background-size:cover; background-repeat:no-repeat;">
        <?php include 'src/header.php'; ?>
    </div>

    <div class="login-container" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7)), url('images/front.jpg'); background-size:cover; background-repeat:no-repeat;">
        <!-- main container -->
        <div class="login-form">
            <h2>Login</h2>
            <div>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="text" id="username" name="username" placeholder="Username" required>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <input type="submit" name="login" value="Login">
                    <?php if (isset($login_error)): ?>
                        <p style="color: red;"><?php echo htmlspecialchars($login_error); ?></p>
                    <?php endif; ?>
                    <div class="login-footer">
                        <p><a href="register.php">Register?</a></p>
                    </div>
                </form>
            </div>
        </div>
        <!-- main container end -->
    </div>

    <footer class="footer">
        <?php include 'src/footer.php'; ?>
    </footer>
</body>
</html>
