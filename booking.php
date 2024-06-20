<?php
session_start();
require './includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Fetch details from recurring_bookings
    $stmt = $conn->prepare("SELECT rb.user_id, rb.pitch_id, rb.day_of_week, rb.start_time, rb.end_time, p.price_per_hour 
                            FROM recurring_bookings rb 
                            JOIN pitches p ON rb.pitch_id = p.pitch_id 
                            WHERE rb.recurring_booking_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($user_id, $pitch_id, $day_of_week, $start_time, $end_time, $price_per_hour);
    $stmt->fetch();
    $stmt->close();

    if ($user_id && $pitch_id && $price_per_hour) {
        // Calculate total_price
        $start = new DateTime($start_time);
        $end = new DateTime($end_time);
        $interval = $start->diff($end);
        $hours = $interval->h + ($interval->i / 60);
        $total_price = $hours * $price_per_hour;

        // Get the booking date (specific day of this week)
        $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $targetDay = array_search($day_of_week, $daysOfWeek); // Get the index of the target day

        // Calculate the date for the specified day in the current week
        $bookingDate = new DateTime();
        $startOfWeek = (clone $bookingDate)->modify('last Sunday');
        $bookingDate = (clone $startOfWeek)->modify("+$targetDay days");
        $booking_date = $bookingDate->format('Y-m-d');

        // Check if booking already exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM bookings WHERE user_id = ? AND booking_date = ?");
        $stmt->bind_param("is", $user_id, $booking_date);
        $stmt->execute();
        $stmt->bind_result($existingBookings);
        $stmt->fetch();
        $stmt->close();

        if ($existingBookings > 0) {
            // Booking already exists, redirect
            echo "<script>window.location.href = 'admin_dashboard';</script>";
        } else {
            // Insert data into bookings
            $stmt = $conn->prepare("INSERT INTO bookings (user_id, pitch_id, booking_date, start_time, end_time, total_price, booking_status, payed) VALUES (?, ?, ?, ?, ?, ?, 'confirmed', 0)");
            $stmt->bind_param("iisssd", $user_id, $pitch_id, $booking_date, $start_time, $end_time, $total_price);
            if ($stmt->execute()) {
                echo "<script>swal({title: 'Success!', text: 'Booking created successfully!', icon: 'success'}).then(() => { window.location.href = 'admin_dashboard'; });</script>";
            } else {
                echo "<script>swal({title: 'Error!', text: 'Error: " . $stmt->error . "', icon: 'error'}).then(() => { window.location.href = 'admin_dashboard'; });</script>";
            }
            $stmt->close();
        }
    } else {
        echo "<script>swal({title: 'Error!', text: 'Invalid recurring booking ID.', icon: 'error'}).then(() => { window.location.href = 'admin_dashboard'; });</script>";
    }
}
