<?php
namespace App\View;
use App\Model\Firestore_honeydoo;
use App\Service\HelperService;

require_once "../../vendor/autoload.php";

if(!isset($_SESSION["user"]))
{
    header("Location: login.php");
} else {
    if(isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "ROLE_ADMIN")
    {
        header("Location: users_list.php");
    }
}
$clientCollectionId = $_GET["client_collection_id"];
$database = new Firestore_honeydoo();
$clientCollectionToShow = $database->fetchClientCollectionById($clientCollectionId);
$firstName1 = $clientCollectionToShow["first_name_1"];
$lastName1 = $clientCollectionToShow["last_name_1"];
$email1 = $clientCollectionToShow["email_1"];
$phoneNumber1 = $clientCollectionToShow["phone_1"];
$firstName2 = $clientCollectionToShow["first_name_2"];
$lastName2 = $clientCollectionToShow["last_name_2"];
$email2 = $clientCollectionToShow["email_2"];
$phoneNumber2 = $clientCollectionToShow["phone_2"];
$address1 = $clientCollectionToShow["address_1"];
$address2 = $clientCollectionToShow["address_2"];
$state = $clientCollectionToShow["state"];
$city = $clientCollectionToShow["city"];
$zipCode = $clientCollectionToShow["zip"];
$homeType = $clientCollectionToShow["home_type"];
$notes = $clientCollectionToShow["notes"];

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
    <title>Edit Client</title>
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
                <a class="dropdown-item" href="<?php echo 'my_profile.php' ?>">
                    <div class="dropdown-item-icon"><i data-feather="user"></i></div>
                    My Profile
                </a>
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
                        My Stories
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
                                    Edit Client
                                </h1>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <a class="btn btn-sm btn-light text-primary" href="<?='clients_list.php'?>">
                                    <i class="me-1" data-feather="arrow-left"></i>
                                    Back to All Clients
                                </a>
                                <div>
                                </div>
                            </div>
                        </div>
            </header>
            <!-- Main page content-->
            <div class="container-sm px-4">
                <form action="<?='../Controller/edit_client_collection_action.php?client_collection_id=' . $clientCollectionId?>" method="post">
                    <h6 class="heading-small text-blue mb-3">Client 1</h6>
                    <div class="pl-lg-4">
                        <div class="row mb-5">
                            <div class="col-md-3">
                                <label class="small mb-1" for="firstName1">First name</label>
                                <input class="form-control" id="firstName1" type="text" value="<?=$firstName1?>" name="firstName1"/>
                            </div>
                            <div class="col-md-3">
                                <label class="small mb-1" for="lastName1">Last name</label>
                                <input class="form-control" id="lastName1" type="text" value="<?=$lastName1?>" name="lastName1"/>
                            </div>

                            <div class="col-md-3">
                                <label class="small mb-1" for="email1">Email</label>
                                <input class="form-control" id="email1" type="text" value="<?=$email1?>" name="email1"/>
                            </div>
                            <div class="col-md-3">
                                <label class="small mb-1" for="phoneNumber1">Phone Number</label>
                                <input class="form-control" id="phoneNumber1" type="text" value="<?=$phoneNumber1?>" name="phoneNumber1"/>
                            </div>
                        </div>
                    </div>

                    <h6 class="heading-small text-blue mb-3">Client 2</h6>
                    <div class="pl-lg-4">
                        <div class="row mb-5">
                            <div class="col-md-3">
                                <label class="small mb-1" for="firstName2">First name</label>
                                <input class="form-control" id="firstName2" type="text" value="<?=$firstName2?>" name="firstName2"/>
                            </div>
                            <div class="col-md-3">
                                <label class="small mb-1" for="lastName2">Last name</label>
                                <input class="form-control" id="lastName2" type="text" value="<?=$lastName2?>" name="lastName2"/>
                            </div>

                            <div class="col-md-3">
                                <label class="small mb-1" for="email2">Email</label>
                                <input class="form-control" id="email2" type="text" value="<?=$email2?>" name="email2"/>
                            </div>
                            <div class="col-md-3">
                                <label class="small mb-1" for="phoneNumber2">Phone Number</label>
                                <input class="form-control" id="phoneNumber2" type="text" value="<?=$phoneNumber2?>" name="phoneNumber2"/>
                            </div>
                        </div>
                    </div>

                    <h6 class="heading-small text-blue mb-3">Home Detail</h6>
                    <div class="pl-lg-4">
                        <div class="row mb-5">
                            <div class="col-md-4">
                                <label class="small mb-1" for="address1">Address 1</label>
                                <input class="form-control" id="address1" type="text" value="<?=$address1?>" name="address1"/>
                            </div>
                            <div class="col-md-4">
                                <label class="small mb-1" for="address2">Address 2</label>
                                <input class="form-control" id="address2" type="text" value="<?=$address2?>" name="address2"/>
                            </div>
                            <div class="col-md-4">
                                <label class="small mb-1" for="state">State</label>
                                <input class="form-control" id="state" type="text" value="<?=$state?>" name="state"/>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-4">
                                <label class="small mb-1" for="city">City</label>
                                <input class="form-control" id="city" type="text" value="<?=$city?>" name="city"/>
                            </div>
                            <div class="col-md-4">
                                <label class="small mb-1" for="zipCode">Zip Code</label>
                                <input class="form-control" id="zipCode" type="text" value="<?=$zipCode?>" name="zipCode"/>
                            </div>
                            <div class="col-md-4">
                                <label class="small mb-1" for="homeType">Home Type</label>
                                <select class="form-control" id="homeType" name="homeType">
                                    <option>Select Home Type</option>
                                    <option value="Single Family Home">Single Family Home</option>
                                    <option value="Townhome">Townhome</option>
                                    <option value="Condo">Condo</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <h6 class="heading-small text-blue mb-3">Personal Notes/Reminders</h6>
                    <div class="pl-lg-4">
                        <div class="row mb-5">
                            <div class="col-md-12">
                                <label class="small mb-1" for="notes">Notes</label>
                                <textarea class="lh-base form-control" type="text" rows="5" style="resize: none" name="notes"><?=$notes?></textarea>
                            </div>
                        </div>
                    </div>
                    <div style="text-align: right">
                        <div><input class="btn btn-lg btn-primary" type="submit" value="Save"></div>
                    </div>
                </form>
            </div>
        </main>
        <?php include 'layout/footer.php';?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@5" type="text/javascript"></script>
<script src="../Ressources/js/datatables/datatables-simple-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="../Ressources/js/scripts.js"></script>
<script>
    window.addEventListener("DOMContentLoaded", function(){
        var selectedHomeType = "<?php echo($homeType); ?>";
        $("#homeType").val(selectedHomeType)
    })
</script>
</body>
</html>
