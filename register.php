<?php
require './includes/conn.php';

// Define variables and initialize with empty values
$full_name = $username = $password = $phone_number = $location = "";
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["full_name"]))) {
        $errorMessage .= "Please enter full name.<br>";
    } else {
        $full_name = trim($_POST["full_name"]);
    }

    if (empty(trim($_POST["phone_number"]))) {
        $errorMessage .= "Please enter phone number.<br>";
    } else {
        $phone_number = trim($_POST["phone_number"]);
    }

    if (empty(trim($_POST["username"]))) {
        $errorMessage .= "Please enter a username.<br>";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["password"]))) {
        $errorMessage .= "Please enter a password.<br>";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $errorMessage .= "Password must have at least 6 characters.<br>";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty(trim($_POST["location"]))) {
        $errorMessage .= "Please select a location.<br>";
    } else {
        $location = trim($_POST["location"]);
    }

    // Check input errors before inserting into database
    if (empty($errorMessage)) {
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password_hash, full_name, phone_number, user_type, location_id) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $param_password_hash = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_user_type = 'regular';

            // Attempt to execute the prepared statement
            if ($stmt->execute([$username, $param_password_hash, $full_name, $phone_number, $param_user_type, $location])) {
                // Redirect to login page
                header("location: login.php");
                exit();
            } else {
                $errorMessage .= "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Register - Arday-kaabe</title>
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

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div class="main-wrapper py-5 justify-content-center">
        <div class="login-wrapper">
            <div class="container">
                <?php if (!empty($errorMessage)) : ?>
                    <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
                <?php endif; ?>
                <div class="loginbox">
                    <div class="login-right">
                        <div class="login-right-wrap">
                            <h1 class="text-center">Register</h1>
                            <p class="account-subtitle text-center">Welcome to Arday-kaabe</p>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                <div class="form-group">
                                    <label class="form-control-label">Full Name</label>
                                    <input class="form-control form-control-lg" type="text" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Phone Number</label>
                                    <input class="form-control form-control-lg" type="text" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Username</label>
                                    <input class="form-control form-control-lg" type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Password</label>
                                    <input class="form-control form-control-lg" type="password" name="password" value="<?php echo htmlspecialchars($password); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Location</label>
                                    <select class="form-control form-control-lg" name="location" required>
                                        <?php
                                        $sql = "SELECT * FROM locations";
                                        $result = $conn->query($sql);

                                        // Assuming $locations is an array of location_id and town fetched from the database
                                        if ($result->num_rows > 0) {
                                            while ($location = $result->fetch_assoc()) {
                                                echo '<option value="' . $location['location_id'] . '">' . $location['town'] . '</option>';
                                            }
                                        }
                                        ?>

                                    </select>
                                </div>
                                <div class="form-group mb-0 m-2 d-flex justify-content-center">
                                    <button class="btn btn-lg btn-block btn-primary" type="submit" name="register">Register</button>
                                </div>
                            </form>
                            <div class="text-center dont-have m-2">Already have an account? <a href="login.php">Login</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>