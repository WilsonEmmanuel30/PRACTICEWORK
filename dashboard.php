<?php
session_start();
include('dbcon.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['status'] = "Please login to access the dashboard!";
    $_SESSION['status_code'] = "error";
    header("Location: login.php");
    exit();
}

$pagetitle = "Dashboard";
include('includes/header.php');
include('includes/navbar.php');
?>

<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
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
                        <h4>User Dashboard</h4>
                    </div>
                    <div class="card-body">
                        <h4>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h4>
                        <p>You are successfully logged in to your account.</p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
                        
                        <div class="mt-4">
                            <a href="logout.php" class="btn btn-danger">Logout</a>
                        </div>
                    </div>
                </div>
            </div>

            <?php include('includes/footer.php') ?>
