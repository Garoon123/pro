<?php
session_start();
require './includes/conn.php';
require './includes/header.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_to'] = 'pitch.php?single-pitch=' . $_POST['single-pitch'];
    header('location: login.php');
    exit;
}

$pitch = null;
$available_slots = [];
$location_name = '';
$xaafada_name = '';
$nearby_pitches = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['single-pitch'])) {
        $pitch_id = $_POST['single-pitch'];

        // Fetch pitch details
        $query = "SELECT * FROM pitches WHERE pitch_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $pitch_id);
        $stmt->execute();
        $result_pitch = $stmt->get_result();
        $pitch = $result_pitch->fetch_assoc();

        if ($pitch) {
            // Fetch location name
            $location_query = "SELECT town FROM locations WHERE location_id = ?";
            $location_stmt = $conn->prepare($location_query);
            $location_stmt->bind_param("i", $pitch['location_id']);
            $location_stmt->execute();
            $location_result = $location_stmt->get_result();
            $location = $location_result->fetch_assoc();
            $location_name = $location['town'];

            // Fetch xaafada name
            $xaafada_query = "SELECT xaafada FROM xaafada WHERE id = ?";
            $xaafada_stmt = $conn->prepare($xaafada_query);
            $xaafada_stmt->bind_param("i", $pitch['xaafada_id']);
            $xaafada_stmt->execute();
            $xaafada_result = $xaafada_stmt->get_result();
            $xaafada = $xaafada_result->fetch_assoc();
            $xaafada_name = $xaafada['xaafada'];

            // Fetch bookings for today
            $query = "SELECT * FROM bookings WHERE pitch_id = ? AND booking_date = CURDATE()";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $pitch_id);
            $stmt->execute();
            $result_bookings = $stmt->get_result();
            $today = date('Y-m-d');
            $time_slots = ['16:30', '19:00', '20:00', '21:00', '22:00', '23:00']; // Adjust this array as needed

            // Fetch booked slots without seconds
            $booked_slots = [];
            while ($row = $result_bookings->fetch_assoc()) {
                $booked_slots[] = substr($row['start_time'], 0, 5); // Get only HH:MM
            }

            // Filter out passed time slots
            $current_time = date('H:i');
            foreach ($time_slots as $slot) {
                if ($slot >= $current_time && !in_array($slot, $booked_slots)) {
                    $available_slots[] = $slot;
                }
            }

            // Fetch recurring bookings
            $recurring_query = "SELECT * FROM recurring_bookings WHERE pitch_id = ? AND day_of_week = DAYNAME(CURDATE())";
            $recurring_stmt = $conn->prepare($recurring_query);
            $recurring_stmt->bind_param("i", $pitch_id);
            $recurring_stmt->execute();
            $result_recurring = $recurring_stmt->get_result();
            while ($row = $result_recurring->fetch_assoc()) {
                $booked_slots[] = substr($row['start_time'], 0, 5); // Get only HH:MM
            }

            // Update available time slots
            $available_slots = array_diff($available_slots, $booked_slots);

            // Fetch nearby stadiums
            $nearby_query = "SELECT * FROM pitches WHERE location_id = ? AND pitch_id != ?";
            $nearby_stmt = $conn->prepare($nearby_query);
            $nearby_stmt->bind_param("ii", $pitch['location_id'], $pitch_id);
            $nearby_stmt->execute();
            $nearby_result = $nearby_stmt->get_result();
            $nearby_pitches = $nearby_result->fetch_all(MYSQLI_ASSOC);
        } else {
            echo "Error: Pitch information not found.";
            exit;
        }
    }
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
</head>

