<?php
// Assuming you have a user ID available
$user_id = 123; // Replace this with the actual user ID

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $original_filename = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
    $new_filename = $original_filename . '_' . $user_id . '.png';
    $upload_dir = 'img/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $image = $upload_dir . $new_filename;
    $tmp_path = $_FILES['image']['tmp_name'];

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
            echo "Unsupported image format.";
            exit;
    }

    if (!imagepng($image_resource, $image)) {
        echo "Failed to save the file as PNG.";
    }
    imagedestroy($image_resource);
} else {
    echo "No file uploaded.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Upload Test</title>
</head>

<body>
    <h1>Upload an Image</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="image">Choose an image:</label>
        <input type="file" name="image" id="image" accept="image/*" required>
        <input type="submit" value="Upload Image">
    </form>
</body>

</html>