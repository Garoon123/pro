<?php
session_start();
require './includes/conn.php';
$pitchId = $_SESSION['pitchId'];
$reportType = $_POST['reportType'];
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];

switch ($reportType) {
    case 'dailyBookings':
        $sql = "SELECT booking_date, COUNT(booking_id) AS total_bookings
                FROM bookings
                WHERE pitch_id = '$pitchId' AND booking_date BETWEEN '$startDate' AND '$endDate'
                GROUP BY booking_date
                ORDER BY booking_date DESC";
        break;
    case 'revenueReport':
        $sql = "SELECT booking_date, SUM(total_price) AS total_revenue
                FROM bookings
                WHERE pitch_id = '$pitchId' AND booking_status = 'confirmed' AND booking_date BETWEEN '$startDate' AND '$endDate'
                GROUP BY booking_date
                ORDER BY booking_date DESC";
        break;
    case 'bookingStatusSummary':
        $sql = "SELECT booking_status, COUNT(booking_id) AS total_bookings
                FROM bookings
                WHERE pitch_id = '$pitchId' AND booking_date BETWEEN '$startDate' AND '$endDate'
                GROUP BY booking_status";
        break;
    case 'userBookingFrequency':
        $sql = "SELECT u.full_name AS user_name, COUNT(b.booking_id) AS booking_count
                FROM bookings b
                INNER JOIN users u ON b.user_id = u.user_id
                WHERE b.pitch_id = '$pitchId' AND b.booking_date BETWEEN '$startDate' AND '$endDate'
                GROUP BY u.full_name
                ORDER BY booking_count DESC
                LIMIT 10";
        break;
    case 'pitchUtilizationReport':
        $sql = "SELECT pitch_id, COUNT(booking_id) AS booking_count
                FROM bookings
                WHERE pitch_id = '$pitchId' AND booking_date BETWEEN '$startDate' AND '$endDate'
                GROUP BY pitch_id
                ORDER BY booking_count DESC";
        break;
    case 'paymentStatusReport':
        $sql = "SELECT u.full_name AS user_name, total_price, payed, total_price - payed AS balance_due
                FROM bookings b
                INNER JOIN users u ON b.user_id = u.user_id
                WHERE b.pitch_id = '$pitchId' AND b.total_price > b.payed AND b.booking_date BETWEEN '$startDate' AND '$endDate'";
        break;
    case 'peakBookingTimes':
        $sql = "SELECT start_time, COUNT(booking_id) AS booking_count
                FROM bookings
                WHERE pitch_id = '$pitchId' AND booking_date BETWEEN '$startDate' AND '$endDate'
                GROUP BY start_time
                ORDER BY booking_count DESC";
        break;
    case 'canceledBookingsAnalysis':
        $sql = "SELECT booking_date, COUNT(booking_id) AS canceled_count
                FROM bookings
                WHERE pitch_id = '$pitchId' AND booking_status = 'cancelled' AND booking_date BETWEEN '$startDate' AND '$endDate'
                GROUP BY booking_date
                ORDER BY booking_date DESC";
        break;
    case 'detailedBookingReport':
        $sql = "SELECT u.full_name AS user_name, b.booking_date, b.start_time, b.end_time, b.booking_status, b.payed
                FROM bookings b
                INNER JOIN users u ON b.user_id = u.user_id
                WHERE b.pitch_id = '$pitchId' AND b.booking_date BETWEEN '$startDate' AND '$endDate'
                ORDER BY b.booking_date DESC";
        break;
    case 'monthlyRevenueComparison':
        $sql = "SELECT YEAR(booking_date) AS year, MONTH(booking_date) AS month, SUM(total_price) AS total_revenue
                FROM bookings
                WHERE pitch_id = '$pitchId' AND booking_status = 'confirmed' AND booking_date BETWEEN '$startDate' AND '$endDate'
                GROUP BY year, month
                ORDER BY year DESC, month DESC";
        break;
    default:
        echo "Invalid report type selected.";
        exit;
}
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
    <?php
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // Display the results in a table
        echo "<style>
        .container1 {
            max-width: 100%;
            margin-top: 120px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            background-color: #fff;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 12px;
            text-align: left;
            border-top: 1px solid #dee2e6;
        }
        .table thead th {
            background-color: #f1f1f1;
            border-bottom: 2px solid #dee2e6;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }
        .table-bordered {
            border: 1px solid #dee2e6;
        }
        h2 {
            margin-bottom: 20px;
            font-size: 1.5rem;
            color: #333;
        }
        p {
            font-size: 1rem;
            color: #666;
        }
        @media (max-width: 768px) {
            .table th, .table td {
                padding: 8px;
            }
            h2 {
                font-size: 1.2rem;
            }
        }
    </style>";
        echo "<div class='container container1 mt-5 py-5'>";
        echo "<h2 class='mb-4 text-center'>Garoono</h2>";
        echo "<h3 class='text-center'>Type of Report: " . $reportType . "</h3>";
        echo "<h3 class='text-center'>Start Date: " . $startDate . "</h3>";
        echo "<h3 class='text-center'>End Date: " . $endDate . "</h3>";
        echo "<div class='table-responsive mt-3'>";
        echo "<table id='reportTable' class='table table-striped table-bordered'>";
        echo "<thead><tr>";
        while ($field = $result->fetch_field()) {
            echo "<th>{$field->name}</th>";
        }
        echo "</tr></thead><tbody>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>{$value}</td>";
            }
            echo "</tr>";
        }

        echo "</tbody></table>";
        echo "</div>";
        echo "</div>";
    } else {
        echo "<div class='container mt-5'><p>No results found for the selected report.</p></div>";
    }

    $conn->close();
    ?>

    <script>
        $(document).ready(function() {
            $('#reportTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });
    </script>

</body>

</html>