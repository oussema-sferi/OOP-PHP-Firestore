<?php
if(isset($_SESSION['user'])) {
    if(isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "ROLE_ADMIN")
    {
        header("Location: ../users_list.php");
    } else {
        header("Location: ../blog_posts.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../../layout/security/header.php';?>
    <title>Check Email - HoneyDoo Realtor Portal</title>
</head>
<body class="bg-primary">
<div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
        <main>
            <center><img src="https://realtors.honeydoo.io/app/Ressources/assets/img/HoneyDoo-logo.png" width="400" style="margin: 50px 0 25px;"></center>
            <div class="container-xl px-4">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="card shadow-lg border-0 rounded-lg mt-5">
                            <div class="card-header justify-content-center"><h3 class="fw-light my-4 text-center">Password Reset Email Sent!</h3></div>
                            <div class="card-body">
                                <div class="small mb-3">
                                    If an account matching your email exists, then an email will be sent that contains a link that you can use to reset your password.
                                    <p>If you do not receive an email please check your spam folder or <a href="<?='request.php'?>">try again</a>.</p>
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <div class="small"><a class="text-white" href="<?=$baseUrl . 'common/security/login.php'?>">Return to login</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <div id="layoutAuthentication_footer">
        <?php include '../../layout/security/footer.php';?>
    </div>
</div>
</body>
</html>
