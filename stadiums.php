<?php
session_start();
require 'includes/conn.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login');
    exit();
}
require './includes/header.php';
?>
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Search by keyword</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex align-items-center">
                <div class="input-group w-75 mx-auto d-flex">
                    <input id="search-input" type="search" class="form-control p-3" placeholder="Search..." aria-describedby="search-icon-1">
                    <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Search End -->
<?php
$query_xaafada = "SELECT DISTINCT * FROM xaafada";
$result_xaafada = mysqli_query($conn, $query_xaafada);

// Fetch all pitches initially
$query_pitches = "SELECT DISTINCT p.*, l.town, x.xaafada 
FROM pitches p 
LEFT JOIN locations l ON p.location_id = l.location_id 
LEFT JOIN xaafada x ON p.xaafada_id = x.id";
$result_pitches = mysqli_query($conn, $query_pitches);
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
                    <a href="index" class="nav-item nav-link">Home</a>
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
<!-- Navbar End -->
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
<div class="container-fluid fruite py-5 align-items-center">
    <div class="container py-5 align-items-center">
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-lg-6">
                    <h1 class="mb-4">Garoonada Diyaarka ah </h1>
                </div>
                <div class="col-lg-6">
                    <div class="row g-3 align-items-center">
                        <div class="col-xl- col-lg-12">
                            <div class="bg-light ps-3 py-3 rounded d-flex justify-content-between mb-4">
                                <label for="xaafada">Xaafada:</label>
                                <select id="xaafada" name="xaafada" class="border-0 form-select-sm bg-light me-3" onchange="filterPitches()">
                                    <option value="">Dhamaan</option>
                                    <?php while ($row = mysqli_fetch_assoc($result_xaafada)) { ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['xaafada']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-9">
                <div class="row g-4 justify-content-center" id="pitches-container">
                    <?php if (mysqli_num_rows($result_pitches) > 0) {
                        while ($row = mysqli_fetch_assoc($result_pitches)) { ?>
                            <div class="col-md-4 col-lg-4 col-xl-4">
                                <div class="rounded position-relative fruite-item">
                                    <div class="fruite-img">
                                        <img src="<?php echo $row['image']; ?>" class="img-fluid w-100 rounded-top" alt="">
                                    </div>
                                    <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;"><?php echo $row['name']; ?></div>
                                    <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                        <h4 class="fruite-name"><?php echo $row['name']; ?></h4>
                                        <p class="fruite-description"><?php
                                                                        $description = $row['description'];
                                                                        $words = explode(' ', $description);
                                                                        if (count($words) > 13) {
                                                                            $shortened_description = implode(' ', array_slice($words, 0, 13)) . '...';
                                                                        } else {
                                                                            $shortened_description = $description;
                                                                        }
                                                                        echo htmlspecialchars($shortened_description); ?></p>
                                        <div class="d-flex justify-content-between flex-lg-wrap">
                                            <p class="text-dark fs-5 fw-bold mb-0"><?php echo $row['price_per_hour']; ?>/ Team</p>
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
                    <?php }
                    } else {
                        echo "No pitches found.";
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('xaafada').addEventListener('change', function() {
        var xaafada = this.value;

        fetch('get_pitches.php?xaafada=' + xaafada)
            .then(response => response.json())
            .then(data => {
                var pitchesContainer = document.getElementById('pitches-container');
                pitchesContainer.innerHTML = '';

                if (data.length === 0) {
                    pitchesContainer.innerHTML = '<p py-5>Xaafadan ma jiro garoon ka diwaan gashan.</p>';
                } else {
                    data.forEach(pitch => {
                        var pitchElement = document.createElement('div');
                        pitchElement.className = 'col-md-6 col-lg-6 col-xl-4';
                        pitchElement.innerHTML = `
                            <div class="rounded position-relative fruite-item">
                                <div class="fruite-img">
                                    <img src="${pitch.image}" class="img-fluid w-100 rounded-top" alt="">
                                </div>
                                <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">${pitch.name}</div>
                                <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                    <h4>${pitch.name}</h4>
                                    <p>${pitch.description}</p>
                                    <div class="d-flex justify-content-between flex-lg-wrap">
                                        <p class="text-dark fs-5 fw-bold mb-0">${pitch.price_per_hour}/ Team</p>
                                        <a href="hire" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i>Kirayso</a>
                                    </div>
                                </div>
                            </div>`;
                        pitchesContainer.appendChild(pitchElement);
                    });
                }
            });
    });
</script>
<?php
require './includes/footer.php';
?>