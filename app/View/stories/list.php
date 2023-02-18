<?php
namespace App\View;
use App\Model\Firestore_honeydoo;
use App\Service\HelperService;

require_once "../../../vendor/autoload.php";
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "/app/View/";
if(!isset($_SESSION["user"]))
{
    $loginPage = $baseUrl . 'common/security/login.php';
    header("Location: $loginPage");
} else {
    if(isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "ROLE_ADMIN")
    {
        $adminUsersPage = $baseUrl . 'admin/users/list.php';
        header("Location: $adminUsersPage");
    }
}
$database = new Firestore_honeydoo();
$loggedUserBlogPosts = $database->fetchBlogPosts($_SESSION["user"]["realtor_id"]);

//
$realtor = $database->fetchRealtorById($_SESSION["user"]["realtor_id"]);
$helper = new HelperService();
$profilePic = $helper->setProfilePic($realtor);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../layout/realtor/header.php';?>
    <title>My Stories</title>
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
                                    All My Stories
                                </h1>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <a class="btn btn-sm btn-light text-primary" href="<?='new.php'?>">
                                    <i class="me-1" data-feather="plus"></i>
                                    Create New Story
                                </a>
                            <div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Main page content-->
            <div class="container-fluid px-4">
                <div class="card">
                    <div class="card-body">
                        <table id="datatablesSimple">
                            <thead>
                            <tr>
                                <th>Story Title</th>
                                <th>Date Created</th>
                                <th>Views</th>
                                <th>Likes</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Story Title</th>
                                <th>Date Created</th>
                                <th>Views</th>
                                <th>Likes</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            <?php
                            foreach ($loggedUserBlogPosts as $blogPost)
                                {
                                    $title = $blogPost->title;
                                    $createdAt = $blogPost->date->get()->format("m-d-Y");
                                    if(isset($blogPost->views)) {
                                        $views = $blogPost->views;
                                    } else {
                                        $views = 0;
                                    }

                                    if(isset($blogPost->user_liked)) {
                                        $likes = count($blogPost->user_liked);
                                    } else {
                                        $likes = 0;
                                    }
                                    $blogPostId = $blogPost->doc_id;
                                    $showBlogPostLink = "show.php?blog_id=$blogPostId";
                                    $editBlogPostLink = "edit.php?blog_id=$blogPostId";
                                    $deleteBlogPostLink = "../../Controller/delete_blog_action.php?blog_id=$blogPostId";
                                    echo "
                                <tr>
                                    <td>$title</td>
                                    <td>$createdAt</td>
                                    <td>$views</td>
                                    <td>$likes</td>
                                    <td class='text-center'>
                                        <a class='btn btn-datatable btn-icon btn-transparent-dark me-2' href=$showBlogPostLink><i data-feather='zoom-in'></i></a>
                                        <a class='btn btn-datatable btn-icon btn-transparent-dark me-2' href=$editBlogPostLink><i data-feather='edit'></i></a>
                                        <a class='btn btn-datatable btn-icon btn-transparent-dark' data-bs-toggle='modal' data-bs-target='#approveUserModal$blogPostId'><i data-feather='trash-2'></i></a>                                                                                                                              
                                    </td>                                                      
                                </tr>
                                
                                <div class='modal fade' id='approveUserModal$blogPostId' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>
                                    <div class='modal-dialog modal-dialog-centered' role='document'>
                                        <div class='modal-content'>
                                            <div class='modal-header d-block'>
                                                <button class='btn-close float-end' type='button' data-bs-dismiss='modal' aria-label='Close'></button>
                                                <h5 class='modal-title text-center' id='exampleModalCenterTitle'>Removal Confirmation</h5>
                                            </div>
                                            <div class='modal-body text-center'>
                                                Do you really want to delete this story ?
                                            </div>
                                            <div class='modal-footer justify-content-center'>
                                                <a class='btn btn-secondary' type='button' data-bs-dismiss='modal'>No</a>
                                                <a class='btn btn-success' type='button' href='$deleteBlogPostLink'>Yes</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                ";
                                };
                            ?>
                            </tbody>
                        </table>
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
