<?php
session_start();
include('dbcon.php');

$pagetitle = "Login page";
include('includes/header.php');
include('includes/navbar.php');

// Handle login
if (isset($_POST['login_btn'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validation
    if (empty($email) || empty($password)) {
        $_SESSION['status'] = "Email and password are required!";
        $_SESSION['status_code'] = "error";
        header("Location: login.php");
        exit();
    }

    // Check if user exists
    $login_query = "SELECT id, name, email, password, is_verified FROM users WHERE email = ? LIMIT 1";
    $stmt = mysqli_prepare($con, $login_query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Check if email is verified
        if ($user['is_verified'] == 0) {
            $_SESSION['status'] = "Please verify your email first! Check your inbox for the verification link.";
            $_SESSION['status_code'] = "error";
            header("Location: login.php");
            exit();
        }

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['status'] = "Login successful! Welcome " . $user['name'];
            $_SESSION['status_code'] = "success";
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['status'] = "Invalid email or password!";
            $_SESSION['status_code'] = "error";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['status'] = "Invalid email or password!";
        $_SESSION['status_code'] = "error";
        header("Location: login.php");
        exit();
    }
}
?>
<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Alert Messages -->
                <?php
                if (isset($_SESSION['status'])) {
                    $status = $_SESSION['status'];
                    $status_code = $_SESSION['status_code'] ?? 'info';
                    $alert_class = $status_code === 'success' ? 'alert-success' : ($status_code === 'error' ? 'alert-danger' : 'alert-warning');
                    echo "<div class='alert $alert_class alert-dismissible fade show' role='alert'>
                            $status
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                          </div>";
                    unset($_SESSION['status']);
                    unset($_SESSION['status_code']);
                }
                ?>

                <div class="card">
                    <div class="card-header">
                        <h5>Login</h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">

                            <div class="form-group mb-3">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div><br>
                            <div class="form-group mb-3">
                                <button type="submit" name="login_btn" class="btn btn-primary">LOGIN NOW</button>
                            </div>
                            <div class="mt-3">
                                <p><a href="forgot-password.php">Forgot your password?</a></p>
                                <p>Don't have an account? <a href="register.php">Register here</a></p>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php') ?>
