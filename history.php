<?php
session_start();
require './includes/header.php';
require './includes/conn.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login');
    exit();
} else {
    $user_id = $_SESSION['user_id'];
    $today = date("Y-m-d");

    // Updated SQL queries to include pitch name and phone number
    $sql_today = "
    SELECT b.booking_id, b.total_price, b.payed, p.name, p.phone_number, b.start_time, b.end_time, b.booking_status
    FROM bookings b
    JOIN pitches p ON b.pitch_id = p.pitch_id
    WHERE b.booking_date = ? AND b.user_id = ?
    ";
    $stmt_today = $conn->prepare($sql_today);
    $stmt_today->bind_param("si", $today, $user_id);
    $stmt_today->execute();
    $result_today = $stmt_today->get_result();

    $sql_past = "
    SELECT b.booking_id, b.total_price, b.payed, p.name, p.phone_number, b.start_time, b.end_time, b.booking_status
    FROM bookings b
    JOIN pitches p ON b.pitch_id = p.pitch_id
    WHERE b.booking_date < ? AND b.user_id = ?
    ";
    $stmt_past = $conn->prepare($sql_past);
    $stmt_past->bind_param("si", $today, $user_id);
    $stmt_past->execute();
    $result_past = $stmt_past->get_result();

    // Fetch data for Kuu Xidhan
    // Get current day of the week
    $sql_kuu_xidhan = "
    SELECT rb.day_of_week, rb.start_time, rb.end_time, p.name, p.phone_number
    FROM recurring_bookings rb
    JOIN pitches p ON rb.pitch_id = p.pitch_id
    WHERE rb.user_id = ?
    ";
    $stmt_kuu_xidhan = $conn->prepare($sql_kuu_xidhan);
    $stmt_kuu_xidhan->bind_param("i",  $user_id);
    $stmt_kuu_xidhan->execute();
    $result_kuu_xidhan = $stmt_kuu_xidhan->get_result();
}
?>
<!-- Navbar start -->
<div class="container-fluid fixed-top">

    <div class="container px-0">
        <nav class="navbar navbar-light bg-white navbar-expand-xl">
            <a href="index" class="navbar-brand">
                <h1 class="text-primary display-6">Garoon Kireeye</h1>
            </a>
            <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars text-primary"></span>
            </button>
            <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                    <a href="index" class="nav-item nav-link ">Home</a>
                    <a href="stadiums" class="nav-item nav-link ">Garoonada</a>
                    <a href="history" class="nav-item nav-link active">Taariikh</a>
                    <a href="contact" class="nav-item nav-link">Contact</a>
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
                <button class="nav-link border-white border-bottom-0" type="button" role="tab" id="nav-kuu-xidhan-tab" data-bs-toggle="tab" data-bs-target="#nav-kuu-xidhan" aria-controls="nav-kuu-xidhan" aria-selected="false">Kuu Xidhan</button>
                <button class="nav-link border-white border-bottom-0" type="button" role="tab" id="nav-past-tab" data-bs-toggle="tab" data-bs-target="#nav-past" aria-controls="nav-past" aria-selected="false">Past Bookings</button>

            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <!-- Today's Bookings -->
            <div class="tab-pane fade show active" id="nav-today" role="tabpanel" aria-labelledby="nav-today-tab">
                <div class="vertical-scroll">
                    <?php
                    if ($result_today->num_rows > 0) {
                        while ($row = $result_today->fetch_assoc()) {
                    ?>
                            <div class='card booking-card col-12 col-sm-6'>
                                <div class='card-body'>
                                    <h5 class='card-title'><i class='bi bi-person-circle'></i> <?php echo htmlspecialchars($row["name"]); ?></h5>
                                    <h5 class='card-text'><i class='bi bi-telephone'></i> <strong>Phone Number:</strong> <?php echo htmlspecialchars($row["phone_number"]); ?></h5>
                                    <h5 class='card-text'><i class='bi bi-clock'></i> <strong>Time:</strong> <?php echo htmlspecialchars($row["start_time"]) . ' - ' . htmlspecialchars($row["end_time"]); ?></h5>
                                    <h5 class='card-text'><i class='bi bi-cash'></i> <strong>Amount:</strong> <?php echo $row["payed"] . ' / ' . $row["total_price"] . ' Sl Shilling'; ?></h5>
                                    <h5 class='card-text'><i class='bi bi-info-circle'></i> <strong>Status:</strong> <?php echo htmlspecialchars($row["booking_status"]); ?></h5>
                                </div>
                            </div>
                        <?php
                        }
                    } else {
                        ?>
                        <div class='col-12'>
                            <div class='card no-bookings-card'>
                                <div class='card-body'>
                                    <p>No bookings found for today.</p>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <!-- Past Bookings -->
            <div class="tab-pane fade" id="nav-past" role="tabpanel" aria-labelledby="nav-past-tab">
                <div class="vertical-scroll">
                    <?php
                    if ($result_past->num_rows > 0) {
                        while ($row = $result_past->fetch_assoc()) {
                    ?>
                            <div class='card booking-card col-12 col-sm-6'>
                                <div class='card-body'>
                                    <h5 class='card-title'><i class='bi bi-person-circle'></i> <?php echo htmlspecialchars($row["name"]); ?></h5>
                                    <h5 class='card-text'><i class='bi bi-telephone'></i> <strong>Phone Number:</strong> <?php echo htmlspecialchars($row["phone_number"]); ?></h5>
                                    <h5 class='card-text'><i class='bi bi-clock'></i> <strong>Time:</strong> <?php echo htmlspecialchars($row["start_time"]) . ' - ' . htmlspecialchars($row["end_time"]); ?></h5>
                                    <h5 class='card-text'><i class='bi bi-cash'></i> <strong>Amount:</strong> <?php echo $row["payed"] . ' / ' . $row["total_price"] . ' Sl Shilling'; ?></h5>
                                    <h5 class='card-text'><i class='bi bi-info-circle'></i> <strong>Status:</strong> <?php echo htmlspecialchars($row["booking_status"]); ?></h5>
                                </div>
                            </div>
                        <?php
                        }
                    } else {
                        ?>
                        <div class='card no-bookings-card'>
                            <div class='card
<div class=' card-body'>
                                <div class='col-12'>
                                    <p>No past bookings found.</p>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <!-- Kuu Xidhan -->
            <div class="tab-pane fade" id="nav-kuu-xidhan" role="tabpanel" aria-labelledby="nav-kuu-xidhan-tab">
                <div class="vertical-scroll">
                    <?php
                    if ($result_kuu_xidhan->num_rows > 0) {
                        while ($row = $result_kuu_xidhan->fetch_assoc()) {
                    ?>
                            <div class='card booking-card col-12 col-sm-6'>
                                <div class='card-body'>
                                    <h5 class='card-title'><i class='bi bi-person-circle'></i> <?php echo htmlspecialchars($row["name"]); ?></h5>
                                    <h5 class='card-text'><i class='bi bi-telephone'></i> <strong>Phone Number:</strong> <?php echo htmlspecialchars($row["phone_number"]); ?></h5>
                                    <h5 class='card-text'><i class='bi bi-clock'></i> <strong>Time:</strong> <?php echo htmlspecialchars($row["start_time"]) . ' - ' . htmlspecialchars($row["end_time"]); ?></h5>
                                    <h5 class='card-text'><i class='bi bi-calendar'></i> <strong>Maalinta:</strong> <?php echo htmlspecialchars($row["day_of_week"]); ?></h5>
                                </div>
                            </div>
                        <?php
                        }
                    } else {
                        ?>
                        <div class='card no-bookings-card'>
                            <div class='card-body'>
                                <div class='col-12'>
                                    <p>No bookings found for this day of the week.</p>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="lib/jquery/jquery.min.js"></script>
<script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.update-status').forEach(button => {
            button.addEventListener('click', function() {
                var bookingId = this.getAttribute('data-booking-id');
                var status = this.value;
                // Perform AJAX request to update booking status
                // This part will require further implementation on the backend
            });
        });

        document.querySelectorAll('.take-money-btn').forEach(button => {
            button.addEventListener('click', function() {
                var bookingId = this.getAttribute('data-booking-id');
                // Perform action to take money
                // This part will require further implementation on the backend
            });
        });
    });
</script>

<?php include 'includes/footer.php'; ?>