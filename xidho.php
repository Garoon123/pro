<?php
session_start();
require './includes/conn.php';
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize the input data
    $pitch_id = $_POST['pitch_id'];
    $day_of_week = $_POST['day_of_week'];
    $original_time_slot = $_POST['original_time_slot'];
    $phone_number = $_POST['phone_number'];

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO recurring_bookings (pitch_id, user_id, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?, ?)");
    // Set parameters and execute the statement
    $user_id = $_SESSION['user_id']; // Assuming a static user_id for now
    $start_time = $original_time_slot; // Assuming start and end time are the same as the original time slot
    $end_time = date('H:i', strtotime($start_time . '+1 hour'));
    $stmt->bind_param("iisss", $pitch_id, $user_id, $day_of_week, $start_time, $end_time);

    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
}
