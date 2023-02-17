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
$blogId = $_GET["blog_id"];
$database = new Firestore_honeydoo();
$blogToShow = $database->fetchBlogById($blogId);
$blogPostTitle = $blogToShow["title"];
$blogPostDistribution = $blogToShow["distribution"];
$blogPostImage = $blogToShow["img"] ?? "";
$encodedblogPostDistribution = json_encode($blogPostDistribution);

//
$realtor = $database->fetchRealtorById($_SESSION["user"]["realtor_id"]);
$helper = new HelperService();
$profilePic = $helper->setProfilePic($realtor);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'layout/realtor/header.php';?>
    <title>Story Details</title>
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
                                    Story Details
                                </h1>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <a class="btn btn-sm btn-light text-primary" href="<?='blog_posts.php'?>">
                                    <i class="me-1" data-feather="arrow-left"></i>
                                    Back to All Stories
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
                            <div class="card-header">Story Title</div>
                            <div class="card-body"><input class="form-control" id="postTitleInput" type="text" value="<?=$blogPostTitle?>" disabled/></div>
                        </div>
                        <div class="card card-header-actions mb-4">
                            <div class="card-header">
                                Story Content
                                <i class="text-muted" title="The post preview text shows below the post title, and is the post summary on blog pages."></i>
                            </div>
                            <div class="card-body"><textarea id="postDistributionShow" class="lh-base form-control" type="text" rows="10" style="resize: none" disabled><?=$blogPostDistribution?></textarea></div>
                        </div>
                        <!--<div class="card card-header-actions mb-4">
                            <div class="card-header">
                                Test
                                <i class="text-muted" title="The post preview text shows below the post title, and is the post summary on blog pages."></i>
                            </div>
                            <div class="card-body"><textarea id="testshow" class="lh-base form-control" type="text" rows="10" style="resize: none" disabled></textarea></div>
                        </div>-->
                        <div class="card card-header-actions mb-4 mb-lg-0">
                            <div class="card-header">
                                Story Image
                                <i class="text-muted" title="Markdown is supported within the post content editor."></i>
                            </div>
                            <div class="card-body">
                                <img src="<?=$blogPostImage?>" alt="" height="150px" width="200px">
                            </div>
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
<script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
    const easyMDE = new EasyMDE({
        element: document.getElementById('postDistributionShow'),
        toolbar: false
    });
    easyMDE.markdown(easyMDE.value());
    easyMDE.togglePreview();
</script>
</body>
</html>
