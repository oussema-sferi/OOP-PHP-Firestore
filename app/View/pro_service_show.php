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
$proServiceId = $_GET["pro_service_id"];
$database = new Firestore_honeydoo();
$proServiceToShow = $database->fetchProServiceById($proServiceId);
$companyName = $proServiceToShow["company_name"] ?? "";
$companyEmail = $proServiceToShow["company_email"] ?? "";
$companyPhoneNumber = $proServiceToShow["company_phone_number"] ?? "";
$companyWebsiteLink = $proServiceToShow["company_website_link"] ?? "";
$homeProType = $proServiceToShow["homePro_type"] ?? "";
$companyComments = $proServiceToShow["comments"] ?? "";
$companyNotes = $proServiceToShow["my_notes"] ?? "";
$proServiceImage = $proServiceToShow["img"] ?? "";

//
$realtor = $database->fetchRealtorById($_SESSION["user"]["realtor_id"]);
$helper = new HelperService();
$profilePic = $helper->setProfilePic($realtor);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'layout/realtor/header.php';?>
    <title>Pro Service Details</title>
</head>
<body class="nav-fixed">
<?php include 'layout/realtor/navbar.php';?>
<div id="layoutSidenav">
    <?php include 'layout/realtor/sidebar.php';?>
    <div id="layoutSidenav_content">
        <main>
            <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
                <div class="container-fluid px-4">
                    <div class="page-header-content">
                        <div class="row align-items-center justify-content-between pt-3">
                            <div class="col-auto mb-3">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i data-feather="list"></i></div>
                                    Pro Service Details
                                </h1>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <a class="btn btn-sm btn-light text-primary" href="<?='pro_services.php'?>">
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
                <div class="row gx-4">
                    <div class="col-lg-2">
                    </div>
                    <div class="col-lg-8">
                        <div class="card mb-4">
                            <div class="card-header">Company Name</div>
                            <div class="card-body"><input class="form-control" id="proServiceTitleInput" type="text" value="<?=$companyName?>" disabled/></div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">Company Email</div>
                            <div class="card-body"><input class="form-control" id="proServiceSubTitleInput" type="text" value="<?=$companyEmail?>" disabled/></div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">Company Phone Number</div>
                            <div class="card-body"><input class="form-control" id="proServiceSubTitleInput" type="text" value="<?=$companyPhoneNumber?>" disabled/></div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">Company Website Link</div>
                            <div class="card-body"><input class="form-control" id="proServiceSubTitleInput" type="text" value="<?=$companyWebsiteLink?>" disabled/></div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">Home Pro Type</div>
                            <div class="card-body"><input class="form-control" id="proServiceSubTitleInput" type="text" value="<?=$homeProType?>" disabled/></div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">Comments</div>
                            <div class="card-body"><textarea class="lh-base form-control" type="text" rows="5" disabled><?=$companyComments?></textarea></div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">My Notes</div>
                            <div class="card-body"><textarea class="lh-base form-control" type="text" rows="5" disabled><?=$companyNotes?></textarea></div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">Image</div>
                            <div class="card-body"><img src="<?=$proServiceImage?>" alt="N/A" height="150px" width="200px"></div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                    </div>
                </div>
            </div>
        </main>
        <?php include 'layout/realtor/footer.php';?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@5" type="text/javascript"></script>
<script src="../Ressources/js/datatables/datatables-simple-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="../Ressources/js/scripts.js"></script>
</body>
</html>
