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
        header("Location: ../View/users_list.php");
    }
}
$blogId = $_GET["blog_id"];
$database = new Firestore_honeydoo();
$blogToEdit = $database->fetchBlogById($blogId);
$blogPostTitle = $blogToEdit["title"];
$blogPostDistribution = $blogToEdit["distribution"];
$encodedblogPostDistribution = json_encode($blogPostDistribution);
//
$realtor = $database->fetchRealtorById($_SESSION["user"]["realtor_id"]);
$helper = new HelperService();
$profilePic = $helper->setProfilePic($realtor);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../layout/realtor/header.php';?>
    <title>Edit Story</title>
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
                                    Edit Story
                                </h1>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <a class="btn btn-sm btn-light text-primary" href="<?='list.php'?>">
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
                <form id="editBlogForm" action="<?='../../Controller/edit_blog_action.php?blog_id=' . $blogId?>" method="post" enctype="multipart/form-data">
                    <div class="row gx-4">
                        <div class="col-lg-8">
                            <div class="card mb-4">
                                <div class="card-header">Story Title</div>
                                <div class="card-body"><input class="form-control" id="postTitleInput" type="text" value="<?=$blogPostTitle?>" name="blogPostTitle" required/></div>
                            </div>
                            <div class="card card-header-actions mb-4">
                                <div class="card-header">
                                    Story Content
                                    <i class="text-muted" title="The post preview text shows below the post title, and is the post summary on blog pages."></i>
                                </div>
                                <div class="card-body"><textarea id="postDistribution" class="lh-base form-control" type="text" rows="10" style="resize: none" name="blogPostDistribution"></textarea></div>
                            </div>
                            <div class="card card-header-actions mb-4 mb-lg-0">
                                <div class="card-header">
                                    Story Image
                                    <i class="text-muted"></i>
                                </div>
                                <div class="card-body">
                                    <input type="file" accept="image/jpeg/png" name="blogPostImage">
                                </div>
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
<script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://unpkg.com/turndown/dist/turndown.js"></script>
<script>
    const easyMDE = new EasyMDE({
        element: document.getElementById('postDistribution'),

    });
    const turndownService = new TurndownService()
    console.log(turndownService.turndown(easyMDE.value()))
    easyMDE.value(turndownService.turndown(<?php echo $encodedblogPostDistribution;  ?>));
    $("#editBlogForm").submit(function (e) {
        let postContent = $("#postDistribution").val()
        $("#postDistribution").val(marked.parse(postContent))
    })
</script>
</body>
</html>
