<?php
if(isset($_SESSION['user'])) {
    if(isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "ROLE_ADMIN")
    {
        header("Location: users_list.php");
    } else {
        header("Location: blog_posts.php");
    }
}

if(isset($_SESSION['registration_error_flash_message'])) {
    $errorMessage = $_SESSION['registration_error_flash_message'];
    unset($_SESSION['registration_error_flash_message']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Honeydoo" />
    <meta name="author" content="Honeydoo" />
    <title>Registration - Honeydoo</title>
    <link href="../Ressources/css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="../Ressources/assets/img/favicon.png" />
</head>
<body class="bg-primary">
<div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
        <main>
            <div class="container-xl px-4">
                <div class="row justify-content-center">
                    <div class="col-lg-7">
                        <!-- Basic registration form-->
                        <div class="card shadow-lg border-0 rounded-lg mt-5">
                            <div class="card-header justify-content-center"><h3 class="fw-light my-4 text-center">Create Account</h3></div>
                            <div class="card-body">
                                <!-- Registration form-->
                                <form action="../Controller/registration_action.php" method="post">
                                    <?php
                                    if(isset($errorMessage))
                                    {
                                        echo "<div class='alert alert-danger'>$errorMessage</div>";
                                    }
                                    ?>
                                    <!-- Form Row-->
                                    <div class="row gx-3">
                                        <div class="col-md-6">
                                            <!-- Form Group (full name)-->
                                            <div class="mb-3">
                                                <label class="small mb-1" for="fullName">Full Name</label>
                                                <input class="form-control" name="fullName" type="text" placeholder="Enter your full name" required/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- Form Group (email address)-->
                                            <div class="mb-3">
                                                <label class="small mb-1" for="emailAddress">Email Address</label>
                                                <input class="form-control" name="emailAddress" type="email" placeholder="Enter your email address" required/>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Form Row-->
                                    <div class="row gx-3">
                                        <div class="col-md-6">
                                            <!-- Form Group (company)-->
                                            <div class="mb-3">
                                                <label class="small mb-1" for="companyName">Company Name</label>
                                                <input class="form-control" name="companyName" type="text" placeholder="Enter your company name" required/>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <!-- Form Group (phone number)-->
                                            <div class="mb-3">
                                                <label class="small mb-1" for="phoneNumber">Phone Number</label>
                                                <input class="form-control" name="phoneNumber" type="text" placeholder="Enter your phone number" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Form Row    -->
                                    <div class="row gx-3">
                                        <div class="col-md-6">
                                            <!-- Form Group (password)-->
                                            <div class="mb-3">
                                                <label class="small mb-1" for="password">Password</label>
                                                <input class="form-control" name="password" type="password" placeholder="Enter your password" required/>
                                            </div>
                                            <div style="color: grey; font-size: small">
                                                <small>Your password must be at least 8 characters long and contains a special character, e.g. !#$@.,:;()</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- Form Group (confirm password)-->
                                            <div class="mb-3">
                                                <label class="small mb-1" for="confirmPassword">Confirm Password</label>
                                                <input class="form-control" name="confirmPassword" type="password" placeholder="Confirm your password" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Form Group (create account submit)-->
                                    <div class="mt-4 mb-0 text-center">
                                        <input class="btn btn-primary" type="submit" value="Create Account">
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center">
                                <div class="small"><a href="<?='login.php'?>">Have an account? Go to login</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <div id="layoutAuthentication_footer">
        <footer class="footer-admin mt-auto footer-dark">
            <div class="container-xl px-4">
                <div class="row">
                    <div class="small text-center">Copyright &copy; Honeydoo</div>
                </div>
            </div>
        </footer>
    </div>
</div>
</body>
</html>
