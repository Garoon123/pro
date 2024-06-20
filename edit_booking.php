<?php
require './includes/conn.php';
require './includes/header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST['booking_id'];
    $pitch_id = $_POST['pitch_id'];

    // Fetch current booking details
    $query = "SELECT * FROM bookings WHERE booking_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();

    if (!$booking) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Booking not found.'
    });
            });
        </script>";
        echo "<script>window.location.href = 'admin_dashboard';
</script>";
        exit();
    }

    // Handle update logic
    if (isset($_POST['update_booking'])) {
        $phone_number = $_POST['phone_number'];
        $start_time = $_POST['start_time'];
        $end_time = date("H:i", strtotime($start_time . "+1 hour"));
        $booking_date = $_POST['booking_date'];

        $update_query = "UPDATE bookings SET phone_number = ?, start_time = ?, end_time = ?, booking_date = ? WHERE booking_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ssssi", $phone_number, $start_time, $end_time, $booking_date, $booking_id);

        if ($update_stmt->execute()) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: 'Booking information has been updated.'
                    });
                });
            </script>";
            echo "<script>window.location.href = 'admin_dashboard';
</script>";
            exit();
        } else {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while updating the booking.'
                    }).then(() => {
                        window.location.href = 'admin_dashboard.php';
                    });
                });
            </script>";
        }
        $update_stmt->close();
        $conn->close();
        exit();
    }
}
?>

<div class="container mt-5">
    <h2 class="text-center">Edit Booking</h2>
    <form method="POST" action="edit_booking.php">
        <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking['booking_id']); ?>">
        <input type="hidden" name="pitch_id" value="<?php echo htmlspecialchars($booking['pitch_id']); ?>">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="mb-3">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($booking['phone_number']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="start_time" class="form-label">Start Time</label>
                    <input type="time" class="form-control" id="start_time" name="start_time" value="<?php echo htmlspecialchars($booking['start_time']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="booking_date" class="form-label">Booking Date</label>
                    <input type="date" class="form-control" id="booking_date" name="booking_date" value="<?php echo htmlspecialchars($booking['booking_date']); ?>" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 offset-md-3 d-flex justify-content-center">
                <button type="submit" class="btn btn-primary" name="update_booking">Update Booking</button>
            </div>
        </div>
    </form>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</body>

</html>

<?php require './includes/footer.php'; ?>