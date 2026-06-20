<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    if (isset($_SESSION['user_role'])) {
        if ($_SESSION['user_role'] > 0) {
            header('Location: dashboard.php');
        } else {
            header('Location: dashboard-admin.php');
        }
        exit;
    } else {
        header('Location: login.php');
        exit;
    }
} else {
    header('Location: login.php');
    exit;
}
?>
