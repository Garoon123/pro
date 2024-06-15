<?php
session_start();
require './includes/conn.php'; // Include the connection to the database

$full_name = $phone_number = $username = $password = $confirmpassword = $phone_number = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $full_name = htmlspecialchars($_POST['full_name']);
    $phone_number = htmlspecialchars($_POST['phone_number']);
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $confirmpassword = htmlspecialchars($_POST['confirmpassword']);

    // Validate input
    if (empty($username)) {
        $errors[] = 'Username is required';
    }
    if (empty($password) || empty($confirmpassword) || $password !== $confirmpassword) {
        $errors[] = 'Passwords do not match';
    }

    if (empty($errors)) {
        // Store user data in session temporarily
        $_SESSION['full_name'] = $full_name;
        $_SESSION['phone_number'] = $phone_number;
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;

        // Insert user into the database
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (username,  password_hash, full_name,  user_type) VALUES (?, ?, ?,   'Admin')");
        $stmt->bind_param("sss", $username,  $password_hash, $full_name);

        if ($stmt->execute()) {
            // Store the user_id in the session
            $_SESSION['user_id'] = $stmt->insert_id;

            // Redirect to pitch registration
            header("Location: pitch_registration.php");
            exit();
        } else {
            $errors[] = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>User Registration</title>
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
                            <h1 class="text-center">User Registration</h1>
                            <p class="account-subtitle text-center">Enter your personal information</p>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                <div class="form-group">
                                    <label class="form-control-label">Full Name</label>
                                    <input class="form-control form-control-lg" type="text" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>">
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label">Username</label>
                                    <input class="form-control form-control-lg" type="text" name="username" value="<?php echo htmlspecialchars($username); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Password</label>
                                    <input class="form-control form-control-lg" type="password" name="password" value="<?php echo htmlspecialchars($password); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Confirm Password</label>
                                    <input class="form-control form-control-lg" type="password" name="confirmpassword" value="<?php echo htmlspecialchars($confirmpassword); ?>">
                                </div>
                                <div class="form-group mb-0 m-2 d-flex justify-content-center">
                                    <button class="btn btn-lg btn-block btn-primary" type="submit">Next</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>