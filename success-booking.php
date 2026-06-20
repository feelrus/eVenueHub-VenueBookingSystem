<?php
session_start();

if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header('Location: login.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Success</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div style="background-image: url('images/front.jpg'); background-size:cover; background-repeat:no-repeat;">
        <?php include 'src/header.php'; ?>
    </div>

    <div class="main-container">

        <div class="success-container" style="text-align: center;  margin-top: 50px;">
            <div class="success-message" style="font-size: 1.5em; color: green; margin-bottom: 20px;">
                Your request has been successfully submitted!
            </div>
            <a href="index.php" class="back-link" style="font-size: 1.2em;color: blue; text-decoration: underline;">Go
                back
                to the homepage</a>
        </div>

    </div>

    <footer class="footer">
        <?php include 'src/footer.php'; ?>
    </footer>
</body>

</html>