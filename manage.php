<?php
session_start();
require './includes/conn.php';
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
</head>

<body>

    <div class="container-fluid fixed-top">
        <div class="container px-0">
            <nav class="navbar navbar-light bg-white navbar-expand-xl">
                <a href="index.php" class="navbar-brand">
                    <h1 class="text-primary display-6">Customers</h1>
                </a>
                <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars text-primary"></span>
                </button>
                <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                    <div class="navbar-nav mx-auto">
                        <a href="admin_dashboard" class="nav-item nav-link">Home</a>
                        <a href="iixidh" class="nav-item nav-link">Iixidh</a>
                        <a href="customers" class="nav-item nav-link active">Past Booking</a>
                        <a href="reports" class="nav-item nav-link active">Reports</a>
                        <a href="manage" class="nav-item nav-link active">Manage</a>
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
                    <button class="nav-link active border-white border-bottom-0" type="button" role="tab" id="nav-today-tab" data-bs-toggle="tab" data-bs-target="#nav-today" aria-controls="nav-today" aria-selected="true">garoonka</button>
                    <button class="nav-link border-white border-bottom-0" type="button" role="tab" id="nav-tomorrow-tab" data-bs-toggle="tab" data-bs-target="#nav-tomorrow" aria-controls="nav-tomorrow" aria-selected="false">Users</button>
                    <button class="nav-link border-white border-bottom-0" type="button" role="tab" id="nav-tomorrow-tab" data-bs-toggle="tab" data-bs-target="#nav-tomorrow" aria-controls="nav-tomorrow" aria-selected="false">Me</button>
                </div>
            </nav>
            <div class="row">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-today" role="tabpanel" aria-labelledby="nav-today-tab">
                        <div class='col-md-6 col-lg-4'>
                            <div class='card booking-card mb-4 shadow-sm'>
                                <div class='card-body'>
                                    <h5 class='card-title'><i class='bi bi-person-circle'></i> <?php //echo htmlspecialchars($row["full_name"]); 
                                                                                                ?></i></h5>
                                    <h5 class='card-text'><i class='bi bi-telephone'></i> <strong>Phone Number:</strong> <?php //echo htmlspecialchars($row["phone_number"]); 
                                                                                                                            ?></h5>
                                    <h5 class='card-text'><i class='bi bi-clock'></i> <strong>Time:</strong> <?php //echo htmlspecialchars($row["start_time"]) . ' - ' . htmlspecialchars($row["end_time"]); 
                                                                                                                ?></h5>
                                    <h5 class='card-text'><i class='bi bi-clock-fill'></i> <strong>Lacagta:</strong> <?php //echo $row["payed"] - $row["total_price"] . ' Sl Shilling'; 
                                                                                                                        ?></h5>
                                    <h5 class='card-text'><i class='bi bi-info-circle'></i> <strong>Booking Status:</strong> <?php //echo htmlspecialchars($row["booking_status"]); 
                                                                                                                                ?></h5>
                                    <form method='post'>
                                        <input type='hidden' name='booking_id' value="<?php //echo htmlspecialchars($row["booking_id"]); 
                                                                                        ?>">

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-tomorrow" role="tabpanel" aria-labelledby="nav-tomorrow-tab">
                        <div class='col-md-6 col-lg-4'>
                            <div class='card booking-card mb-4 shadow-sm'>
                                <div class='card-body'>
                                    <h5 class='card-title'><i class='bi bi-person-circle'></i> <?php echo 'full name'; ?></i></h5>
                                    <h5 class='card-text'><i class='bi bi-telephone'></i> <strong>Phone Number:</strong> <?php echo 'Phone number'; ?></h5>
                                    <h5 class='card-text'><i class='bi bi-clock'></i> <strong>Username:</strong> <?php echo 'username'; ?></h5>
                                    <h5 class='card-text'><i class='bi bi-info-circle'></i> <strong>Status:</strong> <?php echo 'status'; ?></h5>
                                    <form method='post'>
                                        <input type='hidden' name='booking_id' value="<?php echo htmlspecialchars($row["booking_id"]); ?>">
                                        <div class='d-grid gap-2'>
                                            <button type='button' class='btn btn-success update-status' data-booking-id="<?php //echo htmlspecialchars($row["booking_id"]); 
                                                                                                                            ?>" value='confirmed'>Edit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="add_user_modal" tabindex="-1" aria-labelledby="add_user_modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="takeMoneyForm" method="POST" action="process_payment.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="add_user_modalLabel">Take Money</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="booking_id" id="bookingIdInput">
                        <div class="mb-3">
                            <label for="amountInput" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="amountInput" name="amount" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
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
                $('#add_user_modal').modal('show');
            });
        });
    </script>

    <?php
    require './includes/footer.php';
    ?>
</body>

</html>