<?php
session_start();
require 'includes/conn.php';

function getUnreadNotifications($userId, $conn)
{
    $query = "SELECT * FROM notifications WHERE user_id = ? AND status = 'unread'";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $notifications = [];
        while ($notification = mysqli_fetch_assoc($result)) {
            $notifications[] = $notification;
        }
        return $notifications;
    } else {
        return [];
    }
}

$error = '';
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $notifications = getUnreadNotifications($userId, $conn);
    foreach ($notifications as $notification) {
        $query = "UPDATE notifications SET status = 'read' WHERE notification_id = ?";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $query)) {
            mysqli_stmt_bind_param($stmt, "i", $notification['notification_id']);
            mysqli_stmt_execute($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Garoon Kireeya</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>


    <?php if (isset($_SESSION['user_id'])) : ?>
        <div class="notifications">
            <?php foreach ($notifications as $notification) : ?>
                <div class="notification">
                    <?php echo htmlspecialchars($notification['message']); ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php require 'includes/footer.php'; ?>
</body>

</html>