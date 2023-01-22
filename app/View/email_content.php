<?php
namespace App\View;
use App\Model\Firestore_honeydoo;
use App\Service\HelperService;

require_once "../../vendor/autoload.php";

if(!isset($_SESSION["user"]))
{
    header("Location: login.php");
}

if(isset($_SESSION["user"]["role"]) && ($_SESSION["user"]["role"] != "ROLE_ADMIN"))
{
    header("Location: blog_posts.php");
}

$database = new Firestore_honeydoo();
$email = $database->fetchEmailContent();
$emailContentToEdit = $email["content"];
$emailSubjectToEdit = $email["subject"];

//
$realtor = $database->fetchRealtorById($_SESSION["user"]["realtor_id"]);
$helper = new HelperService();
$profilePic = $helper->setProfilePic($realtor);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Honeydoo" />
    <meta name="author" content="Honeydoo" />
    <title>Email Content</title>
    <link href="../Ressources/css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
    <link rel="icon" type="image/x-icon" href="../Ressources/assets/img/favicon.png" />
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body class="nav-fixed">
<nav class="topnav navbar navbar-expand shadow justify-content-between justify-content-sm-start navbar-light bg-white" style="background-color: #262144!important" id="sidenavAccordion">
    <!-- Sidenav Toggle Button-->
    <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 me-2 ms-lg-2 me-lg-0" id="sidebarToggle" style="background-color: #00B2A0!important;"><i data-feather="menu"></i></button>
    <!-- Navbar Brand-->
    <!--<a class="navbar-brand pe-3 ps-4 ps-lg-2" href="#">Honeydoo</a>-->
    <img class="navbar-brand pe-3 ps-4 ps-lg-2" src="../Ressources/assets/img/HoneyDoo-logo.png">
    <!-- Navbar Items-->
    <ul class="navbar-nav align-items-center ms-auto">
        <!-- User Dropdown-->
        <li class="nav-item dropdown no-caret dropdown-user me-3 me-lg-4">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img class="img-fluid" src=<?= $profilePic ?> /></a>
            <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">
                <h6 class="dropdown-header d-flex align-items-center">
                    <img class="dropdown-user-img" src=<?=$profilePic?> />
                    <div class="dropdown-user-details">
                        <div class="dropdown-user-details-name"><?=$_SESSION["user"]["realtor_title"]?></div>
                        <div class="dropdown-user-details-email"><?=$_SESSION["user"]["email"]?></div>
                    </div>
                </h6>
                <div class="dropdown-divider"></div>
                <!--<a class="dropdown-item" href="<?php /*echo 'my_profile.php' */?>">
                    <div class="dropdown-item-icon"><i data-feather="user"></i></div>
                    My Profile
                </a>-->
                <a class="dropdown-item" href="<?php echo 'logout.php' ?>">
                    <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sidenav shadow-right sidenav-light">
            <div class="sidenav-menu">
                <div class="nav accordion" id="accordionSidenav">
                    <!-- Sidenav Heading (Custom)-->
                    <div class="sidenav-menu-heading">Dashboard</div>
                    <!-- Sidenav Link (Users)-->
                    <a class="nav-link" href="<?='users_list.php'?>">
                        <div class="nav-link-icon"><i data-feather="user"></i></div>
                        Users
                    </a>
                    <!-- Sidenav Link (Email)-->
                    <a class="nav-link" href="<?='email_content.php'?>">
                        <div class="nav-link-icon"><i data-feather="mail"></i></div>
                        Email
                    </a>
                </div>
            </div>
        </nav>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
                <div class="container-fluid px-4">
                    <div class="page-header-content">
                        <div class="row align-items-center justify-content-between pt-3">
                            <div class="col-auto mb-3">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i data-feather="edit"></i></div>
                                    Edit Email
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Main page content-->
            <div class="container-fluid px-4">
                <form action="<?='../Controller/save_email_content_action.php'?>" method="post" enctype="multipart/form-data">
                    <div class="row gx-4">
                        <div class="col-lg-2">
                        </div>
                        <div class="col-lg-8">
                            <div class="card mb-4 text-center">
                                <div class="card-header">
                                    Email Subject
                                </div>
                                <div class="card-body"><input class="form-control" id="postTitleInput" type="text" placeholder="Enter your email subject here..." value="<?=$emailSubjectToEdit?>" name="emailSubject" required/></div>
                            </div>
                            <div class="card mb-4 text-center">
                                <div class="card-header">
                                    Email Content
                                </div>
                                <div class="card-body"><textarea class="lh-base form-control" type="text" placeholder="Enter your email content here..." rows="25" name="emailContent" style="resize: none" required><?=$emailContentToEdit?></textarea></div>
                            </div>
                            <div class="text-center">
                                <div class="card-body">
                                    <div><input class="btn btn-primary" type="submit" value="Save"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2">
                        </div>
                    </div>
                </form>
            </div>
        </main>
        <footer class="footer-admin mt-auto footer-light">
            <div class="container-xl px-4">
                <div class="row">
                    <div class="small text-center">Copyright &copy; Honeydoo</div>
                </div>
            </div>
        </footer>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@5" type="text/javascript"></script>
<script src="../Ressources/js/datatables/datatables-simple-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="../Ressources/js/scripts.js"></script>
<script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>
<script src="../Ressources/js/markdown.js"></script>
</body>
</html>
