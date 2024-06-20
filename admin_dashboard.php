<?php
session_start();
require './includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$sql_pitch = "SELECT pitch_id,price_per_hour FROM pitches WHERE user_id = ?";
$stmt_pitch = $conn->prepare($sql_pitch);
$stmt_pitch->bind_param("i", $user_id);
$stmt_pitch->execute();
$result_pitch = $stmt_pitch->get_result();

if ($result_pitch->num_rows > 0) {
    $row_pitch = $result_pitch->fetch_assoc();
    $pitch_id = $row_pitch['pitch_id'];
    $amount12 = $row_pitch['price_per_hour'];
    $_SESSION['pitchId'] = $row_pitch['pitch_id'];
} else {
    die("No pitch found for the user.");
}

// Fetch today's bookings
$today = date("Y-m-d");
$sql_today = "
    SELECT b.booking_id, b.total_price, b.payed, 
           COALESCE(u.full_name, b.phone_number) AS display_name, 
           b.phone_number, b.start_time, b.end_time, b.booking_status
    FROM bookings b
    LEFT JOIN users u ON b.user_id = u.user_id
    WHERE b.booking_date = ? AND b.pitch_id = ?
";
$stmt_today = $conn->prepare($sql_today);
$stmt_today->bind_param("si", $today, $pitch_id);
$stmt_today->execute();
$result_today = $stmt_today->get_result();

// Fetch tomorrow's bookings
$tomorrow = date("Y-m-d", strtotime("+1 day"));
$sql_tomorrow = "
    SELECT b.booking_id, COALESCE(u.full_name, b.phone_number) AS display_name, 
           b.phone_number, b.start_time, b.end_time, b.booking_status, b.total_price, b.payed
    FROM bookings b
    LEFT JOIN users u ON b.user_id = u.user_id
    WHERE b.booking_date = ? AND b.pitch_id = ?
";
$stmt_tomorrow = $conn->prepare($sql_tomorrow);
$stmt_tomorrow->bind_param("si", $tomorrow, $pitch_id);
$stmt_tomorrow->execute();
$result_tomorrow = $stmt_tomorrow->get_result();

// Fetch available time slots for today
$query = "SELECT * FROM bookings WHERE pitch_id = ? AND booking_date = CURDATE()";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $pitch_id);
$stmt->execute();
$result_bookings = $stmt->get_result();
$time_slots = ['16:30', '19:00', '20:00', '21:00', '22:00', '23:00']; // Adjust this array as needed

$booked_slots = [];
while ($row = $result_bookings->fetch_assoc()) {
    $booked_slots[] = substr($row['start_time'], 0, 5); // Get only HH:MM
}

// Fetch recurring bookings for today
$recurring_query = "SELECT * FROM recurring_bookings WHERE pitch_id = ? AND day_of_week = DAYNAME(CURDATE())";
$recurring_stmt = $conn->prepare($recurring_query);
$recurring_stmt->bind_param("i", $pitch_id);
$recurring_stmt->execute();
$result_recurring = $recurring_stmt->get_result();
while ($row = $result_recurring->fetch_assoc()) {
    $booked_slots[] = substr($row['start_time'], 0, 5); // Get only HH:MM
}

$available_slots = array_diff($time_slots, $booked_slots);

