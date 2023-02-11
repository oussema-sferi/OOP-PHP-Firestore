<?php
require_once "../../../vendor/autoload.php";
use App\Model\Firestore_honeydoo;

if(isset($_SESSION['user'])) {
    if(isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "ROLE_ADMIN")
    {
        header("Location: ../users_list.php");
    } else {
        header("Location: ../blog_posts.php");
    }
}
if(!isset($_SESSION['token']))
{
    header("Location: request.php");
}
$tokenFromSession = $_SESSION['token'];
$database = new Firestore_honeydoo();
$userFromDB = $database->fetchTokenFromDb($tokenFromSession);
if(!$userFromDB)
{
    header("Location: request.php");
}

if(isset($_SESSION['change_password_error_flash_message'])) {
    $errorMessage = $_SESSION['change_password_error_flash_message'];
    unset($_SESSION['change_password_error_flash_message']);
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
    <title>Change Password Form - HoneyDoo Realtor Portal</title>
    <link href="../../Ressources/css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="../../Ressources/assets/img/favicon.png" />
</head>
<body class="bg-primary">
<div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
        <main>
            <center><img src="https://realtors.honeydoo.io/app/Ressources/assets/img/HoneyDoo-logo.png" width="400" style="margin: 50px 0 25px;"></center>
            <div class="container-xl px-4">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <!-- Change password form-->
                        <div class="card shadow-lg border-0 rounded-lg mt-5">
                            <div class="card-header justify-content-center"><h3 class="fw-light my-4 text-center">Enter your new password</h3></div>
                            <div class="card-body">
                                <!-- Forgot password form-->
                                <form action="../../Controller/forgot_password_change_password_action.php" method="post">
                                    <?php
                                    if(isset($errorMessage))
                                    {
                                        echo "<div class='alert alert-danger'>$errorMessage</div>";
                                    }
                                    ?>
                                <!-- Form Group (password)-->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="password">Password</label>
                                        <input class="form-control" name="password" type="password" placeholder="Enter your new password" required/>
                                        <div style="color: grey; font-size: small">
                                            <small>Your password must be at least 8 characters long and contains a special character, e.g. !#$@.,:;</small>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="small mb-1" for="confirmPassword">Confirm Password</label>
                                        <input class="form-control" name="confirmPassword" type="password" placeholder="Confirm your password" required/>
                                    </div>
                                    <!-- Form Group (submit options)-->
                                    <div class="text-center">
                                        <button class="btn btn-success text-center" type="submit">Reset Password</button>
                                    </div>
                                </form>
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