<body>
    <!-- <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div> -->
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
                        <a href="stadiums" class="nav-item nav-link active">Garoonada</a>
                        <a href="history" class="nav-item nav-link">Taariikh</a>
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
    <div class="container-fluid py-5 mt-5">
        <div class="container py-5">
            <div class="row g-4 mb-5">
                <div class="col-lg-12 col-xl-12">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="border rounded">
                                <a href="#">
                                    <img src="<?php echo $pitch['image']; ?>" class="img-fluid rounded" alt="Image">
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h4 class="fw-bold mb-3"><?php echo $pitch['name']; ?></h4>
                            <p class="mb-3">Goobta: <?php echo $location_name . ', ' . $xaafada_name; ?></p>
                            <h5 class="fw-bold mb-3"><?php echo 'Lacagta: ' . $pitch['price_per_hour']; ?>$ / Team</h5>
                            <h5 class="fw-bold mb-3"><?php echo 'Ciyaartoyda: ' . $pitch['type'] . ' / ' . $pitch['type']; ?></h5>
                            <div class="d-flex mb-4">
                                <i class="fa fa-phone text-secondary"></i>
                                <h3><span><?php echo $pitch['phone_number']; ?></span></h3>
                            </div>
                            <div class="mb-4">
                                <?php foreach ($available_slots as $slot) { ?>
                                    <button class="btn btn-primary mb-2 select-time-btn" data-toggle="modal" data-target="#phoneModal" data-time="<?php echo $slot; ?>">
                                        <?php echo $slot; ?>
                                    </button>
                                <?php } ?>
                            </div>
                            <div style="display: flex; gap: 10px;">
                                <button type="submit" class="btn border border-secondary rounded-pill px-4 py-2 mb-4 text-primary" data-toggle="modal" data-target="#kiraysoModal">
                                    <i class="fa fa-shopping-bag me-2 text-primary"></i> Kirayso
                                </button>
                                <button class="btn border border-secondary rounded-pill px-4 py-2 mb-4 text-primary" data-toggle="modal" data-target="#xidhoModal">
                                    <i class="fa fa-calendar-alt me-2 text-primary"></i> Xidho
                                </button>

                            </div>

                        </div>
                        <div class="col-lg-12">
                            <div class="tab-pane active" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                                <p><?php echo $pitch['description']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h1 class="fw-bold mb-0">Garoonada Ka ag Dhaw</h1>
            <div class="vesitable">
                <div class="owl-carousel vegetable-carousel justify-content-center">
                    <?php foreach ($nearby_pitches as $nearby_pitch) { ?>
                        <div class="border border-primary rounded position-relative vesitable-item">
                            <div class="vesitable-img">
                                <img src="<?php echo $nearby_pitch['image']; ?>" class="img-fluid w-100 rounded-top" alt="">
                            </div>
                            <div class="text-white bg-primary px-3 py-1 rounded position-absolute" style="top: 10px; right: 10px;"><?php echo $nearby_pitch['name']; ?></div>
                            <div class="p-4 pb-0 rounded-bottom">
                                <h4><?php echo $nearby_pitch['name']; ?></h4>
                                <p><?php echo $nearby_pitch['description']; ?></p>
                                <div class="d-flex justify-content-between flex-lg-wrap">
                                    <p class="text-dark fs-5 fw-bold"><?php echo $nearby_pitch['price_per_hour']; ?> $ / hr</p>
                                    <a href="#" class="btn border border-secondary rounded-pill px-3 py-1 mb-4 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Day and Time Modal -->
    <div class="modal fade" id="dayTimeModal" tabindex="-1" aria-labelledby="dayTimeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dayTimeModalLabel">Select Day and Time</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="dayModalSelect" class="form-label">Select Day:</label>
                            <select class="form-control" id="dayModalSelect">
                                <?php foreach ($days as $day) {
                                    echo "<option value='$day'>$day</option>";
                                } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="timeModalSelect" class="form-label">Select Time:</label>
                            <select class="form-control" id="timeModalSelect">
                                <?php foreach ($available_slots as $slot) {
                                    echo "<option value='$slot'>$slot</option>";
                                } ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Xidho Modal -->
    <div class="modal fade" id="xidhoModal" tabindex="-1" role="dialog" aria-labelledby="xidhoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="xidho" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="xidhoModalLabel">Xidho Garoonka</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="pitch_id" value="<?php echo $pitch['pitch_id']; ?>">
                        <div class="form-group">
                            <label for="day_of_week">Maalinta aad xidhanaysid</label>
                            <select class="form-control" name="day_of_week" id="day_of_week">
                                <option value="Saturday">Sabti</option>
                                <option value="Sunday">Axad</option>
                                <option value="Monday">Isniin</option>
                                <option value="Tuesday">Salaasa</option>
                                <option value="Wednesday">Arbaca</option>
                                <option value="Thursday">Khamiis</option>
                                <option value="Friday">Jimce</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="original_time_slot">Wakhtiga aad xidhanaysid</label>
                            <select class="form-control" name="original_time_slot" id="original_time_slot">
                                <?php foreach ($available_slots as $slot) : ?>
                                    <option value="<?php echo $slot; ?>"><?php echo $slot; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="phone_number">Phone Number hadii kaaga la waayo</label>
                            <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="numberka Saaxiibkaa" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Kirayso Modal -->
    <div class="modal fade" id="kiraysoModal" tabindex="-1" role="dialog" aria-labelledby="kiraysoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="book_pitch" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="kiraysoModalLabel">Kirayso Garoon</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="pitch_id" value="<?php echo $pitch['pitch_id']; ?>">
                        <div class="form-group">
                            <label for="available_time">Wakhtiyada Banaan</label>
                            <select class="form-control" name="available_time" id="available_time">
                                <?php foreach ($available_slots as $slot) : ?>
                                    <option value="<?php echo $slot; ?>"><?php echo $slot; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="price_per_hour" value="<?php echo $pitch['price_per_hour']; ?>">

                            <label for="phone_number">Phone NumberKa Saaxiibkaa</label>
                            <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="Meel lagaala soo xidhiidho hadii la waayo numberkaaga" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal for phone number input -->
    <div class="modal fade" id="phoneModal" tabindex="-1" aria-labelledby="phoneModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="phoneModalLabel">Gali Number kale oo lagaala soo xidhiidho</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="phoneForm" action="book_pitch" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="available_time" id="selectedTime">
                        <input type="hidden" name="pitch_id" value="<?php echo $pitch['pitch_id']; ?>">
                        <input type="hidden" name="price_per_hour" value="<?php echo $pitch['price_per_hour']; ?>">
                        <div class="form-group">
                            <label for="phoneNumber">Phone Number</label>
                            <input type="text" class="form-control" id="phoneNumber" name="phone_number" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Include jQuery, Popper.js, and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var selectTimeButtons = document.querySelectorAll('.select-time-btn');
            var selectedTimeInput = document.getElementById('selectedTime');

            selectTimeButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var selectedTime = this.getAttribute('data-time');
                    selectedTimeInput.value = selectedTime;
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#kiraysoModal').on('show.bs.modal', function(event) {
                // Additional JavaScript if needed
            });

            $('#xidhoModal').on('show.bs.modal', function(event) {
                // Additional JavaScript if needed
            });
        });
    </script>
    <?php include 'includes/footer.php'; ?>