// The variables $result_today, $result_tomorrow, and $available_slots are now available for use in your HTML
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Garoon - Kireeye</title>
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
    <!-- Include SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Include SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* CSS style for the floating button */
        .floating-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #FFA500;
            /* Set your desired background color */
            color: #fff;
            /* Set your desired text color */
            border: none;
            border-radius: 50%;
            /* Make it round */
            width: 60px;
            /* Set your desired width */
            height: 60px;
            /* Set your desired height */
            font-size: 24px;
            /* Set your desired font size */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            /* Add a subtle shadow */
            z-index: 1000;
            /* Ensure it stays on top of other content */
            cursor: pointer;
            /* Show pointer cursor on hover */
            transition: background-color 0.3s ease;
            /* Add a smooth transition for background color */
        }

        /* CSS style for the button icon */
        .floating-button i {
            vertical-align: middle;
        }

        /* Change background color on hover */
        .floating-button:hover {
            background-color: #FF6347;
            /* Set your desired hover background color */
        }

        /* Media query for small screens */
        @media (max-width: 768px) {
            .floating-button {
                bottom: 10px;
                /* Adjust bottom spacing for small screens */
                right: 10px;
                /* Adjust right spacing for small screens */
                width: 80px;
                /* Adjust width for small screens */
                height: 90px;
                /* Adjust height for small screens */
                font-size: 20px;
                /* Adjust font size for small screens */
            }
        }

        .booking-card {
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.2s;
        }

        .booking-card:hover {
            transform: scale(1.05);
        }

        .booking-card img {
            border-bottom: 1px solid #ddd;
        }

        .booking-card .card-body {
            padding: 20px;
        }

        .booking-card .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #007bff;
        }

        .booking-card .card-text {
            margin: 0.5rem 0;
        }

        .booking-card .card-text strong {
            color: #333;
        }
    </style>
    <script>
        if ('Notification' in window && navigator.serviceWorker) {
            Notification.requestPermission().then(permission => {
                if (permission === "granted") {
                    navigator.serviceWorker.register('/service-worker.js');
                }
            });
        }

        const conn = new WebSocket('ws://localhost:8080');

        conn.onopen = function(e) {
            console.log("Connection established!");
        };

        conn.onmessage = function(e) {
            const data = JSON.parse(e.data);
            if (Notification.permission === "granted") {
                new Notification("Pitch Booking", {
                    body: data.message,
                    icon: 'Logo Centered.png' // Replace with your icon path
                });
            }
        };
    </script>
</head>

