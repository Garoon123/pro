<?php
session_start();
include('includes/conn.php'); // Include your database connection file

function hasBookedToday($userId, $conn)
{
    $today = date('Y-m-d'); // Get today's date
    $query = "SELECT COUNT(*) AS booking_count FROM bookings WHERE user_id = ? AND booking_date = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $userId, $today);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['booking_count'] > 0;
}

// Example usage
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    if (hasBookedToday($userId, $conn)) {
        echo "You have already booked a pitch today. You cannot book more than one pitch per day.";
    } else {
        $startTime = $_POST['start_time'];
        $endTime = $_POST['end_time'];
        $totalPrice = $_POST['total_price'];
        $bookingStatus = 'pending'; // Assuming default status
        $payed = 0; // Assuming default payment status
        $phoneNumber = $_POST['phone_number'];

        $bookingDate = date('Y-m-d'); // Assuming booking for today

        // Insert booking details into the database
        $query = "INSERT INTO bookings (user_id, pitch_id, booking_date, start_time, end_time, total_price, booking_status, payed, phone_number) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iissssdss", $userId, $pitchId, $bookingDate, $startTime, $endTime, $totalPrice, $bookingStatus, $payed, $phoneNumber);

        if ($stmt->execute()) {
            echo "Booking successful!";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
} else {
    echo "You need to log in to book a pitch.";
}
