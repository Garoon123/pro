<?php
session_start();
require './includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: index.php');
    exit();
}
function formatTime($time)
{
    $dateTime = new DateTime($time);
    return $dateTime->format('h:i A');
}

$user_id = $_SESSION['user_id'];
$sql_pitch = "SELECT pitch_id FROM pitches WHERE user_id = ?";
$stmt_pitch = $conn->prepare($sql_pitch);
$stmt_pitch->bind_param("i", $user_id);
$stmt_pitch->execute();
$result_pitch = $stmt_pitch->get_result();
if ($result_pitch->num_rows > 0) {
    $row_pitch = $result_pitch->fetch_assoc();
    $pitch_id = $row_pitch['pitch_id'];
} else {
    die("No pitch found for the user.");
}

// Get today's and tomorrow's dates
$today = date('l');
$tomorrow = date('l', strtotime('+1 day'));

// Fetch recurring bookings for today and tomorrow
$recurring_bookings_sql = "
    SELECT rb.recurring_booking_id,
           u.full_name,
           u.phone_number,
           rb.day_of_week,
           rb.start_time,
           rb.end_time
    FROM recurring_bookings rb
    JOIN users u ON rb.user_id = u.user_id
    WHERE rb.pitch_id = ? AND (rb.day_of_week = ? OR rb.day_of_week = ?)
    ORDER BY rb.day_of_week, rb.start_time
";

$stmt_recurring_bookings = $conn->prepare($recurring_bookings_sql);
$stmt_recurring_bookings->bind_param("iss", $pitch_id, $today, $tomorrow);
$stmt_recurring_bookings->execute();
$result_recurring_bookings = $stmt_recurring_bookings->get_result();

$bookings_by_day = [];
while ($row = $result_recurring_bookings->fetch_assoc()) {
    $day = $row['day_of_week'];
    if (!isset($bookings_by_day[$day])) {
        $bookings_by_day[$day] = [];
    }
    $bookings_by_day[$day][] = $row;
}
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
        .booking-card {
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.2s;
        }

        .booking-card:hover {
            transform: scale(1.05);
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

        .nav-tabs {
            white-space: nowrap;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .nav-tabs .nav-link {
            display: inline-block;
        }
    </style>
</head>

<body>

    <div class="container-fluid fixed-top">
        <div class="container px-0">
            <nav class="navbar navbar-light bg-white navbar-expand-xl">
                <a href="index.php" class="navbar-brand">
                    <h1 class="text-primary display-6">Iixidh</h1>
                </a>
                <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars text-primary"></span>
                </button>
                <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                    <div class="navbar-nav mx-auto">
                        <a href="admin_dashboard" class="nav-item nav-link">Home</a>
                        <a href="iixidh" class="nav-item nav-link active">Iixidh</a>
                        <a href="customers" class="nav-item nav-link">Past Booking</a>
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
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Search by keyword</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex align-items-center">
                    <div class="input-group w-75 mx-auto d-flex">
                        <input type="search" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                        <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Search End -->
    <!-- Booking Page Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="today-tab" data-bs-toggle="tab" data-bs-target="#today" type="button" role="tab" aria-controls="today" aria-selected="true">Today</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tomorrow-tab" data-bs-toggle="tab" data-bs-target="#tomorrow" type="button" role="tab" aria-controls="tomorrow" aria-selected="false">Tomorrow</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="today" role="tabpanel" aria-labelledby="today-tab">
                    <div class="row">
                        <?php
                        if (isset($bookings_by_day[$today])) {
                            foreach ($bookings_by_day[$today] as $booking) {
                                echo "<div class='col-md-6 col-lg-4'>
                                        <div class='card booking-card mb-4 shadow-sm'>
                                            <div class='card-body'>
                                                <h5 class='card-title'><i class='bi bi-person-circle'></i> " . htmlspecialchars($booking["full_name"]) . "</h5>
                                                <h5 class='card-text'><i class='bi bi-telephone'></i> <strong>Phone Number:</strong> " . htmlspecialchars($booking["phone_number"]) . "</h5>
                                           <h5 class='card-text'><i class='bi bi-clock'></i> <strong>Time:</strong> " . htmlspecialchars(formatTime($booking["start_time"])) . " - " . htmlspecialchars(formatTime($booking["end_time"])) . "</h5>;
                                                </div>
                                        </div>
                                    </div>";
                            }
                        } else {
                            echo "<div class='col-12'><p>No recurring bookings found for today.</p></div>";
                        }
                        ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="tomorrow" role="tabpanel" aria-labelledby="tomorrow-tab">
                    <div class="row">
                        <?php
                        if (isset($bookings_by_day[$tomorrow])) {
                            foreach ($bookings_by_day[$tomorrow] as $booking) {
                                echo "<div class='col-md-6 col-lg-4'>
                                        <div class='card booking-card mb-4 shadow-sm'>
                                            <div class='card-body'>
                                                <h5 class='card-title'><i class='bi bi-person-circle'></i> " . htmlspecialchars($booking["full_name"]) . "</h5>
                                                <h5 class='card-text'><i class='bi bi-telephone'></i> <strong>Phone Number:</strong> " . htmlspecialchars($booking["phone_number"]) . "</h5>
                                                <h5 class='card-text'><i class='bi bi-clock'></i> <strong>Time:</strong> " . htmlspecialchars($booking["start_time"]) . " - " . htmlspecialchars($booking["end_time"]) . "</h5>
                                            </div>
                                        </div>
                                    </div>";
                            }
                        } else {
                            echo "<div class='col-12'><p>No recurring bookings found for tomorrow.</p></div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Booking Page End -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
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

            $('.take-money-btn').on('click', function() {
                var bookingId = $(this).data('booking-id');
                $('#bookingIdInput').val(bookingId);
                $('#takeMoneyModal').modal('show');
            });
        });
    </script>

    <?php
    require './includes/footer.php';
    ?>

    <?php
    $stmt_pitch->close();
    $stmt_recurring_bookings->close();
    $conn->close();
    ?>