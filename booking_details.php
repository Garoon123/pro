<?php
require 'includes/header.php';
require 'includes/conn.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login');
    exit();
}
// Fetch locations and pitches data
$sql = "
    SELECT p.*, l.town 
    FROM pitches p 
    JOIN locations l ON p.location_id = l.location_id
";
$result = $conn->query($sql);
?>

<style>
    .calendar {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 20px;
    }

    .day {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        min-width: 150px;
    }

    .day h3 {
        margin-bottom: 10px;
    }

    .slot {
        border: 1px solid #ccc;
        padding: 10px;
        width: 100%;
        text-align: center;
        box-sizing: border-box;
    }

    .booked {
        background-color: #f0f0f0;
    }

    .available {
        background-color: #e0ffe0;
        border-color: #4CAF50;
    }

    @media (max-width: 600px) {
        .container {
            width: 100%;
            padding: 10px;
        }

        .calendar {
            flex-direction: column;
            align-items: center;
        }

        .day {
            width: 100%;
            max-width: 300px;
        }
    }
</style>

<div class="container-fluid py-5 mt-5">
    <div class="container py-5">
        <div class="row g-4 mb-5">
            <div class="col-lg-8 col-xl-9">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="border rounded">
                            <a href="#">
                                <img src="img/single-item.jpg" class="img-fluid rounded" alt="Image">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h4 class="fw-bold mb-3">Brocoli</h4>
                        <p class="mb-3">Category: Vegetables</p>
                        <h5 class="fw-bold mb-3">3,35 $</h5>
                        <div class="d-flex mb-4">
                            <i class="fa fa-star text-secondary"></i>
                            <i class="fa fa-star text-secondary"></i>
                            <i class="fa fa-star text-secondary"></i>
                            <i class="fa fa-star text-secondary"></i>
                            <i class="fa fa-star"></i>
                        </div>
                        <p class="mb-4">The generated Lorem Ipsum is therefore always free from repetition injected humour, or non-characteristic words etc.</p>
                        <p class="mb-4">Susp endisse ultricies nisi vel quam suscipit. Sabertooth peacock flounder; chain pickerel hatchetfish, pencilfish snailfish</p>
                        <div class="input-group quantity mb-5" style="width: 100px;">
                            <div class="input-group-btn">
                                <button class="btn btn-sm btn-minus rounded-circle bg-light border">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                            <input type="text" class="form-control form-control-sm text-center border-0" value="1">
                            <div class="input-group-btn">
                                <button class="btn btn-sm btn-plus rounded-circle bg-light border">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <a href="#" class="btn border border-secondary rounded-pill px-4 py-2 mb-4 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                    </div>
                    <div class="col-lg-12">
                        <nav>
                            <div class="nav nav-tabs mb-3">
                                <button class="nav-link active border-white border-bottom-0" type="button" role="tab" id="nav-about-tab" data-bs-toggle="tab" data-bs-target="#nav-about" aria-controls="nav-about" aria-selected="true">Description</button>
                                <button class="nav-link border-white border-bottom-0" type="button" role="tab" id="nav-mission-tab" data-bs-toggle="tab" data-bs-target="#nav-mission" aria-controls="nav-mission" aria-selected="false">Reviews</button>
                            </div>
                        </nav>
                        <div class="tab-content mb-5">
                            <div class="tab-pane active" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                                <div class="container">
                                    <div class="calendar">
                                        <div class="day">
                                            <h3>Wed 22nd</h3>
                                            <div class="slot booked">6:55 PM<br>Booked<br>45 min</div>
                                            <div class="slot booked">7:40 PM<br>Booked<br>45 min</div>
                                            <div class="slot booked">8:25 PM<br>Booked<br>45 min</div>
                                            <div class="slot booked">9:10 PM<br>Booked<br>45 min</div>
                                        </div>
                                        <div class="day">
                                            <h3>Thu 23rd</h3>
                                            <div class="slot booked">6:10 PM<br>Booked<br>45 min</div>
                                            <div class="slot booked">6:55 PM<br>Booked<br>45 min</div>
                                            <div class="slot booked">7:40 PM<br>Booked<br>45 min</div>
                                            <div class="slot booked">8:25 PM<br>Booked<br>45 min</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="nav-mission" role="tabpanel" aria-labelledby="nav-mission-tab">
                                <div class="d-flex">
                                    <img src="img/avatar.jpg" class="img-fluid rounded-circle p-3" style="width: 100px; height: 100px;" alt="">
                                    <div class="">
                                        <p class="mb-2" style="font-size: 14px;">April 12, 2024</p>
                                        <div class="d-flex justify-content-between">
                                            <h5>Jason Smith</h5>
                                            <div class="d-flex mb-3">
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star"></i>
                                            </div>
                                        </div>
                                        <p>The generated Lorem Ipsum is therefore always free from repetition injected humour, or non-characteristic words etc. Susp endisse ultricies nisi vel quam suscipit </p>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <img src="img/avatar.jpg" class="img-fluid rounded-circle p-3" style="width: 100px; height: 100px;" alt="">
                                    <div class="">
                                        <p class="mb-2" style="font-size: 14px;">April 12, 2024</p>
                                        <div class="d-flex justify-content-between">
                                            <h5>Sam Peters</h5>
                                            <div class="d-flex mb-3">
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                            </div>
                                        </div>
                                        <p class="text-dark">The generated Lorem Ipsum is therefore always free from repetition injected humour, or non-characteristic words etc. Susp endisse ultricies nisi vel quam suscipit </p>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="nav-vision" role="tabpanel">
                                <p class="text-dark">Tempor erat elitr rebum at clita. Diam dolor diam ipsum et tempor sit. Aliqu diam amet diam et eos labore. 3</p>
                                <p class="mb-0">Diam dolor diam ipsum et tempor sit. Aliqu diam amet diam et eos labore. Clita erat ipsum et lorem et sit</p>
                            </div>
                        </div>
                    </div>

                    <form action="#">
                        <h4 class="mb-5 fw-bold">Leave a Reply</h4>
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="border-bottom rounded">
                                    <input type="text" class="form-control border-0 me-4" placeholder="Your Name *">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="border-bottom rounded">
                                    <input type="email" class="form-control border-0" placeholder="Your Email *">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="border-bottom rounded my-3">
                                    <input type="text" class="form-control border-0" placeholder="Your Website *">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="border-bottom rounded my-3">
                                    <textarea class="form-control border-0" rows="3" placeholder="Your Comment *"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <a href="#" class="btn border border-secondary rounded-pill px-4 py-2 mb-4 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Post Comment</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-4 col-xl-3">
                <div class="d-flex align-items-center mb-5">
                    <input type="text" class="form-control me-2" placeholder="Search">
                    <button class="btn btn-primary"><i class="fa fa-search"></i></button>
                </div>
                <h4 class="fw-bold mb-4">Related</h4>
                <?php while ($pitch = $result->fetch_assoc()) { ?>
                    <div class="border rounded p-4 mb-4">
                        <h5 class="fw-bold"><?php echo htmlspecialchars($pitch['name']); ?></h5>
                        <p class="text-dark">Location: <?php echo htmlspecialchars($pitch['town']); ?></p>
                        <p class="text-dark">Type: <?php echo htmlspecialchars($pitch['type']); ?></p>
                        <p class="text-dark">Price per hour: $<?php echo htmlspecialchars($pitch['price_per_hour']); ?></p>
                        <p class="text-dark">Rating: <?php echo htmlspecialchars($pitch['rating']); ?>/5</p>
                        <p class="text-dark">Availability: <?php echo $pitch['availability_status'] ? 'Available' : 'Not Available'; ?></p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php
require 'includes/footer.php';
?>