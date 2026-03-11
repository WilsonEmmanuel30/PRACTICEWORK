<?php
session_start();
include('dbcon.php');

$pagetitle = "Reset Password";
include('includes/header.php');
include('includes/navbar.php');

$token_valid = false;
$token = '';

// Check if token is provided
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verify the token exists and hasn't expired
    $check_token_query = "SELECT id FROM users WHERE reset_token = ? AND reset_token_expire > NOW() LIMIT 1";
    $stmt = mysqli_prepare($con, $check_token_query);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $token_valid = true;
    }
}
?>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php
                // Display alert messages
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

                if (!$token_valid) {
                    echo "<div class='alert alert-danger' role='alert'>
                            <strong>Invalid or Expired Link</strong>
                            <p>The password reset link is invalid or has expired. Please request a new one.</p>
                            <p><a href='forgot-password.php' class='btn btn-primary'>Request New Reset Link</a></p>
                          </div>";
                } else {
                ?>

                <div class="card">
                    <div class="card-header">
                        <h5>Reset Password</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-4">Enter your new password below.</p>

                        <form action="code.php" method="POST">
                            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                            
                            <div class="form-group mb-3">
                                <label for="password">New Password</label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="Minimum 6 characters" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="confirm_password">Confirm Password</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Re-enter your password" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="reset_password_btn" class="btn btn-primary">Reset Password</button>
                            </div>
                            <div class="mt-3">
                                <p>Remember your password? <a href="login.php">Login here</a></p>
                            </div>
                        </form>

                    </div>
                </div>

                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php') ?>
