<?php
namespace App\View;
use App\Model\Firestore_honeydoo;
use App\Service\HelperService;

require_once "../../../vendor/autoload.php";

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
    <?php include '../layout/realtor/header.php';?>
    <title>Client Details</title>
</head>
<body class="nav-fixed">
<?php include '../layout/realtor/navbar.php';?>
<div id="layoutSidenav">
    <?php include '../layout/realtor/sidebar.php';?>
    <div id="layoutSidenav_content">
        <main>
            <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
                <div class="container-fluid px-4">
                    <div class="page-header-content">
                        <div class="row align-items-center justify-content-between pt-3">
                            <div class="col-auto mb-3">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i data-feather="list"></i></div>
                                    Client Details
                                </h1>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <a class="btn btn-sm btn-light text-primary" href="<?='list.php'?>">
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
                        <h6 class="heading-small text-blue mb-3">Client 1</h6>
                            <div class="pl-lg-4">
                                <div class="row mb-5">
                                    <div class="col-md-3">
                                        <label class="small mb-1" for="firstName1">First name</label>
                                        <input class="form-control" id="firstName1" type="text" value="<?=$firstName1?>" disabled/>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small mb-1" for="lastName1">Last name</label>
                                        <input class="form-control" id="lastName1" type="text" value="<?=$lastName1?>" disabled/>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="small mb-1" for="email1">Email</label>
                                        <input class="form-control" id="email1" type="text" value="<?=$email1?>" disabled/>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small mb-1" for="phoneNumber1">Phone Number</label>
                                        <input class="form-control" id="phoneNumber1" type="text" value="<?=$phoneNumber1?>" disabled/>
                                    </div>
                                </div>
                            </div>

                <h6 class="heading-small text-blue mb-3">Client 2</h6>
                <div class="pl-lg-4">
                    <div class="row mb-5">
                        <div class="col-md-3">
                            <label class="small mb-1" for="firstName2">First name</label>
                            <input class="form-control" id="firstName2" type="text" value="<?=$firstName2?>" disabled/>
                        </div>
                        <div class="col-md-3">
                            <label class="small mb-1" for="lastName2">Last name</label>
                            <input class="form-control" id="lastName2" type="text" value="<?=$lastName2?>" disabled/>
                        </div>

                        <div class="col-md-3">
                            <label class="small mb-1" for="email2">Email</label>
                            <input class="form-control" id="email2" type="text" value="<?=$email2?>" disabled/>
                        </div>
                        <div class="col-md-3">
                            <label class="small mb-1" for="phoneNumber2">Phone Number</label>
                            <input class="form-control" id="phoneNumber2" type="text" value="<?=$phoneNumber2?>" disabled/>
                        </div>
                    </div>
                </div>

                <h6 class="heading-small text-blue mb-3">Home Detail</h6>
                <div class="pl-lg-4">
                    <div class="row mb-5">
                        <div class="col-md-4">
                            <label class="small mb-1" for="address1">Address 1</label>
                            <input class="form-control" id="address1" type="text" value="<?=$address1?>" disabled/>
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1" for="address2">Address 2</label>
                            <input class="form-control" id="address2" type="text" value="<?=$address2?>" disabled/>
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1" for="state">State</label>
                            <input class="form-control" id="state" type="text" value="<?=$state?>" disabled/>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-4">
                            <label class="small mb-1" for="city">City</label>
                            <input class="form-control" id="city" type="text" value="<?=$city?>" disabled/>
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1" for="zipCode">Zip Code</label>
                            <input class="form-control" id="zipCode" type="text" value="<?=$zipCode?>" disabled/>
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1" for="homeType">Home Type</label>
                            <input class="form-control" id="homeType" type="text" value="<?=$homeType?>" disabled/>
                        </div>
                    </div>
                </div>
                <h6 class="heading-small text-blue mb-3">Personal Notes/Reminders</h6>
                <div class="pl-lg-4">
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <label class="small mb-1" for="notes">Notes</label>
                            <textarea class="lh-base form-control" type="text" rows="5" style="resize: none" disabled><?=$notes?></textarea>
                        </div>
                    </div>
                </div>
        </main>
        <?php include '../layout/realtor/footer.php';?>
    </div>
</div>
<?php include '../layout/realtor/scripts.php';?>
</body>
</html>
