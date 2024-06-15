<?php
session_start();
require 'includes/conn.php';
if (isset($_SESSION['user_id'])) {
    $query = "UPDATE users SET remember_token=NULL, token_expires=NULL WHERE user_id=?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
        mysqli_stmt_execute($stmt);
    }
    session_unset();
    session_destroy();
    setcookie('remember_token', '', time() - 3600, "/"); // Expire the cookie
}
header("Location: login.php");
exit();
