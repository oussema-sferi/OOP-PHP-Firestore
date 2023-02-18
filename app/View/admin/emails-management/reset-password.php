<?php
namespace App\View;
use App\Model\Firestore_honeydoo;
use App\Service\HelperService;

require_once "../../../../vendor/autoload.php";

if(!isset($_SESSION["user"]))
{
    header("Location: login.php");
}

if(isset($_SESSION["user"]["role"]) && ($_SESSION["user"]["role"] != "ROLE_ADMIN"))
{
    header("Location: blog_posts.php");
}

$database = new Firestore_honeydoo();
$email = $database->fetchResetEmail();
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
    <?php include '../../layout/admin/header.php';?>
    <title>Reset Password Email</title>
</head>
<body class="nav-fixed">
<?php include '../../layout/admin/navbar.php';?>
<div id="layoutSidenav">
    <?php include '../../layout/admin/sidebar.php';?>
    <div id="layoutSidenav_content">
        <main>
            <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
                <div class="container-fluid px-4">
                    <div class="page-header-content">
                        <div class="row align-items-center justify-content-between pt-3">
                            <div class="col-auto mb-3">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i data-feather="edit"></i></div>
                                    Edit Reset Password Email
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Main page content-->
            <div class="container-fluid px-4">
                <form action="<?='../../../Controller/save_reset_email_action.php'?>" method="post">
                    <div class="row gx-4">
                        <div class="col-lg-2">
                        </div>
                        <div class="col-lg-8">
                            <div class="card mb-4 text-center">
                                <div class="card-header">
                                    Subject
                                </div>
                                <div class="card-body"><input class="form-control" id="postTitleInput" type="text" placeholder="Enter your email subject here..." value="<?=$emailSubjectToEdit?>" name="emailSubject" required/></div>
                            </div>
                            <div class="card mb-4 text-center">
                                <div class="card-header">
                                    Content
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
        <?php include '../../layout/admin/footer.php';?>
    </div>
</div>
<?php include '../../layout/admin/scripts.php';?>
</body>
</html>
