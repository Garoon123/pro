<?php
session_start();
require 'includes/conn.php';

$error = '';
if (isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $query = "SELECT * FROM users WHERE remember_token=? AND token_expires > NOW()";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, "s", $token);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result && mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['location'] = $user['location_id'];

            if ($user_type == 'admin') {
                header("Location: admin_dashboard");
                exit();
            } else {
                header("Location: admin_dashboard");
                exit();
            }
        }
    }
}

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $email = mysqli_real_escape_string($conn, $email);
    $query = "SELECT * FROM users WHERE username=?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result && mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['location'] = $user['location_id'];
                if (isset($_POST['remember_me']) && $_POST['remember_me'] == 'on') {
                    $token = bin2hex(random_bytes(16));
                    $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
                    $query = "UPDATE users SET remember_token=?, token_expires=? WHERE user_id=?";
                    $stmt = mysqli_stmt_init($conn);
                    if (mysqli_stmt_prepare($stmt, $query)) {
                        mysqli_stmt_bind_param($stmt, "ssi", $token, $expires, $user['user_id']);
                        mysqli_stmt_execute($stmt);
                    }
                    setcookie('remember_token', $token, time() + (86400 * 30), "/"); // 30 days
                }
                if ($_SESSION['user_type'] == 'admin') {
                    header('Location: admin_dashboard');
                } else {
                    header("Location: index");
                    exit();
                }
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Failed to prepare the SQL statement.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Garoon Kireeya</title>
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
    <main class="main" id="top">
        <div class="container-fluid">
            <div class="row min-vh-100 flex-center g-0">
                <div class="col-12 d-flex justify-content-center align-items-center">
                    <div class="card overflow-hidden">
                        <div class="card-body p-4">
                            <h3 class="text-center mb-4">Account Login</h3>
                            <?php if (!empty($error)) : ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $error; ?>
                                </div>
                            <?php endif; ?>
                            <form action="login" method="post">
                                <div class="mb-3">
                                    <label class="form-label" for="card-email">Username</label>
                                    <input class="form-control" name="email" id="card-email" type="text" required />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="card-password">Password</label>
                                    <input class="form-control" name="password" id="card-password" type="password" required />
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember_me" id="card-checkbox" checked="checked" />
                                        <label class="form-check-label" for="card-checkbox">Remember me</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary d-block w-100 mt-3" type="submit" name="submit">Log in</button>
                                </div>
                            </form>
                            <div class="text-center dont-have">Don't have an account? <a href="register">Register Now</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php require 'includes/footer.php'; ?>
</body>

</html>