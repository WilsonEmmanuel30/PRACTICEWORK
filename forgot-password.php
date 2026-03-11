<?php
$pagetitle = "Forgot Password";
include('includes/header.php');
include('includes/navbar.php');
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
                        <h5>Forgot Password</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-4">Enter your email address and we'll send you a link to reset your password.</p>

                        <form action="code.php" method="POST">
                            <div class="form-group mb-3">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your registered email" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="forgot_password_btn" class="btn btn-primary">Send Reset Link</button>
                            </div>
                            <div class="mt-3">
                                <p>Remember your password? <a href="login.php">Login here</a></p>
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
