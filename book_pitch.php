<?php
require './includes/conn.php';
session_start();

// Function to check if user has already booked a pitch today
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

if (isset($_POST['phone_number'], $_POST['available_time'], $_POST['pitch_id'], $_POST['price_per_hour'])) {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        if (hasBookedToday($user_id, $conn)) {
            $_SESSION['message'] = [
                'type' => 'error',
                'title' => 'Booking Error',
                'text' => 'You have already booked a pitch today. You cannot book more than one pitch per day.'
            ];
            header('Location: stadiums');
            exit();
        } else {
            $pitch_id = $_POST['pitch_id'];
            $phone_number = $_POST['phone_number'];
            $selected_time = $_POST['available_time'];
            $booking_date = date('Y-m-d');
            $start_time = $selected_time . ':00';
            $end_time = date('H:i:s', strtotime('+1 hour', strtotime($start_time)));
            $total_price = $_POST['price_per_hour'];
            $booking_status = 'pending';

            $booking_query = "INSERT INTO bookings (user_id, pitch_id, booking_date, start_time, end_time, total_price, booking_status, phone_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($booking_query);
            $stmt->bind_param("iissssss", $user_id, $pitch_id, $booking_date, $start_time, $end_time, $total_price, $booking_status, $phone_number);

            if ($stmt->execute()) {
                $_SESSION['message'] = [
                    'type' => 'success',
                    'title' => 'Booking Confirmed',
                    'text' => 'Your booking has been confirmed.'
                ];
                header('Location: history');
                exit();
            } else {
                $_SESSION['message'] = [
                    'type' => 'error',
                    'title' => 'Booking Error',
                    'text' => 'Unable to confirm booking. ' . $stmt->error
                ];
                header('Location: stadiums');
                exit();
            }
        }
    } else {
        $_SESSION['message'] = [
            'type' => 'warning',
            'title' => 'Login Required',
            'text' => 'You need to log in to book a pitch.'
        ];
        header('Location: stadiums');
        exit();
    }
} else {
    $_SESSION['message'] = [
        'type' => 'error',
        'title' => 'No Data',
        'text' => 'No data found.'
    ];
    header('Location: stadiums');
    exit();
}
