<?php
require './includes/conn.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST['booking_id'];
    $number = $_POST['number'];
    $amount = $_POST['amount'];
    $time_slot = $_POST['time_slot'];
    $date = $_POST['date'];
    $end_time = date("H:i", strtotime($time_slot . "+1 hour"));
    $pitch_id = $_POST['pitch_id'];
    $status = 'confirmed';
    $query = "INSERT INTO bookings ( phone_number, total_price, start_time,end_time, pitch_id, booking_date, booking_status) VALUES ( ?, ?,?,?,?,?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sdssiss",  $number, $amount,  $time_slot, $end_time, $pitch_id, $date, $status);
    if ($stmt->execute()) {
        echo "Booking saved successfully.";
    } else {
        echo "Error saving booking: " . $conn->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
