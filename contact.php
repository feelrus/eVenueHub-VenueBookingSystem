<?php
session_start();
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
    <div style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7)), url('images/bg3.jpg'); background-size:cover; background-repeat:no-repeat;">
        <div class="main-container">
            <div class="divider"></div>
            <div class="white-container">
                <div class="white-form" style="text-align:center;">
                    <h1>Contact Us</h1>
                    <p>Email us at evenuehub@gmail.com</p>
                    <p>Phone: +60192483950</p>
                    <h2>visit us at</h2>
                    <p><a href="facebook.com">Facebook</a></p>
                    <p>
                        <a href="twitter.com">Twitter</a>
                    </p>
                    <p><a href="instagram.com">Instagram</a></p>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <?php include 'src/footer.php'; ?>
    </footer>
</body>

</html>