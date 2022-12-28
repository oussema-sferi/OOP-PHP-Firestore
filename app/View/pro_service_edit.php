<?php
namespace App\View;
use App\Model\Firestore_honeydoo;
require_once "../../vendor/autoload.php";

if(!isset($_SESSION["user"]))
{
    header("Location: login.php");
}
$proServiceId = $_GET["pro_service_id"];
$database = new Firestore_honeydoo();
$proServiceToEdit = $database->fetchProServiceById($proServiceId);
$companyName = $proServiceToEdit["company_name"];
$companyEmail = $proServiceToEdit["company_email"];
$companyPhoneNumber = $proServiceToEdit["company_phone_number"];
$companyWebsiteLink = $proServiceToEdit["company_website_link"];
$homeProType = $proServiceToEdit["homePro_type"];
$companyComments = $proServiceToEdit["comments"];
$companyNotes = $proServiceToEdit["my_notes"];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Honeydoo" />
    <meta name="author" content="Honeydoo" />
    <title>Edit Pro Service</title>
    <link href="../Ressources/css/styles.css" rel="stylesheet" />
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
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img class="img-fluid" src="../Ressources/assets/img/illustrations/profiles/profile-4.png" /></a>
            <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">
                <h6 class="dropdown-header d-flex align-items-center">
                    <img class="dropdown-user-img" src="../Ressources/assets/img/illustrations/profiles/profile-4.png" />
                    <div class="dropdown-user-details">
                        <div class="dropdown-user-details-name"><?=$_SESSION["user"]["realtor_title"]?></div>
                        <div class="dropdown-user-details-email"><?=$_SESSION["user"]["email"]?></div>
                    </div>
                </h6>
                <div class="dropdown-divider"></div>
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
                    <!-- Sidenav Link (Blog Posts)-->
                    <a class="nav-link" href="<?='blog_posts.php'?>">
                        <div class="nav-link-icon"><i data-feather="list"></i></div>
                        My Blog Posts
                    </a>
                    <!-- Sidenav Link (Pro Services)-->
                    <a class="nav-link" href="<?='pro_services.php'?>">
                        <div class="nav-link-icon"><i data-feather="list"></i></div>
                        My Pro Services
                    </a>
                    <!-- Sidenav Link (Client)-->
                    <a class="nav-link" href="<?='clients_list.php'?>">
                        <div class="nav-link-icon"><i data-feather="list"></i></div>
                        Clients List
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
                                    <div class="page-header-icon"><i data-feather="list"></i></div>
                                    Edit Pro Service
                                </h1>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <a class="btn btn-sm btn-light text-primary" href="<?='pro_services.php'?>"">
                                    <i class="me-1" data-feather="arrow-left"></i>
                                    Back to All Pro Services
                                </a>
                            <div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Main page content-->
            <div class="container-fluid px-4">
                <form action="<?='../Controller/edit_pro_service_action.php?pro_service_id=' . $proServiceId?>" method="post" enctype="multipart/form-data">
                    <div class="row gx-4">
                        <div class="col-lg-8">
                            <div class="card mb-4">
                                <div class="card-header">Company Name</div>
                                <div class="card-body"><input class="form-control" id="proServiceTitleInput" type="text" placeholder="Enter company name..." name="companyName" value="<?=$companyName?>" required/></div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-header">Company Email</div>
                                <div class="card-body"><input class="form-control" id="proServiceSubTitleInput" type="text" placeholder="Enter company email..." name="companyEmail" value="<?=$companyEmail?>" required/></div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-header">Company Phone Number</div>
                                <div class="card-body"><input class="form-control" id="proServiceSubTitleInput" type="text" placeholder="Enter company phone number..." name="companyPhoneNumber" value="<?=$companyPhoneNumber?>" required/></div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-header">Company Website Link</div>
                                <div class="card-body"><input class="form-control" id="proServiceSubTitleInput" type="text" placeholder="Enter company website link..." name="companyWebsiteLink" value="<?=$companyWebsiteLink?>" required/></div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-header">Home Pro Type</div>
                                <div class="card-body"><input class="form-control" id="proServiceSubTitleInput" type="text" placeholder="Enter home pro type..." name="homeProType" value="<?=$homeProType?>" required/></div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-header">Comments</div>
                                <div class="card-body"><textarea class="lh-base form-control" type="text" placeholder="Enter your comments..." rows="5" name="comments" required><?=$companyComments?></textarea></div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-header">My Notes</div>
                                <div class="card-body"><textarea class="lh-base form-control" type="text" placeholder="Enter your notes..." rows="5" name="myNotes" required><?=$companyNotes?></textarea></div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-header">Image</div>
                                <div class="card-body"><input type="file" accept="image/jpeg/png" name="proServiceImage"></div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card card-header-actions">
                                <div class="card-body">
                                    <div class="d-grid"><input class="fw-500 btn btn-primary" type="submit" value="Save"></div>
                                </div>
                            </div>
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
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
<script src="../Ressources/js/datatables/datatables-simple-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="../Ressources/js/scripts.js"></script>
</body>
</html>
