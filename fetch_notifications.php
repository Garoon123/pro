<?php
require './includes/conn.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type']; // Assuming user_type is stored in the session

$sql = "SELECT message FROM notifications WHERE user_id = ? AND status = 'unread'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$notifications = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode(['count' => count($notifications), 'notifications' => $notifications, 'user_type' => $user_type]);
