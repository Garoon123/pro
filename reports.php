<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Garoono</title>
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

    <!-- Include DataTables CSS and JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">

    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>

</head>

<body>

    <div class="container-fluid fixed-top ">
        <div class="container px-0">
            <nav class="navbar navbar-light bg-white navbar-expand-xl">
                <a href="admin_dashboard" class="navbar-brand">
                    <h1 class="text-primary display-6">Garoono</h1>
                </a>
                <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars text-primary"></span>
                </button>
                <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                    <div class="navbar-nav mx-auto">
                        <a href="admin_dashboard" class="nav-item nav-link ">Home</a>
                        <a href="iixidh" class="nav-item nav-link">Iixidh</a>
                        <a href="customers" class="nav-item nav-link">Past Booking</a>
                        <a href="reports" class="nav-item nav-link active">Reports</a>
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
    <div class="container mt-5">
        <h2 class="mb-4">Generate Report</h2>
        <form action="report_read.php" method="post">
            <div class="form-group">
                <label for="reportType">Select Report</label>
                <select class="form-control" id="reportType" name="reportType" required>
                    <option value="dailyBookings">Daily Bookings Report</option>
                    <option value="revenueReport">Revenue Report</option>
                    <option value="bookingStatusSummary">Booking Status Summary</option>
                    <option value="userBookingFrequency">User Booking Frequency</option>
                    <option value="pitchUtilizationReport">Pitch Utilization Report</option>
                    <option value="paymentStatusReport">Payment Status Report</option>
                    <option value="peakBookingTimes">Peak Booking Times</option>
                    <option value="canceledBookingsAnalysis">Canceled Bookings Analysis</option>
                    <option value="detailedBookingReport">Detailed Booking Report</option>
                    <option value="monthlyRevenueComparison">Monthly Revenue Comparison</option>
                </select>
            </div>
            <div class="form-group">
                <label for="startDate">Start Date</label>
                <input type="date" class="form-control" id="startDate" name="startDate" required>
            </div>
            <div class="form-group mb-3">
                <label for="endDate">End Date</label>
                <input type="date" class="form-control" id="endDate" name="endDate" required>
            </div>
            <button type="submit" class="btn btn-primary">Generate Report</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
</body>

</html>