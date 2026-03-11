<?php
session_start();
include('dbcon.php');

$pagetitle = "Email Verification";
include('includes/header.php');
include('includes/navbar.php');

$message = '';
$alert_type = '';
$verified = false;

// Check if token is provided
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verify the token exists and mark user as verified
    $verify_query = "SELECT id, name, email FROM users WHERE verify_token = ? LIMIT 1";
    $stmt = mysqli_prepare($con, $verify_query);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Update user to mark as verified
        $update_query = "UPDATE users SET is_verified = 1, verify_token = NULL WHERE id = ?";
        $stmt = mysqli_prepare($con, $update_query);
        mysqli_stmt_bind_param($stmt, "i", $user['id']);

        if (mysqli_stmt_execute($stmt)) {
            $message = "Email verification successful! Your account is now active. You can now login with your credentials.";
            $alert_type = "success";
            $verified = true;
        } else {
            $message = "An error occurred during verification. Please try again.";
            $alert_type = "error";
        }
    } else {
        $message = "Invalid or expired verification link. Please register again.";
        $alert_type = "error";
    }
} else {
    $message = "No verification token provided.";
    $alert_type = "error";
}
?>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Email Verification</h5>
                    </div>
                    <div class="card-body text-center">
                        <?php
                        $alert_class = $alert_type === 'success' ? 'alert-success' : 'alert-danger';
                        echo "<div class='alert $alert_class' role='alert'>
                                $message
                              </div>";
                        
                        if ($verified) {
                            echo "<p class='mt-3'><a href='login.php' class='btn btn-primary'>Proceed to Login</a></p>";
                        } else {
                            echo "<p class='mt-3'><a href='register.php' class='btn btn-secondary'>Back to Registration</a></p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php') ?>
