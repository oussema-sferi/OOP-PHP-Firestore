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

//
$database = new Firestore_honeydoo();
$realtor = $database->fetchRealtorById($_SESSION["user"]["realtor_id"]);
$helper = new HelperService();
$profilePic = $helper->setProfilePic($realtor);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../layout/realtor/header.php';?>
    <title>Create Pro Service</title>
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
                                    Create Pro Service
                                </h1>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <a class="btn btn-sm btn-light text-primary" href="<?='list.php'?>">
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
                <form action="<?='../../Controller/add_pro_service_action.php'?>" method="post" enctype="multipart/form-data">
                    <div class="row gx-4">
                        <div class="col-lg-8">
                            <div class="card mb-4">
                                <div class="card-header">Company Name</div>
                                <div class="card-body"><input class="form-control" id="proServiceTitleInput" type="text" placeholder="Enter company name..." name="companyName" required/></div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-header">Company Email</div>
                                <div class="card-body"><input class="form-control" id="proServiceSubTitleInput" type="text" placeholder="Enter company email..." name="companyEmail" required/></div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-header">Company Phone Number</div>
                                <div class="card-body"><input class="form-control" id="proServiceSubTitleInput" type="text" placeholder="Enter company phone number..." name="companyPhoneNumber" required/></div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-header">Company Website Link</div>
                                <div class="card-body"><input class="form-control" id="proServiceSubTitleInput" type="text" placeholder="Enter company website link..." name="companyWebsiteLink" required/></div>
                            </div>
                            <!--<div class="card mb-4">
                                <div class="card-header">Home Pro Type</div>
                                <div class="card-body"><input class="form-control" id="proServiceSubTitleInput" type="text" placeholder="Enter home pro type..." name="homeProType" required/></div>
                            </div>-->
                            <div class="card mb-4">
                                <div class="card-header">Home Pro Type</div>
                                <div class="card-body">
                                <select class="form-control" id="homeProType" name="homeProType" required>
                                    <option>Select Home Pro Type</option>
                                    <option value="Cleaning">Cleaning</option>
                                    <option value="Electrical">Electrical</option>
                                    <option value="Fencing">Fencing</option>
                                    <option value="Handy person">Handy person</option>
                                    <option value="Home Renovation">Home Renovation</option>
                                    <option value="HVAC">HVAC</option>
                                    <option value="Inspector">Inspector</option>
                                    <option value="Lawncare">Lawncare</option>
                                    <option value="Painting">Painting</option>
                                    <option value="Pest Control">Pest Control</option>
                                    <option value="Plumbing">Plumbing</option>
                                    <option value="Power Washing">Power Washing</option>
                                    <option value="Roofing">Roofing</option>
                                    <option value="Security System">Security System</option>
                                    <option value="Windows">Windows</option>
                                    <option value="Other">Other</option>
                                </select>
                                </div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-header">Comments</div>
                                <div class="card-body"><textarea class="lh-base form-control" type="text" placeholder="Enter your comments..." rows="5" name="comments" required></textarea></div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-header">My Notes</div>
                                <div class="card-body"><textarea class="lh-base form-control" type="text" placeholder="Enter your notes..." rows="5" name="myNotes" required></textarea></div>
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
        <?php include '../layout/realtor/footer.php';?>
    </div>
</div>
<?php include '../layout/realtor/scripts.php';?>
</body>
</html>
