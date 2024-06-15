<?php
require './includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'];
    $amount = $_POST['amount'];

    // Update the payed amount in the database
    $sql = "UPDATE bookings SET payed = payed + ? WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("di", $amount, $booking_id);

    if ($stmt->execute()) {
        header('Location: admin_dashboard.php?success=1');
    } else {
        header('Location: admin_dashboard.php?error=1');
    }

    $stmt->close();
    $conn->close();
}
