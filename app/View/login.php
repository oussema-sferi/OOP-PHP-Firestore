<?php
if(isset($_SESSION['user'])) {
    if(isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "ROLE_ADMIN")
    {
        header("Location: users_list.php");
    } else {
        header("Location: blog_posts.php");
    }
}

if(isset($_SESSION['login_error_flash_message'])) {
    $errorMessage = $_SESSION['login_error_flash_message'];
    unset($_SESSION['login_error_flash_message']);
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
    <title>Login - Honeydoo</title>
    <link href="../Ressources/css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="../Ressources/assets/img/favicon.png" />
</head>
<body class="bg-primary">
<div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
        <main>
            <div class="container-xl px-4">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <!-- Basic login form-->
                        <div class="card shadow-lg border-0 rounded-lg mt-5">
                            <div class="card-header justify-content-center text-center"><h3 class="fw-light my-4">Authentication</h3></div>
                            <div class="card-body">
                                <!-- Login form-->
                                <form action="../Controller/login_action.php" method="post">
                                    <?php
                                    if(isset($errorMessage))
                                    {
                                        echo "<div class='alert alert-danger'>$errorMessage</div>";
                                    }
                                    ?>
                                    <!-- Form Group (email address)-->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="email">Email</label>
                                        <input class="form-control" type="email" name="email" placeholder="Enter your email address" required/>
                                    </div>
                                    <!-- Form Group (password)-->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="password">Password</label>
                                        <input class="form-control" type="password" name="password" placeholder="Enter your password" required/>
                                    </div>
                                    <!-- Form Group (login box)-->
                                    <div class="mt-4 mb-0 text-center">
                                        <input class="btn btn-primary" type="submit" value="Login">
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
