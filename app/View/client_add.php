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

$database = new Firestore_honeydoo();
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
    <title>Add Client</title>
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
            <header class="page-header page-header-compact page-header-light border-bottom bg-white">
                <div class="container-fluid">
                    <div class="page-header-content">
                        <div class="row align-items-center justify-content-between pt-3">
                            <div class="col-auto mb-3">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i data-feather="list"></i></div>
                                    Create New Client
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
            <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
                <div class="container-xl px-4">
                    <div class="page-header-content pt-4">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mt-4">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i data-feather="plus"></i></div>
                                    Add Client (Homeowner)
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Main page content-->
            <div class="container-xl px-4 mt-n10">
                <!-- Wizard card example with navigation-->
                <div class="card">
                    <div class="card-header border-bottom">
                        <!-- Progress bar navigation-->
                        <div id="progressbar1" class="progress">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div id="progressbar2" class="progress" style="display: none">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div id="progressbar3" class="progress" style="display: none">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div id="progressbar4" class="progress" style="display: none">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="cardTabContent">
                            <!-- Wizard Form Field sets-->
                            <div class="py-5 py-xl-10" id="wizard1">
                                <div class="row justify-content-center">
                                    <div class="col-xxl-6 col-xl-8">
                                        <form action="<?='../Controller/add_client_collection_action.php'?>" method="post">
                                            <fieldset id="step1Form">
                                                <div class="row gx-3">
                                                    <div class="col-md-2">
                                                    </div>
                                                    <div class="col-md-8">
                                                        <h3 class="text-primary">Client 1</h3>

                                                        <div class="mb-3">
                                                            <label class="small mb-1" for="firstName1">First name</label>
                                                            <input class="form-control" id="firstName1" type="text" placeholder="Enter your first name" name="firstName1"/>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="small mb-1" for="lastName1">Last name</label>
                                                            <input class="form-control" id="lastName1" type="text" placeholder="Enter your last name" name="lastName1"/>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="small mb-1" for="email1">Email</label>
                                                            <input class="form-control" id="email1" type="email" placeholder="Enter your email address" name="email1"/>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="small mb-1" for="phoneNumber1">Phone number</label>
                                                            <input class="form-control" id="phoneNumber1" type="tel" placeholder="Enter your phone number" name="phoneNumber1"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">

                                                </div>
                                                <hr class="my-4" />
                                                <div class="text-center">
                                                    <button class="btn btn-primary" type="button" id="step1button">Next</button>
                                                </div>
                                            </fieldset>

                                            <fieldset id="step2Form" style="display: none">
                                                <div class="row gx-3">
                                                    <div class="col-md-2">
                                                    </div>
                                                    <div class="col-md-8">
                                                        <h3 class="text-primary">Client 2</h3>

                                                        <div class="mb-3">
                                                            <label class="small mb-1" for="firstName2">First name</label>
                                                            <input class="form-control" id="firstName2" type="text" placeholder="Enter your first name" name="firstName2"/>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="small mb-1" for="lastName2">Last name</label>
                                                            <input class="form-control" id="lastName2" type="text" placeholder="Enter your last name" name="lastName2"/>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="small mb-1" for="email2">Email</label>
                                                            <input class="form-control" id="email2" type="email" placeholder="Enter your email address" name="email2"/>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="small mb-1" for="phoneNumber2">Phone number</label>
                                                            <input class="form-control" id="phoneNumber2" type="tel" placeholder="Enter your phone number" name="phoneNumber2"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">

                                                </div>
                                                <hr class="my-4" />
                                                <div class="d-flex justify-content-between">
                                                    <button class="btn btn-light" type="button" id="step2prev">Previous</button>
                                                    <button class="btn btn-primary" type="button"  id="step2button">Next</button>
                                                </div>
                                            </fieldset>

                                            <fieldset id="step3Form" style="display: none">
                                                <div class="row gx-3">
                                                    <div class="col-md-2">
                                                    </div>
                                                    <div class="col-md-8">
                                                        <h3 class="text-primary">Home Detail</h3>

                                                        <div class="mb-3">
                                                            <label class="small mb-1" for="address1">Address Line 1</label>
                                                            <input class="form-control" id="address1" type="text" placeholder="Address Line 1" name="address1"/>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="small mb-1" for="address2">Address Line 2</label>
                                                            <input class="form-control" id="address2" type="text" placeholder="Address Line 2" name="address2"/>
                                                        </div>
                                                        <div class="row gx-3">
                                                            <div class="mb-3 col-md-6">
                                                                <label class="small mb-1" for="city">City</label>
                                                                <input class="form-control" id="city" type="text" placeholder="City" name="city"/>
                                                            </div>
                                                            <div class="mb-3 col-md-6">
                                                                <label class="small mb-1" for="state">State</label>
                                                                <input class="form-control" id="state" placeholder="State" name="state"/>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="small mb-1" for="zipCode">Zip Code</label>
                                                            <input class="form-control" id="zipCode" placeholder="Zip Code" name="zipCode"/>
                                                        </div>
                                                        <div class="mb-3">
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
                                                <div class="col-md-2">
                                                </div>
                                                <hr class="my-4" />
                                                <div class="d-flex justify-content-between">
                                                    <button class="btn btn-light" type="button" id="step3prev">Previous</button>
                                                    <button class="btn btn-primary" type="button"  id="step3button">Next</button>
                                                </div>
                                            </fieldset>

                                            <fieldset id="step4Form" style="display: none">
                                                <div class="row gx-3">
                                                    <div class="col-md-2">
                                                    </div>
                                                    <div class="col-md-8">
                                                        <h3 class="text-primary">Selections</h3>
                                                        <div class="form-check mb-3">
                                                            <input class="form-check-input" id="flexCheckDefault" type="checkbox" value="">
                                                            <label class="form-check-label" for="flexCheckDefault">My Home Pro List</label>
                                                        </div>
                                                        <h3 class="text-primary">Personal Notes/Reminders</h3>
                                                        <div class="mb-3">
                                                            <textarea id="notes" class="lh-base form-control" type="text" name="notes" placeholder="Enter notes..." rows="4" style="resize: none"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">

                                                </div>
                                                <hr class="my-4" />
                                                <div class="d-flex justify-content-between">
                                                    <button class="btn btn-light" type="button" id="step4prev">Previous</button>
                                                    <button class="btn btn-primary" type="submit">Add Client</button>
                                                </div>
                                            </fieldset>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
