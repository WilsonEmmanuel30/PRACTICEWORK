<?php
$pagetitle = "Login page";
include('includes/header.php');
include('includes/navbar.php');
?>
<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Login page</h5>
                    </div>
                    <div class="card-body">
                        <form action="">

                            <div class="form-group ab-3">
                                <label for="">Email Address</label>
                                <input type="text" name="email" class="form-control">
                            </div>
                            <div class="form-group ab-3">
                                <label for="">Password</label>
                                <input type="text" name="password" class="form-control">
                            </div><br>
                            <div class="form-group ab-3">
                                <button type="submit" class="btn btn-primary">LOGIN NOW</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php') ?>