<?php
session_start();
require './includes/conn.php';

if (!isset($_SESSION['username'])) {
    header("Location: user_registration.php");
    exit();
}

$locations = [];
$sql = "SELECT location_id, town FROM locations";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $locations[] = $row;
    }
}

$name = $type = $price_per_hour = $location_id = $image = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $name = htmlspecialchars($_POST['name']);
    $location_id = htmlspecialchars($_POST['location_id']);
    $xaafada_id = htmlspecialchars($_POST['xaafada_id']);
    $type = htmlspecialchars($_POST['type']);
    $price_per_hour = htmlspecialchars($_POST['price_per_hour']);

    // Image upload and conversion
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $original_filename = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
        $new_filename = $original_filename . '_' . $_SESSION['user_id'] . '.png';
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

        $user_id = $_SESSION['user_id'];
        if ($image) {
            $target_dir = "img/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        }
        $stmt = $conn->prepare("INSERT INTO pitches (user_id, name, location_id, xaafada_id, type, price_per_hour, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isiiids", $user_id, $name, $location_id, $xaafada_id, $type, $price_per_hour, $image_path);
        if ($stmt->execute()) {

            session_unset();
            session_destroy();
            header('location: login.php');
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Pitch Registration</title>
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
                            <h1 class="text-center">Pitch Registration</h1>
                            <p class="account-subtitle text-center">Enter pitch information</p>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                                <div class="form-group m-2">
                                    <label class="form-control-label">Magaca Garoonka</label>
                                    <input class="form-control form-control-lg" type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
                                </div>
                                <div class="form-group m-2">
                                    <label class="form-control-label">Magaalada</label>
                                    <select class="form-control form-control-lg" name="location_id" id="location_id">
                                        <option value="">Dooro Magaalada</option>
                                        <?php foreach ($locations as $location) : ?>
                                            <option value="<?php echo $location['location_id']; ?>" <?php echo $location_id == $location['location_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($location['town']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group m-2">
                                    <label class="form-control-label">Xaafada</label>
                                    <select class="form-control form-control-lg" name="xaafada_id" id="xaafada_id">
                                        <option value="">Select Xaafada</option>
                                    </select>
                                </div>
                                <div class="form-group m-2">
                                    <label class="form-control-label">Phone Number</label>
                                    <input class="form-control form-control-lg" type="text" name="phone_number">
                                </div>
                                <div class="form-group m-2">
                                    <label class="form-control-label">Tirada ciyaartoyga</label>
                                    <select class="form-control form-control-lg" name="type">
                                        <option value="5-a-side" <?php echo $type == '5-a-side' ? 'selected' : ''; ?>>5</option>
                                        <option value="7-a-side" <?php echo $type == '7-a-side' ? 'selected' : ''; ?>>7</option>
                                        <option value="11-a-side" <?php echo $type == '11-a-side' ? 'selected' : ''; ?>>11</option>
                                    </select>
                                </div>
                                <div class="form-group m-2">
                                    <label class="form-control-label">Price per Hour</label>
                                    <input class="form-control form-control-lg" type="text" name="price_per_hour" value="<?php echo htmlspecialchars($price_per_hour); ?>">
                                </div>
                                <div class="form-group m-2">
                                    <label class="form-control-label">Image</label>
                                    <input class="form-control form-control-lg" type="file" name="image">
                                </div>
                                <div class="form-group mb-0 m-2 d-flex justify-content-center">
                                    <button class="btn btn-lg btn-block btn-primary" type="submit" name="register">Register Pitch</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
                            $('#xaafada_id').html('<option value="">Select Xaafada</option>');
                            $.each(data, function(key, value) {
                                $('#xaafada_id').append('<option value="' + value.id + '">' + value.xaafada + '</option>');
                            });
                        }
                    });
                } else {
                    $('#xaafada_id').html('<option value="">Select Xaafada</option>');
                }
            });
            // Trigger change event to populate Xaafada dropdown if location_id is set
            var location_id = $('#location_id').val();
            if (location_id) {
                $('#location_id').trigger('change');
            }
        });
    </script>
</body>

</html>