<script>
    $( document ).ready(function() {
        // Next Button
        // From Step 1 to Step 2
        $("#step1button").click(function () {
            $("#step1Form").hide()
            $("#progressbar1").hide()
            $("#step2Form").show()
            $("#progressbar2").show()
        })
        // From Step 2 to Step 3
        $("#step2button").click(function () {
            $("#step2Form").hide()
            $("#progressbar2").hide()
            $("#step3Form").show()
            $("#progressbar3").show()
        })
        // From Step 3 to Step 4
        $("#step3button").click(function () {
            $("#step3Form").hide()
            $("#progressbar3").hide()
            $("#step4Form").show()
            $("#progressbar4").show()
        })

        // Previous Button
        // From Step 1 to Step 2
        $("#step2prev").click(function () {
            $("#step2Form").hide()
            $("#progressbar2").hide()
            $("#step1Form").show()
            $("#progressbar1").show()
        })
        // From Step 2 to Step 3
        $("#step3prev").click(function () {
            $("#step3Form").hide()
            $("#progressbar3").hide()
            $("#step2Form").show()
            $("#progressbar2").show()
        })
        // From Step 3 to Step 4
        $("#step4prev").click(function () {
            $("#step4Form").hide()
            $("#progressbar4").hide()
            $("#step3Form").show()
            $("#progressbar3").show()
        })
    });
</script>
</body>
</html>