<body>

    <div class="container-fluid fixed-top">
        <div class="container px-0">
            <nav class="navbar navbar-light bg-white navbar-expand-xl">
                <a href="index.php" class="navbar-brand">
                    <h1 class="text-primary display-6">Garoon Kireeye</h1>
                </a>
                <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars text-primary"></span>
                </button>
                <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                    <div class="navbar-nav mx-auto">
                        <a href="admin_dashboard" class="nav-item nav-link active">Home</a>
                        <a href="iixidh" class="nav-item nav-link">Iixidh</a>
                        <a href="customers" class="nav-item nav-link">Past Booking</a>
                        <a href="reports" class="nav-item nav-link">Reports</a>
                        <a href="admin_contact" class="nav-item nav-link">Contact</a>
                    </div>
                    <div class="d-flex m-3 me-0">
                        <?php
                        if (isset($_SESSION['user_id'])) {
                            // User is logged in, display logout button
                        ?><a href="logout.php" class="my-auto">
                                <i class="fas fa-sign-out-alt fa-2x"></i>
                            </a> <?php
                                } else {
                                    // User is not logged in, display login button
                                    ?> <a href="login.php" class="my-auto">
                                <i class="fas fa-sign-in-alt fa-2x"></i>
                            </a>
                        <?php  }
                        ?>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <div class="container-fluid py-5">
        <div class="container py-5">
            <nav>
                <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                    <button class="nav-link active border-white border-bottom-0" type="button" role="tab" id="nav-today-tab" data-bs-toggle="tab" data-bs-target="#nav-today" aria-controls="nav-today" aria-selected="true">Today's Bookings</button>
                    <button class="nav-link border-white border-bottom-0" type="button" role="tab" id="nav-tomorrow-tab" data-bs-toggle="tab" data-bs-target="#nav-tomorrow" aria-controls="nav-tomorrow" aria-selected="false">Tomorrow's Bookings</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-today" role="tabpanel" aria-labelledby="nav-today-tab">
                    <div class="row">
                        <?php
                        if ($result_today->num_rows > 0) {
                            while ($row = $result_today->fetch_assoc()) {
                        ?>
                                <div class='col-md-6 col-lg-6'>
                                    <div class='card booking-card mb-4 shadow-sm'>
                                        <div class='card-body'>
                                            <h5 class='card-title'><i class='bi bi-person-circle'></i> <?php echo htmlspecialchars($row["display_name"]); ?></i></h5>
                                            <h5 class='card-text'><i class='bi bi-telephone'></i> <strong>Phone Number:</strong> <?php echo htmlspecialchars($row["phone_number"]); ?></h5>
                                            <h5 class='card-text'><i class='bi bi-clock'></i> <strong>Time:</strong> <?php echo htmlspecialchars($row["start_time"]) . ' - ' . htmlspecialchars($row["end_time"]); ?></h5>
                                            <h5 class='card-text'><i class='bi bi-clock-fill'></i> <strong>Lacagta:</strong> <?php echo $row["payed"] - $row["total_price"] . ' Sl Shilling'; ?></h5>
                                            <h5 class='card-text'><i class='bi bi-info-circle'></i> <strong>Booking Status:</strong> <?php echo htmlspecialchars($row["booking_status"]); ?></h5>
                                            <form method='post'>
                                                <input type='hidden' name='booking_id' value="<?php echo htmlspecialchars($row["booking_id"]); ?>">
                                                <button type='button' class='btn btn-success text-center update-status' data-booking-id="<?php echo htmlspecialchars($row["booking_id"]); ?>" value='confirmed'>Approve</button>
                                                <button type='button' class='btn btn-danger update-status' data-booking-id="<?php echo htmlspecialchars($row["booking_id"]); ?>" value='cancelled'>Cancel</button>
                                                <button type='button' class='btn btn-secondary edit-booking' data-booking-id="<?php echo htmlspecialchars($row["booking_id"]); ?>" data-pitch-id="<?php echo htmlspecialchars($row["booking_id"]); ?>">Edit</button>
                                                <?php if ($row["payed"] - $row["total_price"] == 0 || $row["total_price"] - $row["payed"] < 0) { ?>
                                                    <!-- If the condition is met, no additional button is shown -->
                                                <?php } else { ?>
                                                    <button type='button' class='btn btn-primary take-money-btn' data-booking-id="<?php echo htmlspecialchars($row["booking_id"]); ?>">Take Money</button>
                                                <?php } ?>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        } else {
                            echo "<div class='col-12'><p>No bookings found for today.</p></div>";
                        }
                        ?>
                    </div>
                </div>
                <!-- Tomorrow's Bookings -->
                <div class="tab-pane fade" id="nav-tomorrow" role="tabpanel" aria-labelledby="nav-tomorrow-tab">
                    <div class="row">
                        <?php
                        if ($result_tomorrow->num_rows > 0) {
                            while ($row = $result_tomorrow->fetch_assoc()) {
                        ?>
                                <div class='col-md-6 col-lg-4'>
                                    <div class='card booking-card mb-4 shadow-sm'>
                                        <div class='card-body'>
                                            <h5 class='card-title'><i class='bi bi-person-circle'></i> <?php echo htmlspecialchars($row["display_name"]); ?></i></h5>
                                            <h5 class='card-text'><i class='bi bi-telephone'></i> <strong>Phone Number:</strong> <?php echo htmlspecialchars($row["phone_number"]); ?></h5>
                                            <h5 class='card-text'><i class='bi bi-clock'></i> <strong>Start Time:</strong> <?php echo htmlspecialchars($row["start_time"]); ?></h5>
                                            <h5 class='card-text'><i class='bi bi-clock-fill'></i> <strong>Lacagta:</strong> <?php echo $row["payed"] - $row["total_price"] . ' Sl Shilling'; ?></h5>
                                            <h5 class='card-text'><i class='bi bi-info-circle'></i> <strong>Booking Status:</strong> <?php echo htmlspecialchars($row["booking_status"]); ?></h5>
                                            <form method='post'>
                                                <input type='hidden' name='booking_id' value="<?php echo htmlspecialchars($row["booking_id"]); ?>">
                                                <div class='d-grid gap-2'>
                                                    <button type='button' class='btn btn-success update-status' data-booking-id="<?php echo htmlspecialchars($row["booking_id"]); ?>" value='confirmed'>Approve</button>
                                                    <button type='button' class='btn btn-danger update-status' data-booking-id="<?php echo htmlspecialchars($row["booking_id"]); ?>" value='cancelled'>Cancel</button>
                                                    <?php if ($row["payed"] - $row["total_price"] == 0 || $row["total_price"] - $row["payed"] < 0) { ?>
                                                    <?php } else { ?>
                                                        <button type='button' class='btn btn-primary take-money-btn' data-booking-id="<?php echo htmlspecialchars($row["booking_id"]); ?>">Take Money</button>
                                                    <?php } ?>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        } else {
                            echo "<div class='col-12'><p>No bookings found for tomorrow.</p></div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Button -->
    <button type="button" class="btn btn-primary text-white btn-lg rounded-circle position-fixed floating-button" data-bs-toggle="modal" data-bs-target="#saveBookingModal">
        <i class="bi bi-plus-lg" style="font-size: 30px;">+</i>
    </button>

    <div class="modal fade" id="saveBookingModal" tabindex="-1" aria-labelledby="saveBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="saveBookingModalLabel">Save Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="saveBookingForm">
                        <input type="hidden" name="booking_id" id="modalBookingId">
                        <div class="mb-3">
                            <label for="amount" class="form-label">Phone Number</label>
                            <input type="hidden" class="form-control" id="pitch_id" name="pitch_id" value="<?php echo  $pitch_id; ?>" required>
                            <input type="hidden" class="form-control" id="amount" name="amount" value="<?php echo  $amount12; ?>" required>
                            <input type="text" class="form-control" id="number" name="number" required>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label"> Date </label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                        <div class="mb-3">
                            <label for="timeSlot" class="form-label">Available Time Slots</label>
                            <select class="form-select" id="timeSlot" name="time_slot" required>
                                <!-- PHP code to populate available slots -->
                                <?php foreach ($available_slots as $slot) {
                                    echo "<option value='$slot'>$slot</option>";
                                } ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="takeMoneyModal" tabindex="-1" aria-labelledby="takeMoneyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="takeMoneyModalLabel">Take Money</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="takeMoneyForm" method="POST" action="take_money.php">
                        <input type="hidden" id="booking_id" name="booking_id">
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount:</label>
                            <input type="number" class="form-control" id="amount" name="amount" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Take Money</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Page End -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Handle booking status updates
            $('.update-status').click(function(e) {
                e.preventDefault();

                var bookingId = $(this).data('booking-id');
                var newStatus = $(this).val();

                if (newStatus === 'cancelled') {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Do you really want to cancel this booking?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, cancel it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: 'update_booking_status.php',
                                type: 'POST',
                                data: {
                                    booking_id: bookingId,
                                    status: newStatus
                                },
                                success: function(response) {
                                    Swal.fire(
                                        'Cancelled!',
                                        'Booking status has been updated.',
                                        'success'
                                    ).then(() => {
                                        location.reload();
                                    });
                                },
                                error: function() {
                                    Swal.fire(
                                        'Error!',
                                        'An error occurred while updating the booking status.',
                                        'error'
                                    );
                                }
                            });
                        }
                    });
                } else {
                    $.ajax({
                        url: 'update_booking_status.php',
                        type: 'POST',
                        data: {
                            booking_id: bookingId,
                            status: newStatus
                        },
                        success: function(response) {
                            Swal.fire(
                                'Updated!',
                                'Booking status has been updated.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'An error occurred while updating the booking status.',
                                'error'
                            );
                        }
                    });
                }
            });

            // Handle editing a booking
            $('.edit-booking').on('click', function() {
                var bookingId = $(this).data('booking-id');
                var pitchId = $(this).data('pitch-id');

                var form = $('<form>', {
                    'method': 'POST',
                    'action': 'edit_booking'
                }).append($('<input>', {
                    'type': 'hidden',
                    'name': 'booking_id',
                    'value': bookingId
                })).append($('<input>', {
                    'type': 'hidden',
                    'name': 'pitch_id',
                    'value': pitchId
                }));

                $('body').append(form);
                form.submit();
            });

            // Ensure only one event listener for Take Money buttons
            $('.take-money-btn').on('click', function() {
                const bookingId = $(this).data('booking-id');
                document.getElementById('booking_id').value = bookingId;
                const takeMoneyModal = new bootstrap.Modal(document.getElementById('takeMoneyModal'));
                takeMoneyModal.show();
            });

            // Handle Save Booking Form submission
            $('#saveBookingForm').on('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(this);
                fetch('save_booking.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Booking saved successfully.',
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 1500); // Reload after 1.5 seconds
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while saving the booking. Please try again later.',
                            showConfirmButton: true
                        });
                    });
            });
        });
    </script>

    <?php
    require './includes/footer.php';
    ?>
    <?php
    $stmt_pitch->close();
    $stmt_today->close();
    $stmt_tomorrow->close();
    $conn->close();
    ?>