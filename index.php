<?php
session_start();
require './includes/header.php';
require './includes/conn.php';

?>

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
                    <a href="index" class="nav-item nav-link active">Home</a>
                    <a href="stadiums" class="nav-item nav-link ">Garoonada</a>
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
<!-- Modal Search Start -->
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
<style>
    .carousel-caption {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        text-align: center;
        width: 60%;
    }
</style>
<div class="container-fluid hero-header">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="carousel-content d-flex flex-column align-items-center justify-content-center position-relative text-center">
                    <div class="carousel-text mb-3">
                        <h4 class="text-white">100% Organic Foods</h4>
                        <h1 class="display-3 text-white">Organic Veggies & Fruits Foods</h1>
                    </div>
                    <img src="img/pitch1.jpg" class="d-block w-100" alt="Image 1">
                    <div class="carousel-buttons mt-3">
                        <!-- <a href="#" class="btn btn-secondary px-4 py-2">Learn More</a> -->
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="carousel-content d-flex flex-column align-items-center justify-content-center position-relative text-center">
                    <div class="carousel-text mb-3">
                        <h4 class="text-white">100% Organic Foods</h4>
                        <h1 class="display-3 text-white">Organic Veggies & Fruits Foods</h1>
                    </div>
                    <img src="img/pitch2.jpg" class="d-block w-100" alt="Image 2">
                    <div class="carousel-buttons mt-3">
                        <!-- <a href="#" class="btn btn-secondary px-4 py-2">Learn More</a> -->
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="carousel-content d-flex flex-column align-items-center justify-content-center position-relative text-center">
                    <div class="carousel-text mb-3">
                        <h4 class="text-white">100% Organic Foods</h4>
                        <h1 class="display-3 text-white">Organic Veggies & Fruits Foods</h1>
                    </div>
                    <img src="img/pitch3.jpg" class="d-block w-100" alt="Image 3">
                    <div class="carousel-buttons mt-3">
                        <!-- <a href="#" class="btn btn-secondary px-4 py-2">Learn More</a> -->
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="carousel-content d-flex flex-column align-items-center justify-content-center position-relative text-center">
                    <div class="carousel-text mb-3">
                        <h4 class="text-white">100% Organic Foods</h4>
                        <h1 class="display-3 text-white">Organic Veggies & Fruits Foods</h1>
                    </div>
                    <img src="img/pitch4.jpg" class="d-block w-100" alt="Image 4">
                    <div class="carousel-buttons mt-3">
                        <!-- <a href="#" class="btn btn-secondary px-4 py-2">Learn More</a> -->
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="carousel-content d-flex flex-column align-items-center justify-content-center position-relative text-center">
                    <div class="carousel-text mb-3">
                        <h4 class="text-white">100% Organic Foods</h4>
                        <h1 class="display-3 text-white">Organic Veggies & Fruits Foods</h1>
                    </div>
                    <img src="img/pitch5.jpg" class="d-block w-100" alt="Image 5">
                    <div class="carousel-buttons mt-3">
                        <!-- <a href="#" class="btn btn-secondary px-4 py-2">Learn More</a> -->
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev bg-warning" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next bg-warning" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon text-dark" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>

<style>
    .carousel-content {
        height: 80vh;
        overflow: hidden;
    }

    .carousel-text {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
    }

    .carousel-buttons {
        margin-top: 2px;
        position: relative;
        z-index: 10;
    }

    @media (max-width: 576px) {
        .carousel-text h1 {
            font-size: 1.75rem;
        }

        .carousel-text h4 {
            font-size: 1.25rem;
        }

        .carousel-buttons a {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
        }

        .carousel-content {
            height: 30vh;
        }
    }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Hero End -->
