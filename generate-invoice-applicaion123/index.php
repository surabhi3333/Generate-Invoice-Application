<?php
    session_start();
    include('header.php');
?>


<body style="background-color: #17a2b885;">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-2-strong" style="border-radius: 1rem;">
                    <div class="card-body p-5 text-center">
                        <h3 class="mb-5">Login Form</h3>
                        <form method="POST" action="invoice_list.php" id="user_login_form">
                            <div class="form-group">
                                <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Enter Username" autocomplete="off" maxlength="60">
                            </div> 
                            <div class="form-group">
                                <input type="password" class="form-control" id="user_password" name="user_password" placeholder="Enter Password" autocomplete="off" maxlength="60">
                            </div>
                            <div class="form-group">
                                <button name="user_login_form_submit_btn" type="button" class="btn btn-primary btn-lg btn-block " onclick="validateUserLogin();" >Login</button>
                            </div>
                            <div class="alert-danger" id="login_error" style="display: none;"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php include('footer.php'); ?>
