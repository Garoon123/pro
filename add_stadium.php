<?php
session_start();
require './includes/conn.php'; // Include the connection to the database

$full_name = $phone_number = $username = $password = $confirmpassword = $name = $type = $price_per_hour = $location_id = $xaafada_id = $image_path = "";
$errors = [];

// Fetch locations
$locations = [];
$sql = "SELECT location_id, town FROM locations";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $locations[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize user input
    $full_name = htmlspecialchars($_POST['full_name']);
    $phone_number = htmlspecialchars($_POST['phone_number']);
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $confirmpassword = htmlspecialchars($_POST['confirmpassword']);

    // Collect and sanitize pitch input
    $name = htmlspecialchars($_POST['name']);
    $location_id = htmlspecialchars($_POST['location_id']);
    $xaafada_id = htmlspecialchars($_POST['xaafada_id']);
    $type = htmlspecialchars($_POST['type']);
    $price_per_hour = htmlspecialchars($_POST['price_per_hour']);

    // Validate user input
    if (empty($username)) {
        $errors[] = 'Username is required';
    }

    if (empty($password) || empty($confirmpassword) || $password !== $confirmpassword) {
        $errors[] = 'Passwords do not match';
    }

    // Check if username already exists
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $errors[] = 'Username already exists';
    }
    $stmt->close();

    // Image upload and conversion
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $original_filename = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
        $new_filename = $original_filename . '_' . session_id() . '.png'; // Use session ID instead of user ID for unique naming
        $upload_dir = 'img/';

        // Ensure the directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $image_path = $upload_dir . $new_filename;
        $tmp_path = $_FILES['image']['tmp_name'];

        // Check the mime type to determine the original image format
        $mime_type = mime_content_type($tmp_path);
        switch ($mime_type) {
            case 'image/jpeg':
                $image_resource = imagecreatefromjpeg($tmp_path);
                break;
            case 'image/png':
                $image_resource = imagecreatefrompng($tmp_path);
                break;
            case 'image/gif':
                $image_resource = imagecreatefromgif($tmp_path);
                break;
            default:
                $errors[] = "Unsupported image format.";
                $image_resource = false;
        }

        // Crop and resize the image to 500x350
        if ($image_resource) {
            $cropped_image = imagecreatetruecolor(500, 350);
            $original_width = imagesx($image_resource);
            $original_height = imagesy($image_resource);
            imagecopyresampled($cropped_image, $image_resource, 0, 0, 0, 0, 500, 350, $original_width, $original_height);

            if (!imagepng($cropped_image, $image_path)) {
                $errors[] = "Failed to save the file as PNG.";
            }

            imagedestroy($image_resource);
            imagedestroy($cropped_image);
        }
    } else {
        $errors[] = "No image uploaded or upload error.";
    }

    if (empty($errors)) {
        // Insert user into the database
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (username, password_hash, full_name, phone_number, user_type) VALUES (?, ?, ?, ?, 'admin')");
        $stmt->bind_param("ssss", $username, $password_hash, $full_name, $phone_number);

        if ($stmt->execute()) {
            // Store the user_id in the session
            $user_id = $stmt->insert_id;
            $_SESSION['user_id'] = $user_id;

            // Insert pitch into the database
            $stmt_pitch = $conn->prepare("INSERT INTO pitches (name, location_id, xaafada_id, type, price_per_hour, image, phone_number) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt_pitch->bind_param("siisdss", $name, $location_id, $xaafada_id, $type, $price_per_hour, $image_path, $phone_number);

            if ($stmt_pitch->execute()) {
                // Get the pitch_id of the newly inserted pitch
                $pitch_id = $stmt_pitch->insert_id;

                // Update the users table with the pitch_id
                $stmt_update_user = $conn->prepare("UPDATE users SET pitch_id = ? WHERE user_id = ?");
                $stmt_update_user->bind_param("ii", $pitch_id, $user_id);

                if ($stmt_update_user->execute()) {
                    header('Location: login');
                    exit();
                } else {
                    $errors[] = "Error updating user with pitch_id: " . $stmt_update_user->error;
                }

                $stmt_update_user->close();
            } else {
                $errors[] = "Error inserting pitch: " . $stmt_pitch->error;
            }

            $stmt_pitch->close();
        } else {
            $errors[] = "Error inserting user: " . $stmt->error;
        }

        $stmt->close();
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>User & Pitch Registration</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div class="main-wrapper py-5 justify-content-center">
        <div class="login-wrapper">
            <div class="container">
                <?php if (!empty($errors)) : ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error) : ?>
                            <p><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="loginbox">
                    <div class="login-right">
                        <div class="login-right-wrap">
                            <h1 class="text-center">User & Pitch Registration</h1>
                            <p class="account-subtitle text-center">Enter your information</p>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                                <h2>User Information</h2>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-control-label">Full Name</label>
                                        <input class="form-control form-control-lg" type="text" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-control-label">Phone Number</label>
                                        <input class="form-control form-control-lg" type="text" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-control-label">Username</label>
                                        <input class="form-control form-control-lg" type="text" name="username" value="<?php echo htmlspecialchars($username); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-control-label">Password</label>
                                        <input class="form-control form-control-lg" type="password" name="password" value="<?php echo htmlspecialchars($password); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-control-label">Confirm Password</label>
                                        <input class="form-control form-control-lg" type="password" name="confirmpassword" value="<?php echo htmlspecialchars($confirmpassword); ?>">
                                    </div>
                                </div>

                                <h2>Pitch Information</h2>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-control-label">Pitch Name</label>
                                        <input class="form-control form-control-lg" type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-control-label">City</label>
                                        <select class="form-control form-control-lg" name="location_id" id="location_id">
                                            <option value="">Select City</option>
                                            <?php foreach ($locations as $location) : ?>
                                                <option value="<?php echo $location['location_id']; ?>" <?php echo $location_id == $location['location_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($location['town']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-control-label">Neighborhood</label>
                                        <select class="form-control form-control-lg" name="xaafada_id" id="xaafada_id">
                                            <option value="">Select Neighborhood</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-control-label">Pitch Type</label>
                                        <select class="form-control form-control-lg" name="type">
                                            <option value="5-a-side" <?php echo $type == '5-a-side' ? 'selected' : ''; ?>>5-a-side</option>
                                            <option value="7-a-side" <?php echo $type == '7-a-side' ? 'selected' : ''; ?>>7-a-side</option>
                                            <option value="11-a-side" <?php echo $type == '11-a-side' ? 'selected' : ''; ?>>11-a-side</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-control-label">Price Per Hour</label>
                                        <input class="form-control form-control-lg" type="text" name="price_per_hour" value="<?php echo htmlspecialchars($price_per_hour); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-control-label">Image</label>
                                        <input class="form-control form-control-lg" type="file" name="image">
                                    </div>
                                </div>
                                <div class="form-group text-center mb-0">
                                    <button class="btn btn-primary btn-block" type="submit">Register</button>
                                </div>
                            </form>

                            <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
                            <script>
                                $(document).ready(function() {
                                    $('#location_id').change(function() {
                                        var location_id = $(this).val();
                                        if (location_id != '') {
                                            $.ajax({
                                                url: "fetch_xaafada.php",
                                                method: "GET",
                                                data: {
                                                    location_id: location_id
                                                },
                                                dataType: "json",
                                                success: function(data) {
                                                    $('#xaafada_id').html('<option value="">Select Neighborhood</option>');
                                                    $.each(data, function(key, value) {
                                                        $('#xaafada_id').append('<option value="' + value.id + '">' + value.xaafada + '</option>');
                                                    });
                                                }
                                            });
                                        } else {
                                            $('#xaafada_id').html('<option value="">Select Neighborhood</option>');
                                        }
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <p class="text-muted">Already have an account? <a href="index.php">Login</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.slimscroll.min.js"></script>
    <script src="js/app.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var locationSelect = document.getElementById("location_id");
            var xaafadaSelect = document.getElementById("xaafada_id");

            locationSelect.addEventListener("change", function() {
                var locationId = locationSelect.value;
                xaafadaSelect.innerHTML = '<option value="">Select Neighborhood</option>';

                if (locationId) {
                    fetch('get_xaafada.php?location_id=' + locationId)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(item => {
                                var option = document.createElement("option");
                                option.value = item.xaafada_id;
                                option.text = item.xaafada;
                                xaafadaSelect.appendChild(option);
                            });
                        });
                }
            });
        });
    </script>
</body>

</html>