<!-- Featurs Section Start -->
<div class="container-fluid features mt-5">
    <div class="container ">
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="features-item text-center rounded bg-light p-4">
                    <div class="features-icon btn-square rounded-circle bg-white mb-5 mx-auto">
                        <i class="fas fa-map-marker-alt fa-3x text-warning"></i>
                    </div>
                    <div class="features-content text-center">
                        <h5>Goobo Macquul ah</h5>
                        <p class="mb-0">Raadi oo hel Garoonada kuu dhaw dhib la'aan.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="features-item text-center rounded bg-light p-4">
                    <div class="features-icon btn-square rounded-circle bg-white mb-5 mx-auto">
                        <i class="fas fa-calendar-check fa-3x text-warning"></i>
                    </div>
                    <div class="features-content text-center">
                        <h5>Ku kirayso Online ahaan</h5>
                        <p class="mb-0">Hel macluumaadka garoonada Online ahaana ku kirayso</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="features-item text-center rounded bg-light p-4">
                    <div class="features-icon btn-square rounded-circle bg-white mb-5 mx-auto">
                        <i class="fas fa-star fa-3x text-warning"></i>
                    </div>
                    <div class="features-content text-center">
                        <h5>garoonada ugu fiican</h5>
                        <p class="mb-0">Garoono.com waxaad ka helaysaa garoonada ugu tayad fiican</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="features-item text-center rounded bg-light p-4">
                    <div class="features-icon btn-square rounded-circle bg-white mb-5 mx-auto">
                        <i class="fas fa-headset fa-3x text-warning"></i>
                    </div>
                    <div class="features-content text-center">
                        <h5>24/7 Taageero</h5>
                        <p class="mb-0">garoono.com waxa kuugu diyaar ah shaqaale loo diyaariyay soo dhawawyntaada.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Garoonada nav bars-->
<div class="container-fluid fruite py-5">
    <div class="container py-5">
        <div class="tab-class text-center">
            <div class="row g-4">
                <div class="col-lg-4 text-start">
                    <h1>Garoonada</h1>
                </div>
                <div class="col-lg-8 text-end">
                    <ul class="nav nav-pills d-flex text-center mb-5 overflow-auto" style="flex-wrap: nowrap; white-space: nowrap;">
                        <li class="nav-item">
                            <a class="d-flex m-2 py-2 bg-light rounded-pill active" data-bs-toggle="pill" href="#tab-1">
                                <span class="text-dark" style="width: 130px;"> Dhamaan</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="d-flex py-2 m-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-2">
                                <span class="text-dark" style="width: 130px;">Sh. Cali</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="d-flex m-2 py-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-3">
                                <span class="text-dark" style="width: 130px;">Sh. Cismaan</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="d-flex m-2 py-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-4">
                                <span class="text-dark" style="width: 130px;">Jarka</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="d-flex m-2 py-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-5">
                                <span class="text-dark" style="width: 130px;">Cali Black</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane fade show p-0 active">
                <div class="row g-4">
                    <div class="col-lg-12">
                        <div class="row g-4">
                            <?php
                            $query = "SELECT p.*, l.town, x.xaafada
FROM pitches p
LEFT JOIN locations l ON p.location_id = l.location_id
LEFT JOIN xaafada x ON p.xaafada_id = x.id
LIMIT 6;";
                            $result = mysqli_query($conn, $query);

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <img src="<?php echo $row['image']; ?>" class="img-fluid w-100 rounded-top" alt="<?php echo $row['name']; ?>">
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;"><?php echo $row['name']; ?></div>
                                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                <h4><?php echo $row['name']; ?></h4>
                                                <p><?php echo $row['town'] . ', ' . $row['xaafada']; ?></p>
                                                <div class="d-flex justify-content-between flex-lg-wrap align-items-center">
                                                    <p class="text-dark fs-5 fw-bold mb-0 text-center">$<?php echo $row['price_per_hour']; ?> / hour</p>
                                                    <form action="hire" method="post">
                                                        <input type="hidden" name="single-pitch" value="<?php echo $row['pitch_id']; ?>">
                                                        <button type="submit" name="submit" class="btn border border-secondary rounded-pill px-3 text-primary">
                                                            <i class="fa fa-shopping-bag me-2 text-primary"></i>Kirayso
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo "Ma jiro Garoono xaafadan ku yaala oo diwaan gashan";
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
            <div id="tab-2" class="tab-pane fade show p-0">
                <div class="row g-4">
                    <div class="col-lg-12">
                        <div class="row g-4">
                            <?php
                            $query1 = "
    SELECT p.*, l.town, x.xaafada
    FROM pitches p
    LEFT JOIN locations l ON p.location_id = l.location_id
    LEFT JOIN xaafada x ON p.xaafada_id = x.id
    WHERE x.xaafada = 'Sheekh Cali'
    LIMIT 6
