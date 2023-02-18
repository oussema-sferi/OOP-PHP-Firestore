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
$database = new Firestore_honeydoo();
$realtor = $database->fetchRealtorById($_SESSION["user"]["realtor_id"]);
$helper = new HelperService();
$profilePic = $helper->setProfilePic($realtor);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../layout/realtor/header.php';?>
    <title>My Profile</title>
</head>
<body class="nav-fixed">
<?php include '../layout/realtor/navbar.php';?>
<div id="layoutSidenav">
    <?php include '../layout/realtor/sidebar.php';?>
    <div id="layoutSidenav_content">
        <main>
            <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
                <div class="container-xl px-4">
                    <div class="page-header-content">
                        <div class="row align-items-center justify-content-between pt-3">
                            <div class="col-auto mb-3">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i data-feather="user"></i></div>
                                    My Profile
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Main page content-->
            <div class="container-xl px-4 mt-4">
                <div class="row">
                    <div class="col-xl-4">
                        <!-- Profile picture card-->
                        <form action="<?='../../Controller/add_profile_picture_action.php?realtor_id=' . $realtor["realtor_id"]?>" method="post" enctype="multipart/form-data">
                            <div class="card mb-4 mb-xl-0">
                                <div class="card-header text-center">Add/Edit Profile Picture</div>
                                <div class="card-body text-center">
                                    <!-- Profile picture image-->
                                    <!--<img class="img-account-profile rounded-circle mb-2" src="../Ressources/assets/img/illustrations/profiles/profile-4.png" alt="" />-->
                                    <img class="img-account-profile rounded-circle mb-2" src="<?=$profilePic?>" alt="Profile Picture">
                                    <!-- Profile picture help block-->
                                    <!--<div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>-->
                                    <!-- Profile picture upload button-->
                                    <!--<button class="btn btn-primary" type="button">Upload new image</button>-->
                                    <div class="text-center mt-2">
                                        <input class="form-control mb-2" type="file" accept="image/jpeg/png" name="profilePicture">
                                        <button class="btn btn-success" id="uploadPictureButton" type="submit" disabled>Upload picture</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-xl-8">
                        <!-- Account details card-->
                        <div class="card mb-4">
                            <div class="card-header text-center">Account Details</div>
                            <div class="card-body">
                                <form action="<?='../../Controller/edit_my_profile_action.php?realtor_id=' . $realtor["realtor_id"]?>" method="post" class="mt-4">
                                    <div class="row gx-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="small mb-1" for="inputFirstName">Full name</label>
                                            <input class="form-control" id="fullName" name="fullName" placeholder="Enter your full name..." type="text" value="<?= $realtor["realtor_title"]?>" disabled/>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="small mb-1" for="inputEmailAddress">Email address</label>
                                            <input class="form-control" id="emailAddress" name="emailAddress" placeholder="Enter your email address..." type="email" value="<?= $realtor["email"]?>" disabled/>
                                        </div>
                                    </div>

                                    <div class="row gx-3 mb-3">

                                        <div class="col-md-6">
                                            <label class="small mb-1" for="inputLastName">Phone Number</label>
                                            <input class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Enter your phone number..." type="text" value="<?= $realtor["phone_number"]?>" disabled/>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="small mb-1" for="inputOrgName">Company name</label>
                                            <input class="form-control" id="companyName" name="companyName" placeholder="Enter your company name..." type="text" value="<?= $realtor["realtor_sub_title"]?>" disabled/>
                                        </div>
                                    </div>
                                    <!-- Save changes button-->
                                    <div class="text-center mt-5 mb-1">
                                        <button class="btn btn-primary" id="editButton" type="button">Edit</button>
                                        <button class="btn btn-success" id="saveButton" type="submit" style="display: none">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include '../layout/realtor/footer.php';?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="../../Ressources/js/scripts.js"></script>
<script>
    $( document ).ready(function() {
        $("#editButton").click(function () {
            $(this).hide();
            $("#saveButton").show();
            $("input").prop('disabled', false);
        })
        $("input:file").change(function (){
            if($(this).val())
            {
                $("#uploadPictureButton").prop('disabled', false);
            } else {
                $("#uploadPictureButton").prop('disabled', true);
            }
        });
    });
</script>
</body>
</html>