";
                            $result1 = mysqli_query($conn, $query1);

                            if (mysqli_num_rows($result1) > 0) {
                                while ($row = mysqli_fetch_assoc($result1)) {
                            ?>
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <img src="<?php echo $row['image']; ?>" class="img-fluid w-100 rounded-top" alt="<?php echo $row['name']; ?>">
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;"><?php echo $row['name']; ?></div>
                                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                <h4><?php echo $row['name']; ?></h4>
                                                <p><?php echo $row['town'] . ', ' . $row['xaafada']; ?></p>
                                                <div class="d-flex justify-content-between flex-lg-wrap align-items-center">
                                                    <p class="text-dark fs-5 fw-bold mb-0 text-center">$<?php echo $row['price_per_hour']; ?> / hour</p>
                                                    <a href="./hire.php" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i>Kirayso</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo "Ma jiro Garoono xaafadan ku yaala oo diwaan gashan";
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
            <div id="tab-3" class="tab-pane fade show p-0">
                <div class="row g-4">
                    <div class="col-lg-12">
                        <div class="row g-4">
                            <?php
                            $query2 = "
    SELECT p.*, l.town, x.xaafada
    FROM pitches p
    LEFT JOIN locations l ON p.location_id = l.location_id
    LEFT JOIN xaafada x ON p.xaafada_id = x.id
    WHERE x.xaafada = 'Sheekh Cismaan'
    LIMIT 6
";
                            $result2 = mysqli_query($conn, $query2);

                            if (mysqli_num_rows($result2) > 0) {
                                while ($row = mysqli_fetch_assoc($result2)) {
                            ?>
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <img src="<?php echo $row['image']; ?>" class="img-fluid w-100 rounded-top" alt="<?php echo $row['name']; ?>">
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;"><?php echo $row['name']; ?></div>
                                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                <h4><?php echo $row['name']; ?></h4>
                                                <p><?php echo $row['town'] . ', ' . $row['xaafada']; ?></p>
                                                <div class="d-flex justify-content-between flex-lg-wrap align-items-center">
                                                    <p class="text-dark fs-5 fw-bold mb-0 text-center">$<?php echo $row['price_per_hour']; ?> / hour</p>
                                                    <a href="./hire.php" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i>Kirayso</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo "Ma jiro Garoono xaafadan ku yaala oo diwaan gashan";
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
            <div id="tab-4" class="tab-pane fade show p-0">
                <div class="row g-4">
                    <div class="col-lg-12">
                        <div class="row g-4">
                            <?php
                            $query3 = "
    SELECT p.*, l.town, x.xaafada
    FROM pitches p
    LEFT JOIN locations l ON p.location_id = l.location_id
    LEFT JOIN xaafada x ON p.xaafada_id = x.id
    WHERE x.xaafada = 'Jarka'
    LIMIT 6
";
                            $result3 = mysqli_query($conn, $query3);

                            if (mysqli_num_rows($result3) > 0) {
                                while ($row = mysqli_fetch_assoc($result3)) {
                            ?>
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <img src="<?php echo $row['image']; ?>" class="img-fluid w-100 rounded-top" alt="<?php echo $row['name']; ?>">
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;"><?php echo $row['name']; ?></div>
                                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                <h4><?php echo $row['name']; ?></h4>
                                                <p><?php echo $row['town'] . ', ' . $row['xaafada']; ?></p>
                                                <div class="d-flex justify-content-between flex-lg-wrap align-items-center">
                                                    <p class="text-dark fs-5 fw-bold mb-0 text-center">$<?php echo $row['price_per_hour']; ?> / hour</p>
                                                    <a href="./hire.php" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i>Kirayso</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo "Ma jiro Garoono xaafadan ku yaala oo diwaan gashan";
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
            <div id="tab-5" class="tab-pane fade show p-0">
                <div class="row g-4">
                    <div class="col-lg-12">
                        <div class="row g-4">
                            <?php
                            $query4 = "
    SELECT p.*, l.town, x.xaafada
    FROM pitches p
    LEFT JOIN locations l ON p.location_id = l.location_id
    LEFT JOIN xaafada x ON p.xaafada_id = x.id
    WHERE x.xaafada = 'Cali Black'
    LIMIT 6
";
                            $result4 = mysqli_query($conn, $query4);

                            if (mysqli_num_rows($result4) > 0) {
                                while ($row = mysqli_fetch_assoc($result4)) {
                            ?>
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <img src="<?php echo $row['image']; ?>" class="img-fluid w-100 rounded-top" alt="<?php echo $row['name']; ?>">
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;"><?php echo $row['name']; ?></div>
                                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                <h4><?php echo $row['name']; ?></h4>
                                                <p><?php echo $row['town'] . ', ' . $row['xaafada']; ?></p>
                                                <div class="d-flex justify-content-between flex-lg-wrap align-items-center">
                                                    <p class="text-dark fs-5 fw-bold mb-0 text-center">$<?php echo $row['price_per_hour']; ?> / hour</p>
                                                    <a href="./hire.php" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i>Kirayso</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo "Ma jiro Garoono xaafadan ku yaala oo diwaan gashan";
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- Garoonada nav bars-->



<!-- Include jQuery and Owl Carousel JS & CSS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<script>
    $(document).ready(function() {
        $('.owl-carousel').owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 5
                }
            }
        });
    });
</script>

<style>
    .owl-carousel .item {
        margin: 3px;
    }

    .owl-carousel .item img {
        display: block;
        width: 100%;
        height: auto;
    }
</style>

<!-- Banner Section Start-->
<div class="container-fluid banner bg-secondary my-5">
    <div class="container py-5">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <div class="py-4">
                    <h1 class="display-3 text-white">Raadi oo hel Garoon</h1>
                    <p class="fw-normal display-3 text-dark mb-4">Kugu Haboon</p>
                    <p class="mb-4 text-dark">Garoono.com waxaad ka helaysaa garoonada ugu fiican ee kuuugu dhaw adiga oo ku kiraysan kara hal gujin </p>
                    <a href="stadiums" class="banner-btn btn border-2 border-white rounded-pill text-dark py-3 px-5">Kirayso</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="position-relative">
                    <img src="img/baner-1.png" class="img-fluid w-100 rounded" alt="">
                    <div class="d-flex align-items-center justify-content-center bg-white rounded-circle position-absolute" style="width: 140px; height: 140px; top: 0; left: 0;">
                        <h1 style="font-size: 100px;">24</h1>
                        <div class="d-flex flex-column">
                            <span class="h2 mb-0">/</span>
                            <span class="h4 text-muted mb-0">7</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Banner Section End -->
<!-- Bestsaler Product Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="text-center mx-auto mb-5" style="max-width: 700px;">
            <h1 class="display-4">Garoonada Banaan</h1>
            <p></p>
        </div>
        <div class="row g-4">
            <?php
            // SQL query to fetch data
            $available = "SELECT p.*, l.town, x.xaafada
                FROM pitches p
                LEFT JOIN locations l ON p.location_id = l.location_id
                LEFT JOIN xaafada x ON p.xaafada_id = x.id
                LIMIT 10;";
            $result_av = mysqli_query($conn, $available);

            if (mysqli_num_rows($result_av) > 0) {
                while ($row_av = mysqli_fetch_assoc($result_av)) {
            ?>
                    <div class="col-lg-6 col-xl-4">
                        <div class="p-4 rounded bg-light">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <img src="<?php echo $row_av['image']; ?>" class="img-fluid rounded-circle w-100" alt="">
                                </div>
                                <div class="col-6">
                                    <a href="#" class="h5"><?php echo $row_av['name']; ?></a>
                                    <div class="d-flex">
                                        <p><?php echo $row_av['town'] . ', ' . $row_av['xaafada']; ?></p>
                                    </div>
                                    <h4 class="mb-2"><?php echo $row_av['price_per_hour']; ?></h4>
                                    <form action="hire" method="post">
                                        <input type="hidden" name="single-pitch" value="<?php echo $row_av['pitch_id']; ?>">
                                        <button type="submit" name="submit" class="btn border border-secondary rounded-pill px-3 text-primary">
                                            <i class="fa fa-shopping-bag me-2 text-primary"></i>Kirayso
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } ?>
        </div>
    </div>
</div>
<!-- numbers starts here -->
<div class="container-fluid py-5">
    <?php
    // Assume $conn is your database connection object

    // Query to get the count of approved stadiums
    $sql_stadiums = "SELECT COUNT(*) AS stadiums_managed FROM pitches WHERE aproved = 1";
    $result_stadiums = $conn->query($sql_stadiums);
    $row_stadiums = $result_stadiums->fetch_assoc();
    $stadiums_managed = $row_stadiums['stadiums_managed'];

    // Query to get the count of regular users
    $sql_customers = "SELECT COUNT(*) AS happy_customers FROM users WHERE user_type = 'regular'";
    $result_customers = $conn->query($sql_customers);
    $row_customers = $result_customers->fetch_assoc();
    $happy_customers = $row_customers['happy_customers'];

    // Query to get the count of successful rentals
    $sql_rentals = "SELECT COUNT(*) AS successful_rentals FROM bookings WHERE booking_status = 'confirmed'";
    $result_rentals = $conn->query($sql_rentals);
    $row_rentals = $result_rentals->fetch_assoc();
    $successful_rentals = $row_rentals['successful_rentals'];

    // Query to get the service quality ratio
    $sql_service_quality = "
    SELECT 
        (SELECT COUNT(*) FROM bookings WHERE booking_status IN ('cancelled', 'ma iman')) / 
        (SELECT COUNT(*) FROM bookings WHERE booking_status = 'confirmed') * 100 AS service_quality
";
    $result_service_quality = $conn->query($sql_service_quality);
    $row_service_quality = $result_service_quality->fetch_assoc();
    $service_quality = $row_service_quality['service_quality'];
    ?>

    <div class="container">
        <div class="bg-light p-5 rounded">
            <div class="row g-4 justify-content-center">
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="counter bg-white rounded p-5">
                        <i class="fa fa-building text-secondary"></i>
                        <h4>Tirada Garoonada</h4>
                        <h1><?php echo $stadiums_managed; ?></h1>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="counter bg-white rounded p-5">
                        <i class="fa fa-users text-secondary"></i>
                        <h4>Macaamiisha Loo shaqeeyay</h4>
                        <h1><?php echo $happy_customers; ?></h1>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="counter bg-white rounded p-5">
                        <i class="fa fa-handshake text-secondary"></i>
                        <h4>Kiraynta guulaysatay</h4>
                        <h1><?php echo $successful_rentals; ?></h1>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="counter bg-white rounded p-5">
                        <i class="fa fa-thumbs-up text-secondary"></i>
                        <h4>Tayada Adeegyada</h4>
                        <h1><?php echo round($service_quality, 2); ?>%</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- numbers ends here -->
<div class="container-fluid testimonial py-5">
    <div class="container py-5">
        <div class="testimonial-header text-center">
            <h4 class="text-primary">Our Testimonial</h4>
            <h1 class="display-5 mb-5 text-dark">Our Client Saying!</h1>
        </div>
        <div class="owl-carousel testimonial-carousel">
            <div class="testimonial-item img-border-radius bg-light rounded p-4">
                <div class="position-relative">
                    <i class="fa fa-quote-right fa-2x text-secondary position-absolute" style="bottom: 30px; right: 0;"></i>
                    <div class="mb-4 pb-4 border-bottom border-secondary">
                        <p class="mb-0">Lorem Ipsum is simply dummy text of the printing Ipsum has been the industry's standard dummy text ever since the 1500s,
                        </p>
                    </div>
                    <div class="d-flex align-items-center flex-nowrap">
                        <div class="bg-secondary rounded">
                            <img src="img/testimonial-1.jpg" class="img-fluid rounded" style="width: 100px; height: 100px;" alt="">
                        </div>
                        <div class="ms-4 d-block">
                            <h4 class="text-dark">Client Name</h4>
                            <p class="m-0 pb-3">Profession</p>
                            <div class="d-flex pe-5">
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="testimonial-item img-border-radius bg-light rounded p-4">
                <div class="position-relative">
                    <i class="fa fa-quote-right fa-2x text-secondary position-absolute" style="bottom: 30px; right: 0;"></i>
                    <div class="mb-4 pb-4 border-bottom border-secondary">
                        <p class="mb-0">Lorem Ipsum is simply dummy text of the printing Ipsum has been the industry's standard dummy text ever since the 1500s,
                        </p>
                    </div>
                    <div class="d-flex align-items-center flex-nowrap">
                        <div class="bg-secondary rounded">
                            <img src="img/testimonial-1.jpg" class="img-fluid rounded" style="width: 100px; height: 100px;" alt="">
                        </div>
                        <div class="ms-4 d-block">
                            <h4 class="text-dark">Client Name</h4>
                            <p class="m-0 pb-3">Profession</p>
                            <div class="d-flex pe-5">
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="testimonial-item img-border-radius bg-light rounded p-4">
                <div class="position-relative">
                    <i class="fa fa-quote-right fa-2x text-secondary position-absolute" style="bottom: 30px; right: 0;"></i>
                    <div class="mb-4 pb-4 border-bottom border-secondary">
                        <p class="mb-0">Lorem Ipsum is simply dummy text of the printing Ipsum has been the industry's standard dummy text ever since the 1500s,
                        </p>
                    </div>
                    <div class="d-flex align-items-center flex-nowrap">
                        <div class="bg-secondary rounded">
                            <img src="img/testimonial-1.jpg" class="img-fluid rounded" style="width: 100px; height: 100px;" alt="">
                        </div>
                        <div class="ms-4 d-block">
                            <h4 class="text-dark">Client Name</h4>
                            <p class="m-0 pb-3">Profession</p>
                            <div class="d-flex pe-5">
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require 'includes/footer.php'; ?>